<aside class="sidebar" id="sidebar">

    <div class="logo" style="gap: 10px;">

        <div class="logo-icon" style="font-size: 22px; color: #fff;">
            <i class="bi bi-hexagon-fill"></i>
        </div>

        <div class="logo-text" style="font-size: 16px; font-weight: 800; color: #fff; letter-spacing: 0.5px;">
            UD GERLIAN JAYA
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

    <a href="{{ route('admin.jenis_barang.index') }}"
       class="menu {{ request()->routeIs('admin.jenis_barang.*') ? 'active' : '' }}">

        <i class="bi bi-tags"></i>

        <span>Jenis Barang</span>

    </a>

    <a href="{{ route('admin.pelanggan.index') }}"
       class="menu {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">

        <i class="bi bi-people-fill"></i>

        <span>Pelanggan</span>

    </a>

    <a href="{{ route('admin.barang.index') }}"
       class="menu {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">

        <i class="bi bi-boxes"></i>

        <span>Barang</span>

    </a>

    {{-- ================= Transaksi ================= --}}

    <span class="menu-heading">
        TRANSAKSI
    </span>

    <a href="{{ route('admin.pengajuan_gadai.index') }}"
       class="menu {{ request()->routeIs('admin.pengajuan_gadai.*') ? 'active' : '' }}">

        <i class="bi bi-file-earmark-check"></i>

        <span>Pengajuan Gadai</span>

    </a>

        <a href="{{ route('admin.penyerahan_barang.index') }}"
       class="menu {{ request()->routeIs('admin.penyerahan_barang.*') ? 'active' : '' }}">

        <i class="bi bi-box-arrow-in-down"></i>

        <span>Penyerahan Barang</span>

    </a>


    <a href="{{ route('admin.transaksi_gadai.index') }}"
       class="menu {{ request()->routeIs('admin.transaksi_gadai.*') ? 'active' : '' }}">

        <i class="bi bi-cash-coin"></i>

        <span>Gadai</span>

    </a>



    <a href="{{ route('admin.transaksi_penjualan.index') }}"
       class="menu {{ request()->routeIs('admin.transaksi_penjualan.*') ? 'active' : '' }}">

        <i class="bi bi-cart-check-fill"></i>

        <span>Penjualan</span>

    </a>

    {{-- ================= Laporan ================= --}}

    <span class="menu-heading">
        LAPORAN
    </span>

    <a href="{{ route('admin.laporan') }}"
       class="menu {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">

        <i class="bi bi-file-earmark-bar-graph"></i>

        <span>Laporan</span>

    </a>

    {{-- ================= Profile ================= --}}

    <span class="menu-heading">
        AKUN
    </span>

    <a href="{{ route('admin.profile') }}"
       class="menu {{ request()->routeIs('admin.profile') ? 'active' : '' }}">

        <i class="bi bi-person-gear"></i>

        <span>Profile</span>

    </a>

</aside>
