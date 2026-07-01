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

    <a href="{{ route('pelanggan.dashboard') }}"
       class="menu {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">

        <i class="bi bi-grid"></i>

        <span>Dashboard</span>

    </a>

    {{-- ================= Transaksi ================= --}}

    <span class="menu-heading">
        TRANSAKSI
    </span>

    <a href="{{ route('pelanggan.pengajuan_gadai.create') }}"
       class="menu {{ request()->routeIs('pelanggan.pengajuan_gadai.create') ? 'active' : '' }}">

        <i class="bi bi-file-earmark-plus"></i>

        <span>Pengajuan Gadai</span>

    </a>

    <a href="{{ route('pelanggan.riwayat.index') }}"
       class="menu {{ request()->routeIs('pelanggan.riwayat.index') ? 'active' : '' }}">

        <i class="bi bi-clock-history"></i>

        <span>Status & Riwayat Gadai</span>

    </a>

    <a href="#"
       class="menu">

        <i class="bi bi-shop"></i>

        <span>Pembelian Barang</span>

    </a>

    {{-- ================= Pengaturan ================= --}}

    <span class="menu-heading">
        PENGATURAN
    </span>

    <a href="#"
       class="menu">

        <i class="bi bi-person-gear"></i>

        <span>Profil Akun</span>

    </a>

    <a href="{{ url('/') }}"
       class="menu">

        <i class="bi bi-house"></i>

        <span>Ke Halaman Utama</span>

    </a>

</aside>
