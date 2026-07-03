<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - Sistem Informasi Persuratan Gadai</title>
    
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
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 600px;
            padding: 40px;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 25px;
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
            margin-bottom: 30px;
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

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            margin-bottom: 0;
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

        .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.3s;
            resize: vertical;
            min-height: 80px;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
            margin-bottom: 15px;
        }

        .btn-login:hover {
            background-color: #112a4f;
        }

        .btn-back {
            width: 100%;
            padding: 14px;
            background-color: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #f9fafb;
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
        
        .is-invalid {
            border-color: #dc2626 !important;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-area">
            <i class="bi bi-hexagon-fill icon"></i>
            <h3>UD GERLIAN JAYA</h3>
        </div>

        <div class="welcome-text">
            <h2>Buat Akun Pelanggan</h2>
            <p>Silakan isi data diri Anda untuk mendaftar.</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap Anda" required autofocus>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <i class="bi bi-person-badge input-icon"></i>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Contoh: yoru_satoru" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Handphone</label>
                    <div class="input-group">
                        <i class="bi bi-phone input-icon"></i>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email (Opsional)</label>
                <div class="input-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@gmail.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-textarea @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap Anda" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Sandi</label>
                    <div class="input-group">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-person-plus"></i> Daftar Sekarang
            </button>

            <a href="{{ route('login') }}" class="btn-back">
                Batal
            </a>
        </form>
    </div>

</body>
</html>
