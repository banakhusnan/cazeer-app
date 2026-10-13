<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_and_authenticated_pages_use_cazeer_branding(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk · Cazeer', false)
            ->assertSee('assets/images/logos/cazeer-brand.png')
            ->assertDontSee('Plantory');

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('products.index'))
            ->assertOk()
            ->assertSee('Produk · Cazeer', false)
            ->assertSee('Sistem POS untuk Bisnis Anda')
            ->assertSee('assets/images/logos/cazeer-wordmark.png')
            ->assertSee('assets/images/logos/cazeer-icon-clean.png')
            ->assertDontSee('Plantory')
            ->assertDontSee('Bisnis Tanaman');
    }

    public function test_web_manifest_declares_complete_cazeer_icon_set(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('Cazeer', $manifest['name']);
        $this->assertSame('Cazeer', $manifest['short_name']);
        $this->assertSame('#00B3A4', $manifest['theme_color']);
        $this->assertSame(['192x192', '512x512', '512x512'], array_column($manifest['icons'], 'sizes'));
        $this->assertSame(['any', 'any', 'maskable'], array_column($manifest['icons'], 'purpose'));

        foreach ($manifest['icons'] as $icon) {
            $path = public_path(ltrim($icon['src'], '/'));
            $this->assertFileExists($path);
            [$width, $height] = getimagesize($path);
            $expectedSize = (int) explode('x', $icon['sizes'])[0];
            $this->assertSame($expectedSize, $width);
            $this->assertSame($expectedSize, $height);
        }
    }

    public function test_receipt_keeps_store_identity_separate_from_application_brand(): void
    {
        $receipt = file_get_contents(resource_path('views/sales/receipt.blade.php'));

        $this->assertStringContainsString("'TOKO'", $receipt);
        $this->assertStringContainsString('$store?->logo_path', $receipt);
        $this->assertStringNotContainsString('cazeer', strtolower($receipt));
    }

    public function test_service_worker_caches_only_current_cazeer_brand_assets(): void
    {
        $serviceWorker = file_get_contents(public_path('service-worker.js'));

        $this->assertStringContainsString("const CACHE = 'cazeer-v2'", $serviceWorker);
        $this->assertStringContainsString('assets/images/logos/cazeer-brand.png', $serviceWorker);
        $this->assertStringContainsString('assets/images/icons/maskable-icon-512x512.png', $serviceWorker);
        $this->assertStringNotContainsString('plantory', strtolower($serviceWorker));
    }
}
