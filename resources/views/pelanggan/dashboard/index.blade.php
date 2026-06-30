@extends('layouts.pelanggan')
@section('page_title', 'Dashboard Pelanggan')

@section('content')

@if(!$pelanggan)
    <div class="alert alert-warning">
        Data profil pelanggan Anda belum lengkap atau belum terhubung dengan akun ini. Silakan hubungi admin.
    </div>
@else
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="bi bi-box"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $totalBarangAktif }}</h3>
                <p>Barang Gadai Aktif</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-details">
                <h3>Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</h3>
                <p>Total Pinjaman Aktif</p>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Riwayat Gadai Anda</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Barang</th>
                            <th>Tanggal Gadai</th>
                            <th>Jatuh Tempo</th>
                            <th>Pinjaman</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiGadai as $index => $transaksi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $transaksi->kode_transaksi }}</strong></td>
                                <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_gadai)->translatedFormat('d M Y') }}</td>
                                <td>
                                    @php
                                        $jatuhTempo = \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo);
                                        $isWarning = $jatuhTempo->isPast() || $jatuhTempo->diffInDays(now()) <= 7;
                                    @endphp
                                    <span class="{{ $transaksi->status === 'aktif' && $isWarning ? 'text-danger fw-bold' : '' }}">
                                        {{ $jatuhTempo->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($transaksi->uang_pinjaman, 0, ',', '.') }}</td>
                                <td>
                                    @if($transaksi->status == 'aktif')
                                        <span class="badge" style="background: #e0f2fe; color: #0284c7;">Aktif</span>
                                    @elseif($transaksi->status == 'ditebus')
                                        <span class="badge" style="background: #dcfce7; color: #16a34a;">Ditebus</span>
                                    @elseif($transaksi->status == 'dijual')
                                        <span class="badge" style="background: #fee2e2; color: #dc2626;">Dilelang</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($transaksi->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat transaksi gadai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection
