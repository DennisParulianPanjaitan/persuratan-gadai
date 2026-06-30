<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan | Gerlian Jaya</title>
    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Bootstrap Icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body>

<div class="wrapper">
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <div class="logo-text">PELANGGAN</div>
        </div>

        <span class="menu-heading">DASHBOARD</span>
        <a href="{{ route('pelanggan.dashboard') }}" class="menu {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>

        <span class="menu-heading">LAINNYA</span>
        <a href="{{ url('/') }}" class="menu">
            <i class="bi bi-house"></i>
            <span>Ke Halaman Utama</span>
        </a>
    </aside>

    <div class="main">
        <nav class="navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-title">@yield('page_title', 'Dashboard')</div>
            </div>

            <div class="navbar-right">
                <div class="user-profile" id="userProfileBtn">
                    <div class="avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="user-info">
                        @php
                            $pelangganNav = \App\Models\PelangganModels::where('id_user', Auth::id())->first();
                            $namaNav = $pelangganNav ? $pelangganNav->nama : (Auth::user()->username ?? 'Pelanggan');
                        @endphp
                        <span class="name">{{ $namaNav }}</span>
                        <span class="role">Pelanggan</span>
                    </div>
                    <i class="bi bi-chevron-down arrow-icon"></i>

                    <div class="dropdown-menu" id="userDropdown">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="content">
            @if (session('success'))
                <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
            @endif
            @if (session('error'))
                <div id="flash-error" data-message="{{ session('error') }}" style="display: none;"></div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script>
    // Toggle Sidebar
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (menuToggle && sidebar && overlay) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Toggle Dropdown
    const userProfileBtn = document.getElementById('userProfileBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userProfileBtn && userDropdown) {
        userProfileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            userProfileBtn.classList.toggle('active');
        });

        window.addEventListener('click', (e) => {
            if (!userProfileBtn.contains(e.target)) {
                userDropdown.classList.remove('show');
                userProfileBtn.classList.remove('active');
            }
        });
    }

    // SweetAlert Flash Messages
    const flashSuccess = document.getElementById('flash-success');
    if (flashSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: flashSuccess.dataset.message,
            timer: 3000,
            showConfirmButton: false
        });
    }

    const flashError = document.getElementById('flash-error');
    if (flashError) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: flashError.dataset.message,
            timer: 3000,
            showConfirmButton: false
        });
    }
</script>
@stack('scripts')
</body>
</html>
