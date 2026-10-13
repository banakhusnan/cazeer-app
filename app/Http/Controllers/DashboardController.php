<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StoreSetting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $todaySales = Sale::query()->whereDate('transaction_at', today());
        $threshold = StoreSetting::query()->value('low_stock_threshold') ?? 5;

        return view('dashboard', [
            'salesTotal' => (clone $todaySales)->sum('total_amount'),
            'salesCount' => (clone $todaySales)->count(),
            'lowStockCount' => Product::query()->where('is_active', true)->where('stock', '<=', $threshold)->count(),
            'recentSales' => Sale::query()->select(['id', 'invoice_number', 'cashier_id', 'transaction_at', 'total_amount', 'payment_method'])->with('cashier:id,name')->latest('transaction_at')->limit(5)->get(),
        ]);
    }
}
