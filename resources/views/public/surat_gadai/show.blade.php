<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Bukti Gadai - UD. GERLIAN JAYA</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --viewer-bg: #525659;
            --toolbar-bg: #323639;
            --paper-color: #fdfdfc;
            --text-color: #111827;
            --border-color: #374151;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--viewer-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* PDF Viewer Toolbar */
        .pdf-toolbar {
            width: 100%;
            background-color: var(--toolbar-bg);
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            box-sizing: border-box;
            box-shadow: 0 1px 3px rgba(0,0,0,0.5);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .pdf-title {
            color: white;
            font-size: 14px;
            font-weight: 500;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            max-width: 60%;
        }

        .pdf-actions {
            display: flex;
            gap: 12px;
        }

        .pdf-btn {
            background: none;
            border: none;
            color: #f1f1f1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .pdf-btn:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .pdf-btn svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* Paper container */
        .paper-wrapper {
            padding: 30px 15px 50px;
            width: 100%;
            display: flex;
            justify-content: center;
            box-sizing: border-box;
        }

        .paper {
            background-color: var(--paper-color);
            width: 100%;
            max-width: 480px; /* Slightly wider to look like a real document */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
            border-top: 40px solid #fcecae;
        }

        .content {
            padding: 30px 25px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .header p {
            margin: 2px 0 0;
            font-size: 12px;
            font-weight: 500;
        }

        .no-surat {
            position: absolute;
            top: 25px;
            right: 0;
            color: #dc2626;
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .no-surat span {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: var(--text-color);
        }

        .field-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 24px;
        }

        .field-label {
            width: 130px;
            flex-shrink: 0;
            font-weight: 500;
        }

        .field-separator {
            width: 20px;
            flex-shrink: 0;
        }

        .field-value {
            flex-grow: 1;
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            color: #1f2937;
            /* Dotted line that repeats for every line of text */
            background-image: radial-gradient(circle at 3px 23px, #9ca3af 1px, transparent 1px);
            background-size: 6px 24px;
        }

        .jatuh-tempo-box {
            border: 2px solid var(--border-color);
            padding: 10px 15px;
            margin: 25px 0;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
        }

        .jatuh-tempo-box span {
            margin-left: 15px;
            font-family: 'Courier Prime', monospace;
            font-size: 16px;
        }

        .status-container {
            margin: 30px 0;
            display: flex;
            flex-direction: column;
        }

        .status-title {
            text-align: left;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .status-badge {
            align-self: flex-start;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 18px;
            border: 2px dashed;
            transform: rotate(-3deg);
        }

        .status-badge.menunggu {
            color: #d97706;
            border-color: #fcd34d;
            background-color: #fef3c7;
        }

        .status-badge.ditolak {
            color: #dc2626;
            border-color: #fca5a5;
            background-color: #fee2e2;
        }

        .status-badge.disetujui-belum-gadai {
            color: #2563eb;
            border-color: #93c5fd;
            background-color: #dbeafe;
        }

        .status-badge.gadai-aktif {
            color: #059669;
            border-color: #6ee7b7;
            background-color: #d1fae5;
        }

        .status-badge.ditebus {
            color: #4f46e5;
            border-color: #a5b4fc;
            background-color: #e0e7ff;
        }

        .footer-divider {
            border-top: 2px solid var(--border-color);
            margin: 40px 0 15px;
        }

        .perhatian {
            font-size: 11px;
            line-height: 1.5;
        }

        .perhatian h4 {
            margin: 0 0 5px;
            font-size: 12px;
        }

        .perhatian ol {
            margin: 0;
            padding-left: 15px;
        }
        
        .perhatian li {
            margin-bottom: 3px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .pdf-toolbar {
                display: none;
            }
            .paper-wrapper {
                padding: 0;
            }
            .paper {
                box-shadow: none;
                max-width: 100%;
                border-top: 40px solid #fcecae !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- PDF-like Toolbar -->
    <div class="pdf-toolbar">
        <div class="pdf-title">Surat_Gadai_UD_Gerlian_{{ $transaksi ? $transaksi->kode_transaksi : 'Baru' }}.pdf</div>
        <div class="pdf-actions">
            <!-- Print Button -->
            <button class="pdf-btn" onclick="window.print()" title="Cetak / Simpan PDF">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 8h-1V3H6v5H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zM8 5h8v3H8V5zm8 14H8v-5h8v5zm2-4v-2H6v2H4v-4c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v4h-2z"/><circle cx="18" cy="11.5" r="1"/></svg>
            </button>
        </div>
    </div>

    <!-- Document wrapper -->
    <div class="paper-wrapper">
        <div class="paper">
            <div class="content">
            <div class="header">
                <h1>UD. GERLIAN JAYA</h1>
                <h2>( YAPUSA )</h2>
                <div class="no-surat">
                    <span>No.</span> 
                    @if($transaksi)
                        {{ $transaksi->kode_transaksi }}
                    @else
                        -
                    @endif
                </div>
                <p>Jl. Anggrek No. 2<br>PESANGGRAHAN - BATU</p>
            </div>

            @php
                // Tentukan nama pelanggan
                $namaPelanggan = '-';
                if ($transaksi && $transaksi->pelanggan) {
                    $namaPelanggan = $transaksi->pelanggan->nama;
                } elseif ($barang->pelanggan) {
                    $namaPelanggan = $barang->pelanggan->nama;
                }

                // Tentukan alamat
                $alamatPelanggan = '-';
                if ($transaksi && $transaksi->pelanggan) {
                    $alamatPelanggan = $transaksi->pelanggan->alamat;
                } elseif ($barang->pelanggan) {
                    $alamatPelanggan = $barang->pelanggan->alamat;
                }

                // Tentukan Tanggal Masuk
                $tanggalMasuk = '-';
                if ($transaksi) {
                    $tanggalMasuk = \Carbon\Carbon::parse($transaksi->tanggal_gadai)->format('d/m/Y');
                } elseif ($barang->created_at) {
                    $tanggalMasuk = $barang->created_at->format('d/m/Y');
                }

                // Harga
                $harga = '-';
                if ($transaksi) {
                    $harga = number_format($transaksi->uang_pinjaman, 0, ',', '.');
                } elseif ($barang->harga_gadai_sementara) {
                    $harga = number_format($barang->harga_gadai_sementara, 0, ',', '.');
                }
            @endphp

            <div class="field-group">
                <div class="field-label">Tanggal Masuk</div>
                <div class="field-separator">:</div>
                <div class="field-value">{{ $tanggalMasuk }}</div>
            </div>

            <div class="field-group">
                <div class="field-label">Nama</div>
                <div class="field-separator">:</div>
                <div class="field-value">{{ $namaPelanggan }}</div>
            </div>

            <div class="field-group">
                <div class="field-label">Alamat</div>
                <div class="field-separator">:</div>
                <div class="field-value" style="font-size:12px; line-height:22px;">{{ $alamatPelanggan }}</div>
            </div>

            <div class="field-group">
                <div class="field-label">Jenis Barang</div>
                <div class="field-separator">:</div>
                <div class="field-value">{{ $barang->jenisBarang->nama_jenis ?? '' }} - {{ $barang->nama_barang }}</div>
            </div>

            <div class="field-group" style="margin-top: 15px;">
                <div class="field-label">Harga</div>
                <div class="field-separator">:</div>
                <div class="field-value">Rp. {{ $harga }}</div>
            </div>

            <div class="jatuh-tempo-box">
                Tgl. Jatuh Tempo : 
                <span>
                    @if($transaksi && $transaksi->tanggal_jatuh_tempo)
                        {{ \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </span>
            </div>

            <div class="status-container">
                <div class="status-title">Status Barang:</div>
                
                @php
                    $statusClass = 'menunggu';
                    $statusText = 'MENUNGGU PERSETUJUAN';

                    if ($transaksi) {
                        if ($barang->status_verifikasi == 'terverifikasi') {
                            if ($transaksi->status == 'aktif') {
                                $isJatuhTempo = \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->startOfDay()->lt(\Carbon\Carbon::today());
                                $hasPenjualan = $transaksi->penjualan()->exists();

                                if ($isJatuhTempo && !$hasPenjualan) {
                                    $statusClass = 'ditolak'; // Warna merah
                                    $statusText = 'JATUH TEMPO';
                                } else {
                                    $statusClass = 'gadai-aktif';
                                    $statusText = 'GADAI AKTIF';
                                }
                            } elseif ($transaksi->status == 'ditebus') {
                                $statusClass = 'ditebus';
                                $statusText = 'TELAH DITEBUS';
                            } elseif ($transaksi->status == 'dijual') {
                                $statusClass = 'ditolak';
                                $statusText = 'DIJUAL / LELANG';
                            }
                        }
                    } else {
                        if ($barang->status_verifikasi == 'ditolak') {
                            $statusClass = 'ditolak';
                            $statusText = 'DITOLAK';
                        } elseif ($barang->status_verifikasi == 'terverifikasi') {
                            $statusClass = 'disetujui-belum-gadai';
                            $statusText = 'DISETUJUI (Proses)';
                        }
                    }
                @endphp

                <div class="status-badge {{ $statusClass }}">
                    {{ $statusText }}
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="perhatian">
                <h4>Perhatian :</h4>
                <ol>
                    <li>Barang milik sendiri bukan dari hasil pidana</li>
                    <li>Apabila Barang tidak diambil atau diperpanjang pada saat jatuh tempo akan dijual</li>
                    <li>Surat hilang, barang hanya bisa dibeli kembali setelah jatuh tempo</li>
                </ol>
            </div>
        </div>
    </div>
    </div>

</body>
</html>
