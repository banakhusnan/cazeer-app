<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_view_all_sales_and_reprint_other_users_receipts(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $owners = [$cashier, User::factory()->create(['role' => 'admin']), User::factory()->create(['role' => 'cashier'])];
        $sales = array_map(fn (User $owner) => $this->createSale($owner), $owners);

        $this->actingAs($cashier)->get(route('sales.index'))->assertOk();
        $response = $this->getJson(route('sales.index'), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertJsonCount(3, 'data');

        foreach ($sales as $sale) {
            $response->assertJsonFragment(['invoice_number' => $sale->invoice_number]);
            $this->get(route('sales.show', $sale))->assertOk()->assertSee($sale->invoice_number);
            $this->get(route('sales.receipt', ['sale' => $sale, 'embedded' => 1]))
                ->assertOk()->assertSee($sale->invoice_number)->assertSee($sale->cashier->name);
        }
    }

    public function test_cashier_can_filter_other_users_sales_by_invoice_and_date(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $admin = User::factory()->create(['role' => 'admin']);
        $sale = $this->createSale($admin);
        $this->createSale($cashier);

        $filters = ['invoice' => $sale->invoice_number, 'date_from' => '2026-10-13', 'date_to' => '2026-10-13'];
        $this->actingAs($cashier)->getJson(route('sales.index', $filters), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', $sale->id);

        $filters['date_from'] = '2026-10-14';
        $this->getJson(route('sales.index', $filters), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_sale_pages_still_require_login(): void
    {
        $sale = $this->createSale(User::factory()->create(['role' => 'admin']));

        foreach (['sales.index', 'sales.show', 'sales.receipt'] as $route) {
            $this->get(route($route, $route === 'sales.index' ? [] : ['sale' => $sale]))
                ->assertRedirect(route('login'));
        }
    }

    public function test_reports_and_export_remain_admin_only(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'cashier']));
        $this->get(route('reports.index'))->assertForbidden();
        $this->get(route('reports.export'))->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get(route('reports.export'))->assertOk();
    }

    private function createSale(User $owner): Sale
    {
        return Sale::query()->create([
            'invoice_number' => 'INV-ACCESS-'.$owner->id,
            'cashier_id' => $owner->id,
            'transaction_at' => '2026-10-13 10:00:00',
            'total_amount' => 10000,
            'paid_amount' => 10000,
            'change_amount' => 0,
            'payment_method' => 'cash',
        ]);
    }
}
