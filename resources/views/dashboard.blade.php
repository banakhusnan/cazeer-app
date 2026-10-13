@extends('layouts.app')
@section('title', 'Beranda · '.config('app.name'))
@section('page-title', 'Beranda')
@section('page-subtitle', 'Ringkasan operasional hari ini')
@section('page-actions')<a href="{{ route('pos.index') }}" class="btn btn-primary">
    <i class="mdi mdi-cart-plus"></i> Mulai Penjualan</a>@endsection
@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-4">
            <div class="card dashboard-stat stat-sales h-100">
                <div class="card-body">
                    <span class="stat-icon"><i class="mdi mdi-cash-multiple"></i></span>
                    <div><div class="stat-label">Penjualan hari ini</div><div class="stat-value fw-bold money">Rp {{ number_format($salesTotal, 0, ',', '.') }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4">
            <div class="card dashboard-stat stat-transactions h-100">
                <div class="card-body">
                    <span class="stat-icon"><i class="mdi mdi-receipt-text-check"></i></span>
                    <div><div class="stat-label">Transaksi hari ini</div><div class="stat-value fw-bold">{{ $salesCount }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4">
            <div class="card dashboard-stat stat-stock h-100">
                <div class="card-body">
                    <span class="stat-icon"><i class="mdi mdi-package-variant-closed-minus"></i></span>
                    <div><div class="stat-label">Stok menipis</div><div class="stat-value fw-bold">{{ $lowStockCount }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header section-card-header"><span class="section-card-icon"><i class="mdi mdi-history"></i></span><span>Transaksi terbaru</span></div>
        <div class="card-body table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nota</th>
                        <th>Waktu</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->transaction_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->cashier->name }}</td>
                            <td class="money">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            <td><a class="btn btn-light btn-sm" href="{{ route('sales.show', $sale) }}"><i class="mdi mdi-eye-outline"></i> Detail</a></td>
                    </tr>@empty<tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
