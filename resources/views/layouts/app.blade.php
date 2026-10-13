<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#00B3A4">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/icons/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/icons/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    @stack('styles')
</head>
<body>
@php($avatarNumber = ((auth()->id() - 1) % 28) + 1)
<div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row no-print">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5" href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/logos/cazeer-wordmark.png') }}" class="me-2" alt="Cazeer"></a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/logos/cazeer-icon-clean.png') }}" alt="Cazeer"></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize" aria-label="Perkecil sidebar"><span class="icon-menu"></span></button>
            <ul class="navbar-nav me-auto d-none d-lg-flex"><li class="nav-item"><span class="nav-link text-muted">Sistem POS untuk Bisnis Anda</span></li></ul>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown" aria-expanded="false" aria-label="Buka menu pengguna">
                        <img src="{{ asset('assets/images/faces/face'.$avatarNumber.'.jpg') }}" alt="Foto {{ auth()->user()->name }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <div class="px-3 py-2"><div class="font-weight-bold text-dark">{{ auth()->user()->name }}</div><div class="text-muted">{{ auth()->user()->role === 'admin' ? 'Admin' : 'Kasir' }}</div></div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit"><i class="ti-power-off text-primary me-2"></i>Keluar</button></form>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas" aria-label="Buka navigasi"><span class="icon-menu"></span></button>
        </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
        @include('partials.navigation')
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row mb-4"><div class="col-12 col-xl-8 mb-3 mb-xl-0"><div class="page-heading"><span class="page-heading-icon"><i class="mdi {{ request()->routeIs('dashboard') ? 'mdi-view-dashboard-outline' : (request()->routeIs('pos.*') ? 'mdi-cart-outline' : (request()->routeIs('sales.*') ? 'mdi-receipt-text-outline' : (request()->routeIs('products.*') ? 'mdi-package-variant' : (request()->routeIs('stocks.*') ? 'mdi-package-variant-closed' : (request()->routeIs('users.*') ? 'mdi-account-group-outline' : (request()->routeIs('reports.*') ? 'mdi-chart-box-outline' : 'mdi-cog-outline')))))) }}"></i></span><div><h3 class="font-weight-bold mb-1">@yield('page-title')</h3><h6 class="font-weight-normal mb-0 text-muted">@yield('page-subtitle')</h6></div></div></div><div class="col-12 col-xl-4"><div class="d-flex justify-content-xl-end">@yield('page-actions')</div></div></div>
                @yield('content')
            </div>
            <footer class="footer no-print"><div class="d-sm-flex justify-content-center justify-content-sm-between"><span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Cazeer · Sistem POS untuk Bisnis Anda</span><span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">{{ now()->year }}</span></div></footer>
        </div>
    </div>
</div>
<div class="toast-container position-fixed top-0 end-0 p-3"><div id="appToast" class="toast" role="alert"><div class="toast-header"><strong class="me-auto">{{ config('app.name') }}</strong><button type="button" class="btn-close" data-bs-dismiss="toast"></button></div><div class="toast-body"></div></div></div>
<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><div class="font-weight-bold">Konfirmasi Hapus</div><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">Hapus <strong id="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.</div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-danger" id="confirmDelete"><i class="mdi mdi-delete"></i> Ya, Hapus</button></div></div></div></div>
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net/js/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script>
$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}});
window.money = value => new Intl.NumberFormat('id-ID').format(Number(value || 0));
window.escapeHtml = value => $('<div>').text(value ?? '').html();
window.notify = (message, success = true) => { const toast = $('#appToast'); toast.removeClass('text-bg-success text-bg-danger').addClass(success ? 'text-bg-success' : 'text-bg-danger'); toast.find('.toast-body').text(message); bootstrap.Toast.getOrCreateInstance(toast[0]).show(); };
window.clearErrors = form => { $(form).find('.field-error').text(''); $(form).find('.is-invalid').removeClass('is-invalid'); };
window.showErrors = (form, errors = {}) => { clearErrors(form); Object.entries(errors).forEach(([field, messages]) => { $(form).find(`[name="${field}"]`).addClass('is-invalid'); $(form).find(`[data-error="${field}"]`).text(messages[0]); }); };
window.buttonLoading = (button, loading) => { const el = $(button); if (!el.data('label')) el.data('label', el.html()); el.prop('disabled', loading).html(loading ? '<span class="spinner-border spinner-border-sm"></span> Memproses' : el.data('label')); };
if ('serviceWorker' in navigator) window.addEventListener('load', () => navigator.serviceWorker.register('{{ asset('service-worker.js') }}'));
</script>
@stack('scripts')
</body>
</html>
