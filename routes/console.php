<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

Artisan::command('app:backup-database', function () {
    $tables = ['users', 'products', 'sales', 'sale_items', 'stock_movements', 'store_settings'];
    $backup = ['created_at' => now()->toIso8601String(), 'connection' => DB::getDefaultConnection(), 'tables' => []];
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            $backup['tables'][$table] = DB::table($table)->orderBy('id')->get()->map(fn ($row) => (array) $row)->all();
        }
    }
    $filename = 'backups/cazeer-'.now()->format('Ymd-His').'.json.gz';
    Storage::disk('local')->put($filename, gzencode(json_encode($backup, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), 9));
    $this->info('Backup tersimpan: storage/app/private/'.$filename);
})->purpose('Membuat backup data utama Cazeer dalam JSON terkompresi');

Artisan::command('app:restore-database {file : Path relatif pada disk local} {--force : Konfirmasi pemulihan yang menimpa data}', function () {
    if (! $this->option('force')) {
        $this->error('Gunakan --force setelah memastikan file dan database tujuan sudah benar.');

        return 1;
    }

    $file = $this->argument('file');
    if (! Storage::disk('local')->exists($file)) {
        $this->error('File backup tidak ditemukan.');

        return 1;
    }

    try {
        $decoded = gzdecode(Storage::disk('local')->get($file));
        $backup = json_decode($decoded, true, flags: JSON_THROW_ON_ERROR);
        $tables = ['users', 'products', 'sales', 'sale_items', 'stock_movements', 'store_settings'];
        DB::transaction(function () use ($backup, $tables) {
            Schema::disableForeignKeyConstraints();
            try {
                foreach (array_reverse($tables) as $table) {
                    if (Schema::hasTable($table)) {
                        DB::table($table)->delete();
                    }
                }
                foreach ($tables as $table) {
                    foreach (array_chunk($backup['tables'][$table] ?? [], 500) as $rows) {
                        DB::table($table)->insert($rows);
                    }
                }
            } finally {
                Schema::enableForeignKeyConstraints();
            }
        });
        $this->info('Database berhasil dipulihkan dari '.$file);
    } catch (Throwable $exception) {
        Log::error('Pemulihan database gagal.', ['file' => $file, 'exception' => $exception]);
        $this->error('Pemulihan gagal: '.$exception->getMessage());

        return 1;
    }

    return 0;
})->purpose('Memulihkan data utama Cazeer dari backup JSON terkompresi');

Schedule::command('app:backup-database')->dailyAt('01:00')->withoutOverlapping();
