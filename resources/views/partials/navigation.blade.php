<nav class="sidebar sidebar-offcanvas no-print" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('dashboard') }}"><i class="icon-grid menu-icon"></i><span class="menu-title">Beranda</span></a></li>
        <li class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('pos.index') }}"><i class="icon-bag menu-icon"></i><span class="menu-title">Jual</span></a></li>
        <li class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('sales.index') }}"><i class="icon-paper menu-icon"></i><span class="menu-title">Riwayat</span></a></li>
        @if(auth()->user()->isAdmin())
            <li class="nav-item mt-3"><span class="nav-link text-muted"><span class="menu-title">ADMIN</span></span></li>
            <li class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('products.index') }}"><i class="icon-box menu-icon"></i><span class="menu-title">Produk</span></a></li>
            <li class="nav-item {{ request()->routeIs('stocks.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('stocks.index') }}"><i class="icon-layers menu-icon"></i><span class="menu-title">Stok</span></a></li>
            <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('users.index') }}"><i class="icon-head menu-icon"></i><span class="menu-title">Pengguna</span></a></li>
            <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('reports.index') }}"><i class="icon-bar-graph menu-icon"></i><span class="menu-title">Laporan</span></a></li>
            <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('settings.edit') }}"><i class="icon-cog menu-icon"></i><span class="menu-title">Pengaturan</span></a></li>
        @endif
    </ul>
</nav>
