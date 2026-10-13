<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\Concerns\HandlesServiceErrors;
use Illuminate\Support\Facades\DB;

class StockService
{
    use HandlesServiceErrors;

    public function adjust(array $data, int $userId): StockMovement
    {
        return $this->execute('stock.adjust', function () use ($data, $userId) {
            return DB::transaction(function () use ($data, $userId) {
                $product = Product::query()->select(['id', 'stock'])->lockForUpdate()->findOrFail($data['product_id']);
                $before = $product->stock;
                $after = $data['type'] === 'in' ? $before + (int) $data['quantity'] : (int) $data['quantity'];
                $quantity = $data['type'] === 'in' ? (int) $data['quantity'] : abs($after - $before);

                $product->update(['stock' => $after]);

                return StockMovement::query()->create([
                    'product_id' => $product->id, 'user_id' => $userId, 'type' => $data['type'],
                    'quantity' => $quantity, 'stock_before' => $before, 'stock_after' => $after,
                    'notes' => $data['notes'],
                ]);
            });
        });
    }
}
