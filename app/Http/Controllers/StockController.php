<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesJsonExceptions;
use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class StockController extends Controller
{
    use HandlesJsonExceptions;

    public function __construct(private readonly StockService $service) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            $movements = StockMovement::query()->select(['id', 'product_id', 'user_id', 'type', 'quantity', 'stock_before', 'stock_after', 'notes', 'created_at'])->with(['product:id,code,name', 'user:id,name'])->latest()->get();

            return ApiResponse::success('Riwayat stok.', $movements);
        }

        return view('stocks.index', ['products' => Product::query()->select(['id', 'code', 'name', 'stock'])->where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(StockAdjustmentRequest $request): JsonResponse
    {
        try {
            return ApiResponse::created('Stok berhasil diperbarui.', $this->service->adjust($request->validated(), $request->user()->id));
        } catch (Throwable $exception) {
            return $this->jsonFailure($exception);
        }
    }
}
