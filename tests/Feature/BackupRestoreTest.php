<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupRestoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_backup_can_be_created_and_restored(): void
    {
        Storage::fake('local');
        Product::query()->create(['code' => 'BACKUP-1', 'name' => 'Produk Backup', 'unit' => 'pot', 'stock' => 3, 'is_active' => true]);

        $this->artisan('app:backup-database')->assertSuccessful();
        $file = collect(Storage::disk('local')->files('backups'))->first();
        $this->assertNotNull($file);
        Product::query()->delete();

        $this->artisan('app:restore-database', ['file' => $file, '--force' => true])->assertSuccessful();
        $this->assertDatabaseHas('products', ['code' => 'BACKUP-1', 'stock' => 3]);
    }
}
