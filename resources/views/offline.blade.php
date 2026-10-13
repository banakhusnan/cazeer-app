<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#00B3A4">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <title>Offline · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
</head>
<body>
<div class="container min-vh-100 d-flex align-items-center justify-content-center text-center"><div class="offline-card"><img class="offline-logo" src="{{ asset('assets/images/logos/cazeer-brand.png') }}" alt="Cazeer by Bana Khusnan"><div class="fw-bold mb-2">Tidak ada koneksi</div><p class="text-muted">Transaksi membutuhkan koneksi agar stok dan penjualan tetap konsisten.</p><button class="btn btn-primary" onclick="location.reload()">Coba lagi</button></div></div>
</body>
</html>
