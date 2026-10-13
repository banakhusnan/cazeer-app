<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StoreSettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::view('/offline', 'offline')->name('offline');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/jual', [PosController::class, 'index'])->name('pos.index');
    Route::get('/jual/keranjang', [PosController::class, 'cart'])->name('pos.cart');
    Route::post('/jual/keranjang', [PosController::class, 'add'])->name('pos.cart.add');
    Route::delete('/jual/keranjang/{rowId}', [PosController::class, 'remove'])->name('pos.cart.remove');
    Route::post('/jual/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

    Route::get('/transaksi', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/transaksi/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/transaksi/{sale}/nota', [SaleController::class, 'receipt'])->name('sales.receipt');

    Route::middleware('role:admin')->group(function () {
        Route::resource('produk', ProductController::class)->parameters(['produk' => 'product'])->except(['create', 'edit'])->names('products');
        Route::resource('pengguna', UserController::class)->parameters(['pengguna' => 'user'])->except(['create', 'edit'])->names('users');
        Route::get('/stok', [StockController::class, 'index'])->name('stocks.index');
        Route::post('/stok', [StockController::class, 'store'])->name('stocks.store');
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/export', [SaleController::class, 'export'])->name('reports.export');
        Route::get('/pengaturan-toko', [StoreSettingController::class, 'edit'])->name('settings.edit');
        Route::post('/pengaturan-toko', [StoreSettingController::class, 'update'])->name('settings.update');
    });
});
