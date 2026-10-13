<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_soft_delete_product_with_uniform_json(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $payload = ['code' => 'TAN-100', 'name' => 'Tanaman Test', 'category' => 'Test', 'unit' => 'pot', 'selling_price' => 75000, 'initial_stock' => 4, 'description' => null, 'is_active' => true];

        $created = $this->actingAs($admin)->postJson(route('products.store'), $payload)
            ->assertCreated()->assertJsonPath('success', true)->assertJsonStructure(['success', 'message', 'data', 'errors', 'meta']);
        $product = Product::query()->findOrFail($created->json('data.id'));
        $this->assertSame(4, $product->stock);
        $this->assertSame(75000, $product->selling_price);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'stock_after' => 4]);

        $this->actingAs($admin)->putJson(route('products.update', $product), [...$payload, 'name' => 'Tanaman Diperbarui', 'selling_price' => 80000])
            ->assertOk()->assertJsonPath('data.name', 'Tanaman Diperbarui')->assertJsonPath('data.selling_price', 80000);
        $this->actingAs($admin)->deleteJson(route('products.destroy', $product))->assertOk();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_cashier_cannot_manage_products(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier)->get(route('products.index'))->assertForbidden();
    }

    public function test_admin_can_upload_product_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(route('products.store'), [
            'code' => 'TAN-IMG', 'name' => 'Tanaman Bergambar', 'category' => 'Test',
            'unit' => 'pot', 'selling_price' => 50000, 'initial_stock' => 2, 'description' => null, 'is_active' => true,
            'image' => UploadedFile::fake()->image('tanaman.jpg', 300, 300),
        ])->assertCreated();

        $path = $response->json('data.image_path');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_product_requires_a_positive_selling_price(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->postJson(route('products.store'), [
            'code' => 'NO-PRICE', 'name' => 'Produk Tanpa Harga', 'unit' => 'pcs',
            'selling_price' => 0, 'initial_stock' => 1, 'is_active' => true,
        ])->assertUnprocessable()->assertJsonValidationErrors('selling_price');

        $this->assertDatabaseMissing('products', ['code' => 'NO-PRICE']);
    }

    public function test_seeder_fills_missing_dummy_price_without_resetting_stock(): void
    {
        Product::query()->create([
            'code' => 'TAN-001', 'name' => 'Monstera Lama', 'unit' => 'pot',
            'selling_price' => 0, 'stock' => 99, 'is_active' => true,
        ]);

        $this->seed();

        $product = Product::query()->where('code', 'TAN-001')->firstOrFail();
        $this->assertSame(150000, $product->selling_price);
        $this->assertSame(99, $product->stock);
    }
}
