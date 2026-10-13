<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Services\Concerns\HandlesServiceErrors;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleService
{
    use HandlesServiceErrors;

    public function __construct(private readonly CartService $cartService) {}

    public function checkout(array $data, int $cashierId): Sale
    {
        return $this->execute('sale.checkout', function () use ($data, $cashierId) {
            $cart = $this->cartService->items();
            if ($cart === []) {
                throw ValidationException::withMessages(['cart' => 'Keranjang masih kosong.']);
            }

            $sale = DB::transaction(function () use ($data, $cashierId, $cart) {
                $productIds = array_values(array_unique(array_column($cart, 'product_id')));
                $products = Product::query()->select(['id', 'name', 'stock', 'is_active'])->whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');
                $required = [];
                foreach ($cart as $item) {
                    $required[$item['product_id']] = ($required[$item['product_id']] ?? 0) + $item['quantity'];
                }

                foreach ($required as $productId => $quantity) {
                    $product = $products->get($productId);
                    if (! $product || ! $product->is_active || $product->stock < $quantity) {
                        throw ValidationException::withMessages(['cart' => 'Stok salah satu produk tidak mencukupi. Muat ulang produk dan periksa keranjang.']);
                    }
                }

                $total = array_sum(array_column($cart, 'subtotal'));
                $paid = $data['payment_method'] === 'cash' ? (int) ($data['paid_amount'] ?? 0) : $total;
                if ($paid < $total) {
                    throw ValidationException::withMessages(['paid_amount' => 'Uang diterima tidak boleh kurang dari total transaksi.']);
                }

                $sale = Sale::query()->create([
                    'invoice_number' => $this->invoiceNumber(), 'cashier_id' => $cashierId,
                    'transaction_at' => now(), 'customer_name' => $data['customer_name'] ?? null,
                    'total_amount' => $total, 'paid_amount' => $paid, 'change_amount' => $paid - $total,
                    'payment_method' => $data['payment_method'], 'notes' => $data['notes'] ?? null,
                ]);

                foreach ($cart as $item) {
                    $product = $products->get($item['product_id']);
                    $before = $product->stock;
                    $after = $before - $item['quantity'];
                    $sale->items()->create([
                        'product_id' => $product->id, 'quantity' => $item['quantity'], 'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'], 'notes' => $item['notes'],
                    ]);
                    $product->update(['stock' => $after]);
                    StockMovement::query()->create([
                        'product_id' => $product->id, 'user_id' => $cashierId, 'sale_id' => $sale->id,
                        'type' => 'out', 'quantity' => $item['quantity'], 'stock_before' => $before,
                        'stock_after' => $after, 'notes' => 'Penjualan '.$sale->invoice_number,
                    ]);
                }

                return $sale;
            });

            $this->cartService->clear();

            return $sale->load(['cashier:id,name', 'items.product:id,name,unit']);
        });
    }

    private function invoiceNumber(): string
    {
        return 'TRX-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(4));
    }
}
