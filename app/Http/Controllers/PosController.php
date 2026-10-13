<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesJsonExceptions;
use App\Http\Requests\CartItemRequest;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Services\CartService;
use App\Services\SaleService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class PosController extends Controller
{
    use HandlesJsonExceptions;

    public function __construct(private readonly CartService $cartService, private readonly SaleService $saleService) {}

    public function index(): View
    {
        return view('pos.index', [
            'products' => Product::query()->select(['id', 'code', 'name', 'category', 'unit', 'selling_price', 'stock', 'image_path'])
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->where('selling_price', '>', 0)
                ->orderBy('name')
                ->get(),
            'cart' => $this->cartService->summary(),
        ]);
    }

    public function cart(): JsonResponse
    {
        return ApiResponse::success('Isi keranjang.', $this->cartService->summary());
    }

    public function add(CartItemRequest $request): JsonResponse
    {
        try {
            return ApiResponse::created('Produk ditambahkan ke keranjang.', $this->cartService->add($request->validated()));
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }

    public function remove(string $rowId): JsonResponse
    {
        try {
            return ApiResponse::success('Item dihapus dari keranjang.', $this->cartService->remove($rowId));
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        try {
            $sale = $this->saleService->checkout($request->validated(), $request->user()->id);

            return ApiResponse::created('Transaksi berhasil disimpan.', [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'show_url' => route('sales.show', $sale),
                'receipt_url' => route('sales.receipt', $sale),
                'print_receipt' => $request->boolean('print_receipt'),
            ]);
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }
}
