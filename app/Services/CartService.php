<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Concerns\HandlesServiceErrors;
use Illuminate\Session\Store;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartService
{
    use HandlesServiceErrors;

    private const KEY = 'pos_cart_v2';

    public function __construct(private readonly Store $session) {}

    public function add(array $data): array
    {
        return $this->execute('cart.add', function () use ($data) {
            $product = Product::query()->select(['id', 'code', 'name', 'unit', 'selling_price', 'stock', 'is_active'])->findOrFail($data['product_id']);
            if (! $product->is_active || $product->stock < (int) $data['quantity']) {
                throw ValidationException::withMessages(['quantity' => 'Produk tidak aktif atau stok tidak mencukupi.']);
            }
            if ($product->selling_price < 1) {
                throw ValidationException::withMessages(['product_id' => 'Harga jual produk belum diatur.']);
            }

            $cart = $this->items();
            $rowId = (string) Str::uuid();
            $cart[$rowId] = [
                'row_id' => $rowId, 'product_id' => $product->id, 'code' => $product->code,
                'name' => $product->name, 'unit' => $product->unit, 'quantity' => (int) $data['quantity'],
                'price' => $product->selling_price, 'subtotal' => (int) $data['quantity'] * $product->selling_price,
                'notes' => $data['notes'] ?? null,
            ];
            $this->session->put(self::KEY, $cart);

            return $this->summary();
        });
    }

    public function remove(string $rowId): array
    {
        return $this->execute('cart.remove', function () use ($rowId) {
            $cart = $this->items();
            unset($cart[$rowId]);
            $this->session->put(self::KEY, $cart);

            return $this->summary();
        });
    }

    public function items(): array
    {
        return $this->session->get(self::KEY, []);
    }

    public function summary(): array
    {
        $items = array_values($this->items());

        return ['items' => $items, 'item_count' => array_sum(array_column($items, 'quantity')), 'total' => array_sum(array_column($items, 'subtotal'))];
    }

    public function clear(): void
    {
        $this->session->forget(self::KEY);
    }
}
