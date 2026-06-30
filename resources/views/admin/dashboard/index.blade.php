@extends('layouts.admin')

@section('content')
<div class="page-shell">
    <x-page-header
        title="Dashboard Admin"
        breadcrumb="Dashboard > Dashboard Admin"
    />

    <!-- ETALASE BARANG LELANG (MARQUEE) -->
    @if($barangDijual->count() > 0)
    <div class="marquee-container" style="background: white; border-radius: 12px; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; overflow: hidden; border-left: 4px solid #f59e0b;">
        <div style="font-weight: 700; color: #b45309; padding-right: 15px; border-right: 2px solid #fef3c7; white-space: nowrap; margin-right: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-tags-fill"></i> Sedang Dijual
        </div>
        <div style="flex: 1; overflow: hidden;">
            <marquee behavior="scroll" direction="left" scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();">
                <div style="display: inline-flex; gap: 30px;">
                    @foreach($barangDijual as $item)
                        <div style="display: flex; align-items: center; gap: 10px; background: #fffbeb; padding: 5px 15px; border-radius: 50px; border: 1px solid #fde68a;">
                            <i class="bi bi-box-seam" style="color: #d97706;"></i>
                            <span style="font-weight: 600; color: #92400e;">{{ $item->barang->nama_barang }}</span>
                            <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                {{ $item->kode_transaksi }}
                            </span>
                            @if($item->barang->harga_gadai_sementara)
                                <span style="color: #b45309; font-size: 14px; font-weight: 600;">(Taksiran: Rp {{ number_format($item->barang->harga_gadai_sementara, 0, ',', '.') }})</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </marquee>
        </div>
    </div>
    @endif

    <!-- STAT CARDS -->
    <div class="stat-grid">
        <!-- Card 1: Total Pelanggan -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Total Pelanggan</div>
                <div class="stat-value">{{ number_format($totalPelanggan, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Card 2: Barang Gadai Aktif -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Barang Gadai Aktif</div>
                <div class="stat-value">{{ number_format($barangGadaiAktif, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Card 3: Total Pinjaman Aktif -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-wallet-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Total Pinjaman Aktif</div>
                <div class="stat-value text-success">Rp {{ number_format($totalPinjamanAktif, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Card 4: Jatuh Tempo -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <div class="stat-title">Mendekati Jatuh Tempo</div>
                <div class="stat-value {{ $mendekatiJatuhTempoCount > 0 ? 'text-danger' : '' }}">{{ number_format($mendekatiJatuhTempoCount, 0, ',', '.') }}</div>
                @if($mendekatiJatuhTempoCount > 0)
                    <div class="stat-subtitle text-danger"><i class="bi bi-arrow-down-short"></i> Perlu Perhatian</div>
                @endif
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD CONTENT -->
    <div class="dashboard-grid">
        
        <!-- LEFT COLUMN -->
        <div class="dashboard-left">
            
            <!-- Barang Mendekati Jatuh Tempo -->
            <div class="dash-card">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon bg-indigo-100 text-indigo-600"><i class="bi bi-hourglass-bottom"></i></div>
                        Barang Mendekati Jatuh Tempo
                    </div>
                    <a href="{{ route('admin.transaksi_gadai.index') }}" class="dash-card-action">Lihat Semua</a>
                </div>
                <div class="dash-card-body p-0">
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Sisa Hari</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangMendekatiJatuhTempo as $item)
                                    @php
                                        $sisaHari = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->tanggal_jatuh_tempo), false);
                                        // false to allow negative numbers if past due
                                    @endphp
                                    <tr>
                                        <td class="font-semibold text-slate-800">{{ $item->pelanggan->nama ?? '-' }}</td>
                                        <td class="text-slate-600">{{ $item->barang->nama_barang ?? '-' }}</td>
                                        <td class="text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->translatedFormat('d M Y') }}</td>
                                        <td class="font-medium text-slate-700">
                                            @if($sisaHari < 0)
                                                Lewat {{ abs(intval($sisaHari)) }} hari
                                            @else
                                                {{ intval($sisaHari) }} hari
                                            @endif
                                        </td>
                                        <td>
                                            @if($sisaHari <= 3)
                                                <span class="badge badge-danger">Kritis</span>
                                            @elseif($sisaHari <= 7)
                                                <span class="badge badge-warning">Waspada</span>
                                            @else
                                                <span class="badge badge-success">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-6 text-slate-500">Tidak ada barang yang mendekati jatuh tempo.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Transaksi Terbaru -->
            <div class="dash-card">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon bg-blue-100 text-blue-600"><i class="bi bi-file-earmark-text"></i></div>
                        Aktivitas Transaksi Terbaru
                    </div>
                    <a href="{{ route('admin.laporan') }}" class="dash-card-action">Lihat Semua Aktivitas</a>
                </div>
                <div class="dash-card-body p-0">
                    <div class="table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Barang</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $act)
                                    <tr>
                                        <td class="font-semibold text-slate-800">{{ $act['pelanggan'] }}</td>
                                        <td class="text-slate-600">{{ $act['barang'] }}</td>
                                        <td>
                                            <span class="act-badge act-badge-{{ $act['color'] }}">{{ $act['tipe'] }}</span>
                                        </td>
                                        <td class="text-slate-500 text-sm">
                                            {{ \Carbon\Carbon::parse($act['waktu'])->translatedFormat('d M Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="text-emerald-500 font-medium"><i class="bi bi-check2-circle mr-1"></i>{{ $act['status'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-6 text-slate-500">Belum ada aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="dashboard-right">
            
            <!-- Grafik Transaksi Bulanan -->
            <div class="dash-card h-full">
                <div class="dash-card-header">
                    <div class="dash-card-title">
                        <div class="dash-card-icon bg-purple-100 text-purple-600"><i class="bi bi-bar-chart-fill"></i></div>
                        Grafik Transaksi Bulanan
                    </div>
                    <div class="dash-card-filter">
                        <form action="{{ route('admin.dashboard') }}" method="GET">
                            <select name="range" onchange="this.form.submit()" class="form-select text-sm border-slate-200 rounded-lg text-slate-600 focus:ring-primary focus:border-primary">
                                <option value="3" {{ $range == 3 ? 'selected' : '' }}>3 Bulan Terakhir</option>
                                <option value="6" {{ $range == 6 ? 'selected' : '' }}>6 Bulan Terakhir</option>
                                <option value="12" {{ $range == 12 ? 'selected' : '' }}>12 Bulan Terakhir</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="dash-card-body">
                    <canvas id="transactionChart" style="min-height: 300px; width: 100%;"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* UTILITIES */
    .text-success { color: #10B981; }
    .text-danger { color: #EF4444; }
    .text-primary { color: #4F46E5; }
    
    .bg-indigo-100 { background-color: #E0E7FF; }
    .text-indigo-600 { color: #4F46E5; }
    
    .bg-blue-100 { background-color: #DBEAFE; }
    .text-blue-600 { color: #2563EB; }
    
    .bg-purple-100 { background-color: #F3E8FF; }
    .text-purple-600 { color: #9333EA; }

    .text-emerald-500 { color: #10B981; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .text-slate-800 { color: #1E293B; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .text-slate-500 { color: #64748B; }
    .text-sm { font-size: 0.875rem; }
    .p-0 { padding: 0 !important; }
    .p-6 { padding: 1.5rem !important; }
    .mr-1 { margin-right: 0.25rem; }
    .h-full { height: 100%; }

    /* STAT GRIDS */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #F1F5F9;
        border-radius: 20px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-icon-primary { background: #EEF2FF; color: #4F46E5; }
    .stat-icon-info { background: #F0F9FF; color: #0EA5E9; }
    .stat-icon-success { background: #ECFDF5; color: #10B981; }
    .stat-icon-warning { background: #FFF7ED; color: #F97316; }

    .stat-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .stat-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #64748B;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.2;
    }

    .stat-subtitle {
        font-size: 12px;
        font-weight: 500;
        margin-top: 2px;
    }

    /* DASHBOARD MAIN GRID */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .dashboard-left {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .dashboard-right {
        display: flex;
        flex-direction: column;
    }

    /* DASHBOARD CARDS */
    .dash-card {
        background: #fff;
        border: 1px solid #F1F5F9;
        border-radius: 20px;
        box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .dash-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #F1F5F9;
    }

    .dash-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0F172A;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dash-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .dash-card-action {
        font-size: 13px;
        font-weight: 600;
        color: #4F46E5;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .dash-card-action:hover {
        color: #4338CA;
    }

    .dash-card-body {
        padding: 24px;
        flex: 1;
    }

    /* TABLES */
    .dash-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dash-table th {
        text-align: left;
        padding: 12px 24px;
        font-size: 12px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #F8FAFC;
        border-bottom: 1px solid #F1F5F9;
    }

    .dash-table td {
        padding: 16px 24px;
        font-size: 14px;
        border-bottom: 1px solid #F8FAFC;
        vertical-align: middle;
    }

    .dash-table tbody tr:last-child td {
        border-bottom: none;
    }

    .dash-table tbody tr:hover {
        background: #F8FAFC;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-danger { background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; }
    .badge-warning { background: #FFF7ED; color: #F97316; border: 1px solid #FFEDD5; }
    .badge-success { background: #ECFDF5; color: #10B981; border: 1px solid #D1FAE5; }

    .act-badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .act-badge-primary { background: #EEF2FF; color: #4F46E5; border: 1px solid #E0E7FF; }
    .act-badge-info { background: #F0F9FF; color: #0EA5E9; border: 1px solid #E0F2FE; }
    .act-badge-success { background: #ECFDF5; color: #10B981; border: 1px solid #D1FAE5; }
    .act-badge-warning { background: #FFF7ED; color: #F97316; border: 1px solid #FFEDD5; }

    @media (max-width: 1280px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .stat-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rawChartData = @json($chartData);
        
        const ctx = document.getElementById('transactionChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rawChartData.labels,
                datasets: [
                    {
                        label: 'Gadai',
                        data: rawChartData.gadai,
                        backgroundColor: '#4F46E5', // Indigo
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Tebus',
                        data: rawChartData.tebus,
                        backgroundColor: '#3B82F6', // Blue
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Jual (Lelang)',
                        data: rawChartData.jual,
                        backgroundColor: '#10B981', // Emerald
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20,
                            font: {
                                family: "'Poppins', sans-serif",
                                size: 12,
                                weight: 500
                            },
                            color: '#64748B'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        titleFont: {
                            family: "'Poppins', sans-serif",
                            size: 13
                        },
                        bodyFont: {
                            family: "'Poppins', sans-serif",
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F1F5F9',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Poppins', sans-serif", size: 11 },
                            color: '#94A3B8',
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: "'Poppins', sans-serif", size: 12 },
                            color: '#64748B'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
