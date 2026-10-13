<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesJsonExceptions;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    use HandlesJsonExceptions;

    public function __construct(private readonly ProductService $service) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            $products = Product::query()->select(['id', 'code', 'name', 'category', 'unit', 'selling_price', 'stock', 'description', 'image_path', 'is_active'])->latest('id')->get();

            return ApiResponse::success('Data produk.', $products);
        }

        return view('products.index');
    }

    public function show(Product $product): JsonResponse
    {
        return ApiResponse::success('Detail produk.', [...$product->only(['id', 'code', 'name', 'category', 'unit', 'selling_price', 'stock', 'description', 'image_path', 'is_active']), 'image_url' => $product->image_url]);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        try {
            $product = $this->service->create($request->validated(), $request->user()->id);

            return ApiResponse::created('Produk berhasil ditambahkan.', $product);
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            return ApiResponse::success('Produk berhasil diperbarui.', $this->service->update($product, $request->validated()));
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->service->delete($product);

            return ApiResponse::success('Produk berhasil dinonaktifkan dari daftar.');
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }
}
