<aside class="sidebar" id="sidebar">

    <div class="logo">

        <div class="logo-icon">
            <i class="bi bi-shield-fill-check"></i>
        </div>

        <div class="logo-text">
            ADMIN
        </div>

    </div>

    {{-- ================= Dashboard ================= --}}

    <span class="menu-heading">
        DASHBOARD
    </span>

    <a href="{{ route('admin.dashboard') }}"
       class="menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

        <i class="bi bi-grid"></i>

        <span>Dashboard</span>

    </a>

    {{-- ================= Master Data ================= --}}

    <span class="menu-heading">
        MASTER DATA
    </span>

    <a href="{{ route('admin.jenis-barang.index') }}"
       class="menu {{ request()->routeIs('admin.jenis-barang.*') ? 'active' : '' }}">

        <i class="bi bi-box-seam"></i>

        <span>Jenis Barang</span>

    </a>

    <a href="{{ route('admin.pelanggan.index') }}"
       class="menu {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">

        <i class="bi bi-people"></i>

        <span>Pelanggan</span>

    </a>

    <a href="{{ route('admin.barang.index') }}"
       class="menu {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">

        <i class="bi bi-bag"></i>

        <span>Barang</span>

    </a>

    {{-- ================= Transaksi ================= --}}

    <span class="menu-heading">
        TRANSAKSI
    </span>

    <a href="{{ route('admin.pengajuan-gadai.index') }}"
       class="menu {{ request()->routeIs('admin.pengajuan-gadai.*') ? 'active' : '' }}">

        <i class="bi bi-wallet2"></i>

        <span>Pengajuan Gadai</span>

    </a>

        <a href="{{ route('admin.penyerahan-barang.index') }}"
       class="menu {{ request()->routeIs('admin.penyerahan-barang.*') ? 'active' : '' }}">

        <i class="bi bi-wallet2"></i>

        <span>Penyerahan Barang</span>

    </a>


    <a href="{{ route('admin.transaksi-gadai.index') }}"
       class="menu {{ request()->routeIs('admin.transaksi-gadai.*') ? 'active' : '' }}">

        <i class="bi bi-wallet2"></i>

        <span>Gadai</span>

    </a>

    <a href="{{ route('admin.transaksi-perpanjangan.index') }}"
       class="menu {{ request()->routeIs('admin.transaksi-perpanjangan.*') ? 'active' : '' }}">

        <i class="bi bi-arrow-repeat"></i>

        <span>Perpanjangan</span>

    </a>

    <a href="{{ route('admin.transaksi-penjualan.index') }}"
       class="menu {{ request()->routeIs('admin.transaksi-penjualan.*') ? 'active' : '' }}">

        <i class="bi bi-cart-check"></i>

        <span>Penjualan</span>

    </a>

    {{-- ================= Laporan ================= --}}

    <span class="menu-heading">
        LAPORAN
    </span>

    <a href="{{ route('admin.laporan') }}"
       class="menu {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">

        <i class="bi bi-file-earmark-text"></i>

        <span>Laporan</span>

    </a>

    {{-- ================= Profile ================= --}}

    <span class="menu-heading">
        AKUN
    </span>

    <a href="{{ route('admin.profile') }}"
       class="menu {{ request()->routeIs('admin.profile') ? 'active' : '' }}">

        <i class="bi bi-person-circle"></i>

        <span>Profile</span>

    </a>

</aside>
