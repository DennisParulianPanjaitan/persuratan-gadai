@extends('layouts.pelanggan')
@section('page_title', 'Dashboard')

@section('content')

@if(!$pelanggan)
    <div class="alert alert-warning" style="background: #fffbeb; border: 1px solid #fde68a; color: #b45309; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
        <i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i>
        Data profil pelanggan Anda belum lengkap atau belum terhubung dengan akun ini. Silakan hubungi admin.
    </div>
@else
    
    {{-- Notifikasi Jatuh Tempo --}}
    @php
        $jatuhTempoItems = $transaksiGadai->filter(function($t) {
            if($t->status !== 'aktif') return false;
            $now = now()->startOfDay();
            $jtDate = \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->startOfDay();
            $diff = $now->diffInDays($jtDate, false);
            return $diff <= 7;
        });
    @endphp

    @if($jatuhTempoItems->count() > 0)
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <i class="bi bi-bell-fill" style="color: #ef4444; font-size: 24px; margin-top: 2px;"></i>
            <div>
                <h4 style="margin: 0 0 5px 0; color: #991b1b; font-size: 15px; font-weight: 700;">Perhatian: Ada Barang Mendekati Jatuh Tempo!</h4>
                <p style="margin: 0; color: #b91c1c; font-size: 14px;">Anda memiliki <strong>{{ $jatuhTempoItems->count() }} barang</strong> yang hampir atau sudah melewati batas waktu tebus. Segera lakukan pelunasan atau perpanjangan agar barang Anda tidak dilelang.</p>
            </div>
        </div>
    @endif

    {{-- Widget Statistik --}}
    <div class="dashboard-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px; border: 1px solid #f1f5f9;">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="stat-details">
                <p style="margin: 0 0 4px 0; color: #64748b; font-size: 13px; font-weight: 600; text-transform: uppercase;">Gadai Aktif</p>
                <h3 style="margin: 0; color: #0f172a; font-size: 24px; font-weight: 800;">{{ $totalBarangAktif }} <span style="font-size: 14px; color: #94a3b8; font-weight: 500;">Barang</span></h3>
            </div>
        </div>

        <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px; border: 1px solid #f1f5f9;">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-details">
                <p style="margin: 0 0 4px 0; color: #64748b; font-size: 13px; font-weight: 600; text-transform: uppercase;">Total Pinjaman</p>
                <h3 style="margin: 0; color: #0f172a; font-size: 24px; font-weight: 800;">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</h3>
            </div>
        </div>

    </div>

    {{-- Tabel Pengajuan Terakhir --}}
    <div class="card" style="background: #fff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; overflow: hidden;">
        <div class="card-header" style="background: #fff; padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Status Pengajuan Terakhir</h2>
            <a href="{{ route('pelanggan.riwayat.index') }}" style="font-size: 13px; color: #3b82f6; text-decoration: none; font-weight: 600;">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0; width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; color: #64748b; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 12px 24px; font-weight: 600;">Kode</th>
                            <th style="padding: 12px 24px; font-weight: 600;">Nama Barang</th>
                            <th style="padding: 12px 24px; font-weight: 600;">Tgl Pengajuan</th>
                            <th style="padding: 12px 24px; font-weight: 600;">Jatuh Tempo</th>
                            <th style="padding: 12px 24px; font-weight: 600;">Pinjaman</th>
                            <th style="padding: 12px 24px; font-weight: 600;">Sisa Waktu</th>
                            <th style="padding: 12px 24px; font-weight: 600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiGadai->take(5) as $index => $transaksi)
                            <tr style="border-top: 1px solid #f1f5f9;">
                                <td style="padding: 16px 24px; color: #334155; font-weight: 600; font-size: 14px;">{{ $transaksi->kode_transaksi }}</td>
                                <td style="padding: 16px 24px; color: #0f172a; font-weight: 500; font-size: 14px;">{{ $transaksi->barang->nama_barang ?? '-' }}</td>
                                <td style="padding: 16px 24px; color: #64748b; font-size: 14px;">{{ \Carbon\Carbon::parse($transaksi->tanggal_gadai)->translatedFormat('d M Y') }}</td>
                                <td style="padding: 16px 24px; font-size: 14px;">
                                    @php
                                        $jatuhTempo = \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo);
                                        $isWarning = $jatuhTempo->isPast() || $jatuhTempo->diffInDays(now()) <= 7;
                                    @endphp
                                    @if($transaksi->status === 'aktif')
                                        <span style="color: {{ $isWarning ? '#ef4444' : '#64748b' }}; font-weight: {{ $isWarning ? '700' : '400' }};">
                                            {{ $jatuhTempo->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 24px; color: #0f172a; font-weight: 600; font-size: 14px;">Rp {{ number_format($transaksi->uang_pinjaman, 0, ',', '.') }}</td>
                                <td style="padding: 16px 24px; font-size: 13px; font-weight: 700;">
                                    @if($transaksi->status === 'aktif')
                                        @php
                                            $now = now()->startOfDay();
                                            $jtDate = \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->startOfDay();
                                            $diff = $now->diffInDays($jtDate, false);
                                        @endphp
                                        @if($diff < 0)
                                            <span style="background: #fef2f2; color: #ef4444; padding: 4px 8px; border-radius: 6px;">Lewat {{ abs($diff) }} Hari</span>
                                        @elseif($diff == 0)
                                            <span style="background: #fff7ed; color: #f97316; padding: 4px 8px; border-radius: 6px;">Hari Ini!</span>
                                        @elseif($diff <= 7)
                                            <span style="background: #fefce8; color: #eab308; padding: 4px 8px; border-radius: 6px;">Sisa {{ $diff }} Hari</span>
                                        @else
                                            <span style="background: #f0fdf4; color: #10b981; padding: 4px 8px; border-radius: 6px;">Sisa {{ $diff }} Hari</span>
                                        @endif
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 24px;">
                                    @if($transaksi->status == 'aktif')
                                        <span style="background: #e0f2fe; color: #0284c7; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 700;">Aktif</span>
                                    @elseif($transaksi->status == 'ditebus')
                                        <span style="background: #dcfce7; color: #16a34a; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 700;">Ditebus</span>
                                    @elseif($transaksi->status == 'dijual')
                                        <span style="background: #fee2e2; color: #dc2626; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 700;">Dilelang</span>
                                    @else
                                        <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 700;">{{ ucfirst($transaksi->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px; text-align: center; color: #94a3b8; font-size: 14px;">
                                    <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px; color: #cbd5e1;"></i>
                                    Belum ada riwayat pengajuan gadai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection
