<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin | Gerlian Jaya</title>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Bootstrap Icon --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body>

<div class="wrapper">

    {{-- Overlay gelap saat sidebar mobile terbuka --}}
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    @include('partials.admin.sidebar')

    <div class="main">

        @include('partials.admin.navbar')

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

<script src="{{ asset('assets/js/layout.js') }}"></script>

@stack('scripts')

</body>
</html>
