@extends('layouts.pelanggan')
@section('page_title', 'Status & Riwayat Gadai')

@push('styles')
<style>
    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }
    .filter-tab {
        flex: 1 1 auto;
        text-align: center;
        padding: 8px 12px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        text-decoration: none;
        transition: all 0.2s;
    }
    .filter-tab.active {
        background: #3b82f6;
        color: white;
        box-shadow: 0 2px 4px rgba(59,130,246,0.3);
    }
    
    .history-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 16px;
        overflow: hidden;
    }
    .hc-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
    }
    .hc-body {
        padding: 20px;
        display: flex;
        gap: 20px;
    }
    .hc-image {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .hc-info {
        flex-grow: 1;
    }
    .hc-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }
    .hc-subtitle {
        font-size: 13px;
        color: #64748b;
        margin: 0 0 12px 0;
    }
    .hc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
    }
    .hc-grid-item {
        background: #f8fafc;
        padding: 10px;
        border-radius: 8px;
    }
    .hc-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
        display: block;
    }
    .hc-value {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }
    .hc-footer {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }
    .hc-footer-text {
        font-size: 13px;
        line-height: 1.5;
        flex: 1;
    }
    .hc-footer-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }
    
    @media (max-width: 640px) {
        .hc-body {
            flex-direction: column;
            gap: 15px;
        }
        .hc-image {
            width: 100%;
            height: 180px;
        }
        .hc-footer {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .hc-footer-actions {
            flex-direction: column;
            width: 100%;
        }
        .hc-footer-actions form {
            width: 100%;
        }
        .hc-footer-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    .btn {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary { background: #3b82f6; color: white; }
    .btn-primary:hover { background: #2563eb; }
    .btn-outline { background: transparent; border: 1px solid #cbd5e1; color: #475569; }
    .btn-outline:hover { background: #f8fafc; border-color: #94a3b8; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-terverifikasi { background: #dbeafe; color: #1d4ed8; }
    .badge-ditolak { background: #fee2e2; color: #b91c1c; }
    .badge-aktif { background: #e0f2fe; color: #0369a1; }
    .badge-ditebus { background: #dcfce7; color: #15803d; }
    .badge-dijual { background: #ffe4e6; color: #be123c; }
</style>
@endpush

@section('content')

<div class="filter-tabs">
    <a href="{{ route('pelanggan.riwayat.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
    <a href="{{ route('pelanggan.riwayat.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">Sedang Ditinjau</a>
    <a href="{{ route('pelanggan.riwayat.index', ['status' => 'aktif']) }}" class="filter-tab {{ request('status') == 'aktif' ? 'active' : '' }}">Gadai Aktif</a>
    <a href="{{ route('pelanggan.riwayat.index', ['status' => 'ditebus']) }}" class="filter-tab {{ request('status') == 'ditebus' ? 'active' : '' }}">Selesai (Ditebus)</a>
    <a href="{{ route('pelanggan.riwayat.index', ['status' => 'dijual']) }}" class="filter-tab {{ request('status') == 'dijual' ? 'active' : '' }}">Dilelang (Hangus)</a>
</div>

@forelse($barangList as $barang)
    @php
        $transaksi = $barang->transaksiGadai->first();
        
        // Tentukan Status Final
        if ($transaksi) {
            $status = $transaksi->status; // aktif, ditebus, dijual
        } else {
            $status = $barang->status_verifikasi; // pending, terverifikasi, ditolak
        }

        // Setup Tampilan Badge
        $badgeClass = '';
        $badgeText = '';
        if ($status == 'pending') { $badgeClass = 'badge-pending'; $badgeText = 'Sedang Ditinjau'; }
        elseif ($status == 'terverifikasi') { $badgeClass = 'badge-terverifikasi'; $badgeText = 'Ke Toko Sekarang'; }
        elseif ($status == 'ditolak') { $badgeClass = 'badge-ditolak'; $badgeText = 'Ditolak'; }
        elseif ($status == 'aktif') { $badgeClass = 'badge-aktif'; $badgeText = 'Gadai Aktif'; }
        elseif ($status == 'ditebus') { $badgeClass = 'badge-ditebus'; $badgeText = 'Telah Ditebus'; }
        elseif ($status == 'dijual') { $badgeClass = 'badge-dijual'; $badgeText = 'Hangus / Dilelang'; }

        // Setup Foto
        $fotoBarang = $barang->foto_barang ? (preg_match('/^https?:\/\//', $barang->foto_barang) ? $barang->foto_barang : asset('storage/' . ltrim($barang->foto_barang, '/'))) : null;
    @endphp

    <div class="history-card">
        <div class="hc-header">
            <div style="font-size: 13px; font-weight: 600; color: #64748b;">
                <i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($barang->created_at)->translatedFormat('d M Y') }}
                @if($transaksi)
                    <span style="margin: 0 8px; color: #cbd5e1;">|</span>
                    <i class="bi bi-hash"></i> {{ $transaksi->kode_transaksi }}
                @endif
            </div>
            <span class="badge {{ $badgeClass }}">
                {{ $badgeText }}
            </span>
        </div>
        
        <div class="hc-body">
            @if($fotoBarang)
                <img src="{{ $fotoBarang }}" alt="{{ $barang->nama_barang }}" class="hc-image">
            @else
                <div class="hc-image" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <i class="bi bi-image" style="font-size: 32px;"></i>
                </div>
            @endif
            
            <div class="hc-info">
                <h3 class="hc-title">{{ $barang->nama_barang }}</h3>
                <p class="hc-subtitle">{{ $barang->jenisBarang->nama_jenis ?? '-' }}</p>
                
                <div class="hc-grid">
                    @if($transaksi)
                        <div class="hc-grid-item">
                            <span class="hc-label">Uang Pinjaman</span>
                            <span class="hc-value" style="color:#0f172a;">Rp {{ number_format($transaksi->uang_pinjaman, 0, ',', '.') }}</span>
                        </div>
                        <div class="hc-grid-item">
                            <span class="hc-label">Jatuh Tempo</span>
                            @php
                                $jt = \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo);
                                $now = now()->startOfDay();
                                $jtStart = $jt->copy()->startOfDay();
                                $diff = $now->diffInDays($jtStart, false);
                                
                                $jtColor = '#334155';
                                if($status == 'aktif') {
                                    if($diff < 0) $jtColor = '#ef4444';
                                    elseif($diff <= 7) $jtColor = '#f97316';
                                }
                            @endphp
                            <span class="hc-value" style="color: {{ $jtColor }};">{{ $jt->translatedFormat('d M Y') }}</span>
                        </div>
                        @if($status == 'aktif')
                            @php
                                $tglGadai = \Carbon\Carbon::parse($transaksi->tanggal_gadai)->startOfDay();
                                $hariBerjalan = $tglGadai->diffInDays($now);
                                
                                $totalBunga = 0;
                                if($hariBerjalan <= 15) {
                                    $totalBunga = round($transaksi->uang_pinjaman * 0.05);
                                } else {
                                    $monthsDiff = $tglGadai->diffInMonths($now);
                                    if($now->copy()->startOfDay()->day < $tglGadai->day) {
                                        $monthsDiff--;
                                    }
                                    $finalMonths = max(1, $monthsDiff);
                                    $totalBunga = round($transaksi->uang_pinjaman * 0.075 * $finalMonths);
                                }
                                $totalBayar = $transaksi->uang_pinjaman + $totalBunga;
                            @endphp
                            <div class="hc-grid-item">
                                <span class="hc-label">Est. Biaya Penebusan</span>
                                <span class="hc-value" style="color: #10b981; font-weight: 700;">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="hc-grid-item">
                                <span class="hc-label">Sisa Waktu</span>
                                @if($diff < 0)
                                    <span class="hc-value" style="color: #ef4444;">Lewat {{ abs($diff) }} Hari</span>
                                @elseif($diff == 0)
                                    <span class="hc-value" style="color: #f97316;">Hari Ini!</span>
                                @elseif($diff <= 7)
                                    <span class="hc-value" style="color: #eab308;">Sisa {{ $diff }} Hari</span>
                                @else
                                    <span class="hc-value" style="color: #10b981;">Sisa {{ $diff }} Hari</span>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="hc-grid-item">
                            <span class="hc-label">Est. Harga Beli</span>
                            <span class="hc-value">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</span>
                        </div>
                        <div class="hc-grid-item">
                            <span class="hc-label">Kondisi</span>
                            <span class="hc-value">{{ $barang->kondisi }}</span>
                        </div>
                        @if($status == 'terverifikasi' && $barang->harga_gadai_sementara)
                            <div class="hc-grid-item">
                                <span class="hc-label" style="color: #1d4ed8;">Penawaran Gadai</span>
                                <span class="hc-value" style="color: #1d4ed8;">Rp {{ number_format($barang->harga_gadai_sementara, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Area Arahan & Aksi --}}
        <div class="hc-footer" style="background: {{ in_array($status, ['pending', 'terverifikasi']) ? '#eff6ff' : (in_array($status, ['aktif', 'ditebus']) ? '#f8fafc' : '#fef2f2') }};">
            {{-- Pesan Arahan --}}
            <div class="hc-footer-text" style="color: {{ in_array($status, ['pending', 'terverifikasi']) ? '#1d4ed8' : (in_array($status, ['aktif', 'ditebus']) ? '#334155' : '#b91c1c') }};">
                @if($status == 'pending')
                    <i class="bi bi-hourglass-split"></i> Pengajuan Anda sedang ditinjau oleh tim Admin. Harap bersabar menunggu hasil verifikasi.
                @elseif($status == 'terverifikasi')
                    <i class="bi bi-info-circle-fill"></i> <strong>Pengajuan Disetujui!</strong> Silakan datang ke kantor pegadaian membawa barang fisik. Tunjukkan QR Code pada halaman Detail ke Admin.
                @elseif($status == 'ditolak')
                    <i class="bi bi-x-circle-fill"></i> Mohon maaf, pengajuan barang ini tidak dapat kami proses saat ini.
                @elseif($status == 'aktif')
                    <i class="bi bi-check-circle-fill"></i> Barang sedang dalam masa gadai aktif. Perhatikan sisa waktu Anda agar barang tidak dilelang.
                @elseif($status == 'ditebus')
                    <i class="bi bi-box-seam"></i> Transaksi selesai. Barang Anda telah berhasil diambil kembali.
                @elseif($status == 'dijual')
                    <i class="bi bi-exclamation-triangle-fill"></i> Masa tenggang telah habis dan barang telah beralih status (dilelang).
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="hc-footer-actions">
                @if(in_array($status, ['pending', 'ditolak', 'terverifikasi']))
                    <form action="{{ route('pelanggan.riwayat.destroy', $barang->id_barang) }}" method="POST" style="margin: 0;" onsubmit="return confirm('{{ $status == 'terverifikasi' ? 'Apakah Anda yakin ingin menolak penawaran ini? Barang yang ditolak akan otomatis dihapus dari histori Anda.' : 'Yakin ingin menghapus riwayat ini?' }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: transparent; border: 1px solid #fca5a5; color: #ef4444;">
                            <i class="bi {{ $status == 'terverifikasi' ? 'bi-x-circle' : 'bi-trash' }}"></i> {{ $status == 'terverifikasi' ? 'Tolak Penawaran' : 'Hapus' }}
                        </button>
                    </form>
                @endif

                @if($status == 'aktif')
                    <a href="#" class="btn btn-primary" onclick="alert('Fitur Perpanjangan sedang dalam pengembangan'); return false;">
                        <i class="bi bi-calendar-plus"></i> Perpanjang
                    </a>
                    <a href="#" class="btn" style="background: #10b981; color: white; border: none;" onclick="alert('Fitur Penebusan sedang dalam pengembangan'); return false;">
                        <i class="bi bi-box-arrow-down"></i> Tebus
                    </a>
                @endif

                <a href="{{ route('pelanggan.riwayat.show', $barang->id_barang) }}" class="btn btn-outline" style="background: white;">
                    <i class="bi bi-eye"></i> Detail
                </a>
            </div>
        </div>
    </div>
@empty
    <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px solid #f1f5f9;">
        <i class="bi bi-folder-x" style="font-size: 64px; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
        <h3 style="margin: 0 0 8px 0; color: #334155; font-size: 18px;">Belum Ada Riwayat</h3>
        <p style="margin: 0 0 24px 0; color: #64748b; font-size: 14px;">Anda belum memiliki riwayat pengajuan gadai dalam kategori ini.</p>
        <a href="{{ route('pelanggan.pengajuan_gadai.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Ajukan Gadai Sekarang
        </a>
    </div>
@endforelse

@if($barangList->hasPages())
    <div style="margin-top: 20px;">
        {{ $barangList->links() }}
    </div>
@endif

@endsection
