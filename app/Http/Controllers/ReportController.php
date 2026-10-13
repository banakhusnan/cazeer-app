<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = Carbon::parse($request->input('date_from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->input('date_to', now()->toDateString()))->endOfDay();
        $sales = Sale::query()->whereBetween('transaction_at', [$from, $to]);
        $topProducts = SaleItem::query()
            ->select(['product_id'])
            ->selectRaw('SUM(quantity) as quantity_sold, SUM(subtotal) as revenue')
            ->whereHas('sale', fn ($query) => $query->whereBetween('transaction_at', [$from, $to]))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('quantity_sold')
            ->limit(10)
            ->get();

        return view('reports.index', [
            'from' => $from, 'to' => $to,
            'total' => (clone $sales)->sum('total_amount'),
            'count' => (clone $sales)->count(),
            'average' => (int) (clone $sales)->avg('total_amount'),
            'topProducts' => $topProducts,
        ]);
    }
}
