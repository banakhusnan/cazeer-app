<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_calculates_server_totals_and_reduces_stock_atomically(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::query()->create(['code' => 'TAN-001', 'name' => 'Monstera', 'unit' => 'pot', 'selling_price' => 150000, 'stock' => 10, 'is_active' => true]);

        $this->actingAs($cashier)->postJson(route('pos.cart.add'), ['product_id' => $product->id, 'quantity' => 2, 'price' => 1, 'notes' => null])
            ->assertCreated()->assertJsonPath('data.total', 300000);
        $checkout = $this->postJson(route('pos.checkout'), ['payment_method' => 'cash', 'paid_amount' => 350000, 'customer_name' => 'Bana', 'notes' => null, 'print_receipt' => true])
            ->assertCreated()->assertJsonPath('data.print_receipt', true);

        $this->assertDatabaseHas('sales', ['id' => $checkout->json('data.id'), 'total_amount' => 300000, 'change_amount' => 50000]);
        $this->assertDatabaseHas('sale_items', ['product_id' => $product->id, 'quantity' => 2, 'subtotal' => 300000]);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'type' => 'out', 'stock_before' => 10, 'stock_after' => 8]);
        $this->assertSame(8, $product->fresh()->stock);
        $this->getJson(route('pos.cart'))->assertJsonPath('data.item_count', 0);
    }

    public function test_checkout_rejects_insufficient_payment_without_writing_sale(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::query()->create(['code' => 'ANG-002', 'name' => 'Dendrobium', 'unit' => 'pot', 'selling_price' => 100000, 'stock' => 2, 'is_active' => true]);
        $this->actingAs($cashier)->postJson(route('pos.cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

        $this->postJson(route('pos.checkout'), ['payment_method' => 'cash', 'paid_amount' => 50000, 'print_receipt' => false])
            ->assertUnprocessable()->assertJsonPath('success', false);
        $this->assertDatabaseCount('sales', 0);
        $this->assertSame(2, $product->fresh()->stock);
    }

    public function test_product_without_a_selling_price_cannot_be_added_to_cart(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::query()->create(['code' => 'NO-PRICE', 'name' => 'Belum Dihargai', 'unit' => 'pcs', 'stock' => 2, 'is_active' => true]);

        $this->actingAs($cashier)->postJson(route('pos.cart.add'), ['product_id' => $product->id, 'quantity' => 1])
            ->assertUnprocessable()->assertJsonValidationErrors('product_id');

        $this->actingAs($cashier)->get(route('pos.index'))->assertOk()->assertDontSee('Belum Dihargai');
    }

    public function test_cart_keeps_the_price_snapshot_when_product_price_changes(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $product = Product::query()->create(['code' => 'SNAPSHOT', 'name' => 'Produk Snapshot', 'unit' => 'pcs', 'selling_price' => 25000, 'stock' => 3, 'is_active' => true]);

        $this->actingAs($cashier)->postJson(route('pos.cart.add'), ['product_id' => $product->id, 'quantity' => 2])
            ->assertCreated()->assertJsonPath('data.total', 50000);

        $product->update(['selling_price' => 30000]);

        $this->postJson(route('pos.checkout'), ['payment_method' => 'cash', 'paid_amount' => 50000, 'print_receipt' => false])
            ->assertCreated();
        $this->assertDatabaseHas('sale_items', ['product_id' => $product->id, 'price' => 25000, 'subtotal' => 50000]);
    }
}
