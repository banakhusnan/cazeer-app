<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@cazeer.test',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Kasir',
                'email' => 'kasir@cazeer.test',
                'password' => 'password',
                'role' => 'cashier',
                'is_active' => true,
            ]
        );

        $samples = [
            ['code' => 'TAN-001', 'name' => 'Monstera Deliciosa', 'category' => 'Tanaman Hias Daun', 'unit' => 'pot', 'selling_price' => 150000, 'stock' => 12, 'image_path' => 'assets/images/samples/300x300/1.jpg'],
            ['code' => 'TAN-002', 'name' => 'Aglaonema Merah', 'category' => 'Tanaman Hias Daun', 'unit' => 'pot', 'selling_price' => 85000, 'stock' => 18, 'image_path' => 'assets/images/samples/300x300/2.jpg'],
            ['code' => 'TAN-003', 'name' => 'Kaktus Mini', 'category' => 'Sukulen', 'unit' => 'pot', 'selling_price' => 35000, 'stock' => 7, 'image_path' => 'assets/images/samples/300x300/3.jpg'],
            ['code' => 'TAN-004', 'name' => 'Calathea Orbifolia', 'category' => 'Tanaman Hias Daun', 'unit' => 'pot', 'selling_price' => 125000, 'stock' => 9, 'image_path' => 'assets/images/samples/300x300/4.jpg'],
            ['code' => 'TAN-005', 'name' => 'Lavender', 'category' => 'Tanaman Berbunga', 'unit' => 'pot', 'selling_price' => 65000, 'stock' => 11, 'image_path' => 'assets/images/samples/300x300/5.jpg'],
            ['code' => 'TAN-006', 'name' => 'Sirih Gading', 'category' => 'Tanaman Rambat', 'unit' => 'pot', 'selling_price' => 45000, 'stock' => 15, 'image_path' => 'assets/images/samples/300x300/6.jpg'],
        ];
        foreach ($samples as $sample) {
            $product = Product::query()->firstOrCreate(['code' => $sample['code']], [...$sample, 'is_active' => true]);
            $updates = [];
            if ($product->selling_price < 1) {
                $updates['selling_price'] = $sample['selling_price'];
            }
            if (! $product->image_path) {
                $updates['image_path'] = $sample['image_path'];
            }
            if ($updates !== []) {
                $product->update($updates);
            }
            StockMovement::query()->firstOrCreate(['product_id' => $product->id, 'notes' => 'Stok awal seeder.'], [
                'user_id' => $admin->id,
                'type' => 'in',
                'quantity' => $product->stock,
                'stock_before' => 0,
                'stock_after' => $product->stock,
            ]);
        }

        StoreSetting::query()->firstOrCreate([], [
            'store_name' => 'Toko',
            'address' => 'Silakan atur alamat toko',
            'phone' => '08xx-xxxx-xxxx',
            'low_stock_threshold' => 5,
        ]);
    }
}
