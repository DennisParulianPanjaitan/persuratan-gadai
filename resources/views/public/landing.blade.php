<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD Gerlian Jaya - Gadai Mudah & Bunga Murah</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-dark: #0a1930;
            --primary-blue: #1d3557;
            --accent-blue: #3b82f6;
            --accent-gold: #fbbf24;
            --bg-light: #f8fafc;
            --text-dark: #1f2937;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            scroll-behavior: smooth;
        }

        /* Navbar */
        .navbar {
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-dark);
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .brand .icon {
            font-size: 28px;
            color: var(--primary-dark);
        }

        .btn-login {
            background-color: var(--primary-dark);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login:hover {
            background-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(29, 53, 87, 0.2);
        }

        /* Hero Section (Marketing) */
        .hero {
            padding: 100px 20px;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-blue));
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('https://images.unsplash.com/photo-1579621970588-a35d0e7ab9b6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
        }

        .tagline {
            display: inline-block;
            background: rgba(251, 191, 36, 0.2);
            color: var(--accent-gold);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid rgba(251, 191, 36, 0.5);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 25px;
            line-height: 1.2;
            color: #ffffff;
        }

        .hero h1 span {
            color: var(--accent-gold);
        }

        .hero p {
            font-size: 18px;
            color: #e2e8f0;
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: var(--accent-gold);
            color: var(--primary-dark);
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary:hover {
            background-color: #f59e0b;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(251, 191, 36, 0.3);
        }

        .btn-secondary {
            background-color: transparent;
            color: white;
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid white;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary:hover {
            background-color: white;
            color: var(--primary-dark);
            transform: translateY(-3px);
        }

        .btn-login-glow {
            background: linear-gradient(135deg, #3b82f6, #2dd4bf);
            color: white;
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-login-glow::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        .btn-login-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.6);
            color: white;
        }

        /* Features */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: -60px auto 60px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .feature-box {
            text-align: center;
            padding: 40px 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-bottom: 4px solid transparent;
            transition: all 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .feature-box:nth-child(1):hover { border-color: #10b981; }
        .feature-box:nth-child(2):hover { border-color: var(--accent-gold); }
        .feature-box:nth-child(3):hover { border-color: var(--accent-blue); }

        .feature-box i {
            font-size: 45px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .feature-box:nth-child(1) i { color: #10b981; } /* Hijau (Bunga Rendah) */
        .feature-box:nth-child(2) i { color: var(--accent-gold); } /* Emas (Cepat) */
        .feature-box:nth-child(3) i { color: var(--accent-blue); } /* Biru (Aman) */

        .feature-box h4 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .feature-box p {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Tracker Section */
        .tracker-section {
            padding: 60px 20px;
            background-color: white;
            border-top: 1px solid #e2e8f0;
        }

        .tracker-container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .tracker-container h2 {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .tracker-container p {
            color: var(--text-muted);
            margin-bottom: 40px;
            font-size: 16px;
        }

        .search-box {
            background: var(--bg-light);
            padding: 10px;
            border-radius: 12px;
            display: flex;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 15px 25px;
            font-size: 18px;
            outline: none;
            color: var(--text-dark);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .search-btn {
            background-color: var(--primary-dark);
            color: white;
            border: none;
            padding: 0 35px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            background-color: var(--primary-blue);
        }

        /* Result Section */
        .result-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            text-align: left;
            border-top: 5px solid var(--accent-blue);
            max-width: 800px;
            margin: 0 auto;
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .result-header h3 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-aktif { background: #dbeafe; color: #1e40af; }
        .status-ditebus { background: #dcfce7; color: #166534; }
        .status-dijual { background: #fee2e2; color: #991b1b; }

        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .result-item {
            display: flex;
            flex-direction: column;
        }

        .result-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 5px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .result-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            border: 1px solid #fca5a5;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Footer */
        footer {
            background-color: var(--primary-dark);
            color: white;
            text-align: center;
            padding: 40px 20px;
            margin-top: auto;
            font-size: 14px;
        }

        footer p {
            color: #94a3b8;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .hero { padding: 60px 20px 100px; }
            .hero h1 { font-size: 36px; }
            .hero-buttons { flex-direction: column; }
            .features { grid-template-columns: 1fr; gap: 20px; margin-top: -40px; }
            .search-box { flex-direction: column; background: transparent; box-shadow: none; border: none; padding: 0; gap: 15px; }
            .search-input { background: white; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
            .search-btn { padding: 15px; justify-content: center; }
            .result-grid { grid-template-columns: 1fr; }
            .navbar { padding: 15px 20px; }
            .brand span { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="brand">
            <i class="bi bi-hexagon-fill icon"></i>
            <span>UD GERLIAN JAYA</span>
        </a>
        <a href="{{ route('login') }}" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
    </nav>

    <!-- Hero Section (Marketing Focused) -->
    <section class="hero">
        <div class="hero-content">
            <div class="tagline">Gadai Aman, Hati Tenang</div>
            <h1>Solusi Dana Cepat dengan <span>Bunga Super Rendah</span></h1>
            <p>Gadaikan barang berharga Anda di UD Gerlian Jaya. Kami menawarkan taksiran nilai tertinggi, pencairan dana langsung hari ini juga, dan yang terpenting: Bunga sangat bersahabat untuk meringankan Anda.</p>
            
            <div class="hero-buttons">
                <a href="#cek-status" class="btn-primary">
                    <i class="bi bi-search"></i> Cek Status Gadai Anda
                </a>
                <a href="{{ route('login') }}" class="btn-login-glow">
                    <i class="bi bi-person-workspace"></i> Masuk ke Akun Anda
                </a>
                <a href="https://wa.me/6285100653370" class="btn-secondary" target="_blank">
                    <i class="bi bi-whatsapp"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Marquee Promosi Barang Lelang (Kotak Besar Menunjol) -->
    @if(isset($barangDijual) && $barangDijual->count() > 0)
    <section style="padding: 20px 20px 60px; max-width: 1200px; margin: 0 auto; position: relative; z-index: 5;">
        <div style="background: white; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); padding: 40px 20px; position: relative; border-top: 6px solid var(--accent-gold);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="font-size: 28px; font-weight: 800; color: var(--primary-dark);"><i class="bi bi-megaphone-fill" style="color: var(--accent-gold);"></i> Penawaran Spesial</h2>
                <p style="color: var(--text-muted);">Barang lelang berkualitas dengan harga terbaik. Segera hubungi kami sebelum kehabisan!</p>
            </div>
            
            <marquee behavior="scroll" direction="left" scrollamount="7" onmouseover="this.stop();" onmouseout="this.start();">
                <div style="display: inline-flex; gap: 25px; padding: 15px 10px;">
                    @foreach($barangDijual as $item)
                        @php
                            // Harga Jual = Uang Pinjaman (Harga Gadai) x 150%
                            $hargaJual = $item->uang_pinjaman * 1.5;
                        @endphp
                        <a href="https://wa.me/6285100653370?text=Halo%20UD%20Gerlian%20Jaya,%20saya%20tertarik%20dengan%20barang%20lelang%20{{ urlencode($item->barang->nama_barang) }}%20({{ $item->kode_transaksi }})" target="_blank" style="display: block; width: 280px; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); text-decoration: none; color: inherit; transition: transform 0.3s; border: 1px solid #f1f5f9; cursor: pointer;">
                            <div style="height: 190px; background-color: #f8fafc; position: relative;">
                                @if($item->barang->foto_barang)
                                    @php
                                        $fotoBarang = preg_match('/^https?:\/\//', $item->barang->foto_barang)
                                            ? $item->barang->foto_barang
                                            : asset('storage/' . ltrim($item->barang->foto_barang, '/'));
                                    @endphp
                                    <img src="{{ $fotoBarang }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e2e8f0, #cbd5e1);">
                                        <i class="bi bi-box-seam" style="font-size: 60px; color: #94a3b8;"></i>
                                    </div>
                                @endif
                                <div style="position: absolute; bottom: 15px; right: 15px; background: var(--accent-gold); color: var(--primary-dark); padding: 6px 15px; border-radius: 50px; font-weight: 800; font-size: 13px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    <i class="bi bi-whatsapp"></i> Chat
                                </div>
                            </div>
                            <div style="padding: 20px; text-align: left;">
                                <div style="font-weight: 700; font-size: 17px; color: var(--primary-dark); margin-bottom: 5px; white-space: normal; line-height: 1.3; height: 44px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $item->barang->nama_barang }}</div>
                                <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 15px;">Kode: {{ $item->kode_transaksi }}</div>
                                <div style="color: var(--accent-blue); font-size: 20px; font-weight: 800;">Rp {{ number_format($hargaJual, 0, ',', '.') }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </marquee>
        </div>
    </section>
    @endif

    <!-- Tracker / Cek Status Section -->
    <section class="tracker-section" id="cek-status">
        <div class="tracker-container">
            <h2>Lacak Status Barang Gadai Anda</h2>
            <p>Ketikkan Kode Transaksi Anda (contoh: GD-0001) untuk memantau tanggal jatuh tempo secara transparan.</p>
            
            <form action="{{ route('cek_status') }}#hasil" method="POST" class="search-box">
                @csrf
                <input type="text" name="kode_transaksi" class="search-input" placeholder="Masukkan Kode Transaksi..." value="{{ old('kode_transaksi') }}" required>
                <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i> Lacak Status
                </button>
            </form>

            <div id="hasil">
                @if(session('hasil_pencarian'))
                    @php 
                        $transaksi = session('hasil_pencarian'); 
                        // MENGGUNAKAN data_get AGAR KEBAL ERROR (BAIK ARRAY MAUPUN OBJECT)
                        $status = data_get($transaksi, 'status');
                        $kode = data_get($transaksi, 'kode_transaksi');
                        // Akses relasi barang juga dengan aman
                        $barang = data_get($transaksi, 'barang');
                        $nama_barang = data_get($barang, 'nama_barang', '-');
                        $id_barang = data_get($barang, 'id_barang');
                        
                        $tgl_gadai = data_get($transaksi, 'tanggal_gadai');
                        $tgl_jatuh_tempo = data_get($transaksi, 'tanggal_jatuh_tempo');
                        $uang_pinjaman = data_get($transaksi, 'uang_pinjaman', 0);
                        $tgl_ditebus = data_get($transaksi, 'tanggal_ditebus');
                    @endphp
                    
                    <div class="result-card">
                        <div class="result-header">
                            <h3>Hasil Pelacakan</h3>
                            <span class="status-badge status-{{ strtolower($status) }}">
                                {{ $status }}
                            </span>
                        </div>
                        
                        <div class="result-grid">
                            <div class="result-item">
                                <span class="result-label">Kode Transaksi</span>
                                <span class="result-value">{{ $kode }}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Nama Barang</span>
                                <span class="result-value">{{ $nama_barang }}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Tanggal Mulai Gadai</span>
                                <span class="result-value">{{ $tgl_gadai ? \Carbon\Carbon::parse($tgl_gadai)->translatedFormat('d F Y') : '-' }}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Batas Jatuh Tempo</span>
                                <span class="result-value text-danger" style="color: #dc2626;">{{ $tgl_jatuh_tempo ? \Carbon\Carbon::parse($tgl_jatuh_tempo)->translatedFormat('d F Y') : '-' }}</span>
                            </div>
                            <div class="result-item">
                                <span class="result-label">Nilai Pinjaman (Taksiran)</span>
                                <span class="result-value">Rp {{ number_format($uang_pinjaman, 0, ',', '.') }}</span>
                            </div>
                            
                            @if(strtolower($status) == 'ditebus' && $tgl_ditebus)
                            <div class="result-item">
                                <span class="result-label">Tanggal Pelunasan (Ditebus)</span>
                                <span class="result-value" style="color: #166534;">{{ \Carbon\Carbon::parse($tgl_ditebus)->translatedFormat('d F Y') }}</span>
                            </div>
                            @endif
                        </div>

                        @if(strtolower($status) == 'aktif' && $id_barang)
                        <div style="margin-top: 40px; text-align: center; border-top: 1px dashed #e2e8f0; padding-top: 30px;">
                            <p style="margin-bottom: 15px; color: var(--text-muted); font-size: 14px;">Untuk melihat rincian perjanjian gadai secara lengkap:</p>
                            <a href="{{ route('public.surat_gadai', $id_barang) }}" class="btn-primary" style="background-color: var(--primary-blue); color: white;">
                                <i class="bi bi-file-earmark-pdf"></i> Buka Surat Gadai Digital
                            </a>
                        </div>
                        @endif
                    </div>
                @endif

                @if(session('error_pencarian'))
                    <div class="alert-error">
                        <i class="bi bi-exclamation-triangle"></i>
                        <p>{{ session('error_pencarian') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div style="font-size: 20px; font-weight: 800; margin-bottom: 10px; display: flex; justify-content: center; align-items: center; gap: 10px;">
            <i class="bi bi-hexagon-fill"></i> UD GERLIAN JAYA
        </div>
        <p>&copy; {{ date('Y') }} Sistem Informasi Gadai Terpadu. Seluruh hak cipta dilindungi.</p>
    </footer>

</body>
</html>
