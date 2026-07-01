@extends('layouts.pelanggan')
@section('page_title', 'Detail Riwayat')

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        max-width: 600px;
        margin: 0 auto 24px;
        overflow: hidden;
    }
    .dc-header {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafafa;
        text-align: center;
    }
    .dc-body {
        padding: 24px;
    }
    .dc-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .dc-row:last-child {
        border-bottom: none;
    }
    .dc-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }
    .dc-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
        text-align: right;
        max-width: 60%;
    }
    .qr-container {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        display: inline-block;
        border: 2px dashed #cbd5e1;
        margin: 20px 0;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 15px;
    }
    .btn-back:hover {
        color: #0f172a;
    }
</style>
@endpush

@section('content')

@php
    $transaksi = $barang->transaksiGadai->first();
    $status = $transaksi ? $transaksi->status : $barang->status_verifikasi;
    $fotoBarang = $barang->foto_barang ? (preg_match('/^https?:\/\//', $barang->foto_barang) ? $barang->foto_barang : asset('storage/' . ltrim($barang->foto_barang, '/'))) : null;
@endphp

<div style="max-width: 600px; margin: 0 auto;">
    <a href="{{ route('pelanggan.riwayat.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    <div class="detail-card">
        <div class="dc-header">
            @if(in_array($status, ['terverifikasi', 'aktif']))
                <h3 style="margin: 0 0 8px 0; color: #0f172a;">Kode QR Anda</h3>
                <p style="margin: 0; color: #64748b; font-size: 13px;">Tunjukkan QR Code ini kepada petugas kami di loket untuk mempercepat proses pelayanan.</p>
                
                <div class="qr-container">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate(route('public.surat_gadai', $barang->id_barang)) !!}
                </div>
                <div style="font-size: 16px; font-weight: 800; color: #334155; letter-spacing: 1px;">
                    {{ $transaksi ? $transaksi->kode_transaksi : 'BARANG-'.$barang->id_barang }}
                </div>
            @else
                <h3 style="margin: 0; color: #0f172a;">Detail Pengajuan</h3>
            @endif
        </div>

        <div class="dc-body">
            @if($fotoBarang)
                <div style="text-align: center; margin-bottom: 24px;">
                    <img src="{{ $fotoBarang }}" style="max-width: 100%; max-height: 250px; border-radius: 12px; border: 1px solid #e2e8f0; object-fit: contain;">
                </div>
            @endif

            <div class="dc-row">
                <span class="dc-label">Nama Barang</span>
                <span class="dc-value">{{ $barang->nama_barang }}</span>
            </div>
            <div class="dc-row">
                <span class="dc-label">Kategori</span>
                <span class="dc-value">{{ $barang->jenisBarang->nama_jenis ?? '-' }}</span>
            </div>
            <div class="dc-row">
                <span class="dc-label">Tanggal Pengajuan</span>
                <span class="dc-value">{{ \Carbon\Carbon::parse($barang->created_at)->translatedFormat('d F Y') }}</span>
            </div>
            
            @if($transaksi)
                <div class="dc-row">
                    <span class="dc-label">Uang Pinjaman</span>
                    <span class="dc-value" style="color: #1d4ed8;">Rp {{ number_format($transaksi->uang_pinjaman, 0, ',', '.') }}</span>
                </div>
                <div class="dc-row">
                    <span class="dc-label">Tanggal Gadai</span>
                    <span class="dc-value">{{ \Carbon\Carbon::parse($transaksi->tanggal_gadai)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="dc-row">
                    <span class="dc-label">Jatuh Tempo</span>
                    <span class="dc-value" style="color: #ef4444;">{{ \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</span>
                </div>
                @if($transaksi->status == 'aktif')
                    @php
                        $now = now()->startOfDay();
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
                    <div class="dc-row" style="background: #ecfdf5; border-radius: 8px; margin: 10px 0;">
                        <span class="dc-label" style="color: #065f46;">Est. Biaya Penebusan</span>
                        <span class="dc-value" style="color: #10b981; font-size: 18px;">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="dc-row">
                    <span class="dc-label">Status Terkini</span>
                    <span class="dc-value">{{ ucfirst($transaksi->status) }}</span>
                </div>
            @else
                <div class="dc-row">
                    <span class="dc-label">Est. Harga Beli</span>
                    <span class="dc-value">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</span>
                </div>
                <div class="dc-row">
                    <span class="dc-label">Kondisi</span>
                    <span class="dc-value">{{ $barang->kondisi }}</span>
                </div>
                @if($status == 'terverifikasi' && $barang->harga_gadai_sementara)
                    <div class="dc-row">
                        <span class="dc-label" style="color: #1d4ed8;">Penawaran Gadai Admin</span>
                        <span class="dc-value" style="color: #1d4ed8;">Rp {{ number_format($barang->harga_gadai_sementara, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="dc-row">
                    <span class="dc-label">Status Verifikasi</span>
                    <span class="dc-value">{{ ucfirst($barang->status_verifikasi) }}</span>
                </div>
            @endif

            <div class="dc-row" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                <span class="dc-label">Keterangan Tambahan:</span>
                <span style="color: #334155; font-size: 14px; background: #f8fafc; padding: 12px; border-radius: 8px; width: 100%; display: block; box-sizing: border-box;">
                    {{ $barang->keterangan ?: 'Tidak ada keterangan tambahan' }}
                </span>
            </div>
        </div>
    </div>
</div>

@endsection
