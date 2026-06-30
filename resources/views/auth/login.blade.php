<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Persuratan Gadai</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-dark: #0a1930;
            --primary-blue: #1d3557;
            --accent-blue: #3b82f6;
            --bg-light: #f3f4f6;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        .page-header {
            padding: 20px 40px;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        .page-header h1 {
            font-size: 24px;
            color: var(--primary-dark);
            font-weight: 700;
        }
        
        .page-header h2 {
            font-size: 14px;
            color: var(--primary-blue);
            font-weight: 500;
            margin-bottom: 5px;
        }

        .header-line {
            width: 30px;
            height: 3px;
            background-color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Main Container */
        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            display: flex;
            width: 100%;
            max-width: 1000px;
            overflow: hidden;
        }

        /* Left Side (Features) */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
            padding: 60px 40px;
            color: white;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('https://images.unsplash.com/photo-1621501103258-3e125c197ff8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: 1;
        }

        .login-left-content {
            position: relative;
            z-index: 2;
        }

        .main-icon {
            font-size: 48px;
            color: #fbbf24;
            margin-bottom: 20px;
        }

        .login-left h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .login-left > p {
            font-size: 15px;
            color: #d1d5db;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #60a5fa;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .feature-text p {
            font-size: 13px;
            color: #9ca3af;
            line-height: 1.5;
        }

        /* Right Side (Form) */
        .login-right {
            flex: 1;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-area .icon {
            font-size: 36px;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .logo-area h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 35px;
        }

        .welcome-text h2 {
            font-size: 22px;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .welcome-text p {
            font-size: 14px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #9ca3af;
            font-size: 18px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
        }

        .toggle-password:hover {
            color: var(--text-dark);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #112a4f;
        }

        .error-message {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .copyright {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 40px;
        }

        /* Footer Features */
        .footer-features {
            background: white;
            padding: 30px 40px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            border-top: 1px solid var(--border-color);
        }

        .footer-feature {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .footer-feature i {
            font-size: 24px;
            color: var(--primary-blue);
        }

        .footer-feature h5 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .footer-feature p {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .login-left {
                display: none; /* Sembunyikan sisi kiri di mobile */
            }
            .login-right {
                padding: 40px 30px;
            }
            .footer-features {
                grid-template-columns: repeat(2, 1fr);
            }
            .page-header {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .login-right {
                padding: 40px 20px;
            }
            .footer-features {
                grid-template-columns: 1fr;
            }
            .main-container {
                padding: 20px 15px;
            }
            .login-card {
                border-radius: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Header (Hanya tampil di Desktop) -->
    <div class="page-header">
        <h2>HALAMAN LOGIN</h2>
        <h1>Sistem Informasi Persuratan Gadai</h1>
        <div class="header-line"></div>
        <p>Aman, mudah digunakan, dan profesional untuk mendukung bisnis UMKM.</p>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <div class="login-card">
            
            <!-- Kiri (Features Desktop) -->
            <div class="login-left">
                <div class="login-left-content">
                    <i class="bi bi-shield-lock main-icon"></i>
                    <h2>Sistem Informasi<br>Persuratan Gadai</h2>
                    <p style="margin-bottom:40px;">Kelola transaksi gadai dengan lebih mudah, cepat, aman, dan terpercaya.</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
                            <div class="feature-text">
                                <h4>Aman & Terpercaya</h4>
                                <p>Data tersimpan aman dengan sistem terproteksi.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                            <div class="feature-text">
                                <h4>Efisien & Cepat</h4>
                                <p>Proses pencatatan dan pencarian data lebih cepat dan akurat.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-qr-code-scan"></i></div>
                            <div class="feature-text">
                                <h4>Terintegrasi</h4>
                                <p>Surat gadai digital dengan QR Code untuk kemudahan verifikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan (Form Login) -->
            <div class="login-right">
                <div class="logo-area">
                    <i class="bi bi-hexagon-fill icon"></i>
                    <h3>UD GERLIAN JAYA</h3>
                </div>

                <div class="welcome-text">
                    <h2>Selamat Datang Kembali!</h2>
                    <p>Silakan masuk untuk melanjutkan</p>
                </div>

                @if($errors->any())
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Username / No. HP</label>
                        <div class="input-group">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username atau No. HP" value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" name="password" id="passwordField" class="form-control" placeholder="Masukkan kata sandi" required>
                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" style="accent-color: var(--primary-dark);"> Ingat saya
                        </label>
                        <a href="#" class="forgot-link">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </button>
                </form>

                <div class="copyright">
                    &copy; {{ date('Y') }} UD Gerlian Jaya. Semua hak dilindungi.
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Features -->
    <div class="footer-features">
        <div class="footer-feature">
            <i class="bi bi-shield-check"></i>
            <div>
                <h5>Aman</h5>
                <p>Sistem keamanan berlapis untuk melindungi data transaksi Anda.</p>
            </div>
        </div>
        <div class="footer-feature">
            <i class="bi bi-clock-history"></i>
            <div>
                <h5>Cepat</h5>
                <p>Akses data dan informasi dengan cepat kapan saja dibutuhkan.</p>
            </div>
        </div>
        <div class="footer-feature">
            <i class="bi bi-phone"></i>
            <div>
                <h5>Responsif</h5>
                <p>Tampilan optimal di semua perangkat, desktop maupun mobile.</p>
            </div>
        </div>
        <div class="footer-feature">
            <i class="bi bi-people"></i>
            <div>
                <h5>Mudah Digunakan</h5>
                <p>Antarmuka sederhana dan intuitif, cocok untuk semua pengguna.</p>
            </div>
        </div>
    </div>

    <script>
        // Fitur Toggle Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordField');

        togglePassword.addEventListener('click', function (e) {
            // toggle type
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle icon
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
