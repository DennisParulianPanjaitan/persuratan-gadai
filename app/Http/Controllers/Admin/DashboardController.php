<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PelangganModels;
use App\Models\TransaksiGadaiModels;
use App\Models\TransaksiPerpanjanganModels;
use App\Models\TransaksiPenjualanModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK ATAS
        $totalPelanggan = PelangganModels::count();
        $barangGadaiAktif = TransaksiGadaiModels::where('status', 'aktif')->count();
        $totalPinjamanAktif = TransaksiGadaiModels::where('status', 'aktif')->sum('uang_pinjaman');
        
        $mendekatiJatuhTempoCount = TransaksiGadaiModels::where('status', 'aktif')
            ->where('tanggal_jatuh_tempo', '<=', Carbon::now()->addDays(14))
            ->count();

        // 2. DAFTAR BARANG MENDEKATI JATUH TEMPO (Top 5)
        $barangMendekatiJatuhTempo = TransaksiGadaiModels::with('pelanggan', 'barang')
            ->where('status', 'aktif')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->take(5)
            ->get();

        // 3. AKTIVITAS TRANSAKSI TERBARU (Gabungan)
        $activities = collect();

        // - Gadai Baru
        $gadaiBaru = TransaksiGadaiModels::with('pelanggan', 'barang')->latest()->take(5)->get();
        foreach($gadaiBaru as $item) {
            $activities->push([
                'tipe' => 'Pengajuan Gadai',
                'pelanggan' => $item->pelanggan->nama ?? '-',
                'barang' => $item->barang->nama_barang ?? '-',
                'waktu' => $item->created_at,
                'status' => 'Selesai',
                'color' => 'primary' // Ungu
            ]);
        }

        // - Penebusan
        $penebusan = TransaksiGadaiModels::with('pelanggan', 'barang')->where('status', 'ditebus')->orderBy('tanggal_ditebus', 'desc')->take(5)->get();
        foreach($penebusan as $item) {
            $activities->push([
                'tipe' => 'Penebusan',
                'pelanggan' => $item->pelanggan->nama ?? '-',
                'barang' => $item->barang->nama_barang ?? '-',
                'waktu' => Carbon::parse($item->tanggal_ditebus), // fallback waktu jika tidak ada created_at tebus
                'status' => 'Selesai',
                'color' => 'info' // Biru
            ]);
        }

        // - Perpanjangan
        $perpanjangan = TransaksiPerpanjanganModels::with('transaksiGadai.pelanggan', 'transaksiGadai.barang')->latest()->take(5)->get();
        foreach($perpanjangan as $item) {
            $activities->push([
                'tipe' => 'Perpanjangan',
                'pelanggan' => $item->transaksiGadai->pelanggan->nama ?? '-',
                'barang' => $item->transaksiGadai->barang->nama_barang ?? '-',
                'waktu' => $item->created_at,
                'status' => 'Selesai',
                'color' => 'success' // Hijau
            ]);
        }

        // - Penjualan (Lelang)
        $penjualan = TransaksiPenjualanModels::with('transaksiGadai.pelanggan', 'transaksiGadai.barang')->latest()->take(5)->get();
        foreach($penjualan as $item) {
            $activities->push([
                'tipe' => 'Penjualan',
                'pelanggan' => $item->transaksiGadai->pelanggan->nama ?? '-',
                'barang' => $item->transaksiGadai->barang->nama_barang ?? '-',
                'waktu' => $item->created_at,
                'status' => 'Selesai',
                'color' => 'warning' // Orange/Merah
            ]);
        }

        // Sort activities by waktu descending and take top 5
        $recentActivities = $activities->sortByDesc('waktu')->take(5);

        // 4. GRAFIK TRANSAKSI BULANAN (Dinamis berdasarkan rentang waktu)
        $range = request('range', 6);
        $range = (int) $range;
        if (!in_array($range, [3, 6, 12])) {
            $range = 6;
        }

        $chartData = [
            'labels' => [],
            'gadai' => [],
            'tebus' => [],
            'jual' => []
        ];

        for ($i = $range - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M Y'); // ex: Mei 2025
            
            $chartData['labels'][] = $monthName;
            
            $gadaiCount = TransaksiGadaiModels::whereYear('tanggal_gadai', $month->year)
                ->whereMonth('tanggal_gadai', $month->month)->count();
                
            $tebusCount = TransaksiGadaiModels::where('status', 'ditebus')
                ->whereYear('tanggal_ditebus', $month->year)
                ->whereMonth('tanggal_ditebus', $month->month)->count();
                
            $jualCount = TransaksiPenjualanModels::whereYear('tanggal_jual', $month->year)
                ->whereMonth('tanggal_jual', $month->month)->count();
                
            $chartData['gadai'][] = $gadaiCount;
            $chartData['tebus'][] = $tebusCount;
            $chartData['jual'][] = $jualCount;
        }

        return view('admin.dashboard.index', compact(
            'totalPelanggan',
            'barangGadaiAktif',
            'totalPinjamanAktif',
            'mendekatiJatuhTempoCount',
            'barangMendekatiJatuhTempo',
            'recentActivities',
            'chartData',
            'range'
        ));
    }
}
