@extends('layouts.app')
@section('title', 'Riwayat · '.config('app.name'))
@section('page-title','Riwayat Transaksi')
@section('page-subtitle','Cari, periksa, dan cetak ulang transaksi')
@section('content')
<div class="card filter-card mb-3"><div class="card-body"><form id="filterForm" class="row g-2"><div class="col-md-3"><input class="form-control" name="invoice" placeholder="Nomor nota"></div><div class="col-6 col-md-3"><input class="form-control" type="date" name="date_from"></div><div class="col-6 col-md-3"><input class="form-control" type="date" name="date_to"></div><div class="col-md-3"><button class="btn btn-primary w-100"><i class="mdi mdi-filter-outline"></i> Terapkan Filter</button></div></form></div></div>
<div class="card"><div class="card-body table-responsive"><table id="saleTable" class="table align-middle w-100"><thead><tr><th>Nota</th><th>Waktu</th><th>Kasir</th><th>Pelanggan</th><th>Metode</th><th>Total</th><th>Aksi</th></tr></thead></table></div></div>
@include('partials.receipt-modal')
@endsection
@push('scripts')<script>$(function(){const table=$('#saleTable').DataTable({ajax:{url:@json(route('sales.index')),data:d=>Object.assign(d,Object.fromEntries(new FormData($('#filterForm')[0]))),dataSrc:'data'},order:[[1,'desc']],columns:[{data:'invoice_number',render:escapeHtml},{data:'transaction_at',render:v=>new Date(v).toLocaleString('id-ID')},{data:'cashier.name',render:escapeHtml},{data:'customer_name',defaultContent:'-',render:escapeHtml},{data:'payment_method',render:v=>v.toUpperCase()},{data:'total_amount',render:v=>`Rp ${money(v)}`},{data:'id',orderable:false,render:id=>`<div class="d-flex gap-1"><a class="btn btn-light btn-sm" href="{{ url('/transaksi') }}/${id}"><i class="mdi mdi-eye-outline"></i> Detail</a><a class="btn btn-primary btn-sm" data-receipt-modal href="{{ url('/transaksi') }}/${id}/nota"><i class="mdi mdi-printer"></i> Cetak</a></div>`}]});$('#filterForm').submit(e=>{e.preventDefault();table.ajax.reload()});});</script>@endpush
