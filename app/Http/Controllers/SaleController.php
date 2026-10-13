<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\StoreSetting;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SaleController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $sales = $this->visibleSales($request)->select(['id', 'invoice_number', 'cashier_id', 'transaction_at', 'customer_name', 'total_amount', 'payment_method'])->with('cashier:id,name')->latest('transaction_at')->get();

            return ApiResponse::success('Riwayat transaksi.', $sales);
        }

        return view('sales.index');
    }

    public function show(Request $request, Sale $sale): View
    {
        $this->authorizeSale($request, $sale);

        return view('sales.show', ['sale' => $sale->load(['cashier:id,name', 'items.product:id,name,unit'])]);
    }

    public function receipt(Request $request, Sale $sale): View
    {
        $this->authorizeSale($request, $sale);

        return view('sales.receipt', [
            'sale' => $sale->load(['cashier:id,name', 'items.product:id,name,unit']),
            'store' => StoreSetting::query()->select(['store_name', 'address', 'phone', 'logo_path'])->first(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        $sales = $this->visibleSales($request)
            ->join('users', 'users.id', '=', 'sales.cashier_id')
            ->select(['sales.invoice_number', 'sales.transaction_at', 'sales.customer_name', 'sales.total_amount', 'sales.payment_method', 'users.name as cashier_name'])
            ->orderBy('sales.transaction_at')
            ->cursor();

        return response()->streamDownload(function () use ($sales) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No Nota', 'Waktu', 'Kasir', 'Pelanggan', 'Metode', 'Total']);
            foreach ($sales as $sale) {
                fputcsv($handle, [$sale->invoice_number, $sale->transaction_at, $sale->cashier_name, $sale->customer_name, $sale->payment_method, $sale->total_amount]);
            }
            fclose($handle);
        }, 'laporan-penjualan-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }

    private function visibleSales(Request $request): Builder
    {
        return Sale::query()
            ->when(! $request->user()->isAdmin(), fn (Builder $query) => $query->where('cashier_id', $request->user()->id))
            ->when($request->filled('date_from'), fn (Builder $query) => $query->whereDate('transaction_at', '>=', $request->string('date_from')))
            ->when($request->filled('date_to'), fn (Builder $query) => $query->whereDate('transaction_at', '<=', $request->string('date_to')))
            ->when($request->filled('invoice'), fn (Builder $query) => $query->where('invoice_number', 'like', '%'.$request->string('invoice').'%'));
    }

    private function authorizeSale(Request $request, Sale $sale): void
    {
        abort_unless($request->user()->isAdmin() || $sale->cashier_id === $request->user()->id, 403);
    }
}
