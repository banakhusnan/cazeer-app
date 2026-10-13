<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\Concerns\HandlesServiceErrors;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    use HandlesServiceErrors;

    public function create(array $data, int $userId): Product
    {
        return $this->execute('product.create', function () use ($data, $userId) {
            return DB::transaction(function () use ($data, $userId) {
                $stock = (int) $data['initial_stock'];
                $image = $data['image'] ?? null;
                $attributes = Arr::except($data, ['initial_stock', 'image']);
                if ($image instanceof UploadedFile) {
                    $attributes['image_path'] = $image->store('products', 'public');
                }
                $product = Product::query()->create([...$attributes, 'stock' => $stock]);

                if ($stock > 0) {
                    StockMovement::query()->create([
                        'product_id' => $product->id, 'user_id' => $userId, 'type' => 'in',
                        'quantity' => $stock, 'stock_before' => 0, 'stock_after' => $stock,
                        'notes' => 'Stok awal produk.',
                    ]);
                }

                return $product;
            });
        });
    }

    public function update(Product $product, array $data): Product
    {
        return $this->execute('product.update', function () use ($product, $data) {
            $image = $data['image'] ?? null;
            $attributes = Arr::except($data, ['initial_stock', 'image']);
            if ($image instanceof UploadedFile) {
                $this->deleteUploadedImage($product->image_path);
                $attributes['image_path'] = $image->store('products', 'public');
            }
            $product->update($attributes);

            return $product->refresh();
        });
    }

    public function delete(Product $product): void
    {
        $this->execute('product.delete', function () use ($product) {
            $product->delete();
        });
    }

    private function deleteUploadedImage(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'assets/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
