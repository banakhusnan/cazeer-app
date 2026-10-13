<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterfaceContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_crud_pages_use_modals_ajax_and_datatables(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('products.index'))
            ->assertOk()
            ->assertSee('assets/css/style.css')
            ->assertSee('assets/images/faces/face')
            ->assertSee('Buka menu pengguna')
            ->assertSee('container-scroller')
            ->assertSee('navbar-brand-wrapper')
            ->assertSee('page-body-wrapper')
            ->assertSee('sidebar-offcanvas')
            ->assertSee('main-panel')
            ->assertSee('content-wrapper')
            ->assertSee('assets/js/off-canvas.js')
            ->assertSee('assets/js/template.js')
            ->assertSee('productModal')
            ->assertSee('productPreview')
            ->assertSee('DataTable')
            ->assertSee('deleteModal');
        $this->get(route('users.index'))->assertOk()->assertSee('userModal')->assertSee('DataTable');
        $this->get(route('stocks.index'))->assertOk()->assertSee('stockModal')->assertSee('DataTable');
        $this->get(route('pos.index'))->assertOk()->assertSee('printChoiceModal')->assertSee('Selesaikan & Cetak', false);
    }
}
