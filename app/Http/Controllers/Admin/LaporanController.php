<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiGadaiModels;
use App\Models\TransaksiPenjualanModels;
use App\Models\TransaksiPerpanjanganModels;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $data = $this->getLaporanData($startDate, $endDate);

        return view('admin.laporan.index', compact('data', 'startDate', 'endDate'));
    }

    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $data = $this->getLaporanData($startDate, $endDate);

        return view('admin.laporan.cetak', compact('data', 'startDate', 'endDate'));
    }

    private function getLaporanData($startDate, $endDate)
    {
        // 1. Gadai Baru (Uang Keluar)
        $gadaiBaru = TransaksiGadaiModels::with('pelanggan')
            ->whereBetween('tanggal_gadai', [$startDate, $endDate])
            ->get();
        $totalGadaiOut = $gadaiBaru->sum('uang_pinjaman');

        // 2. Gadai Ditebus (Uang Masuk)
        $gadaiTebus = TransaksiGadaiModels::with('pelanggan')
            ->where('status', 'ditebus')
            ->whereBetween('tanggal_ditebus', [$startDate, $endDate])
            ->get();
        $totalTebusIn = $gadaiTebus->sum('total_ditebus');

        // 3. Perpanjangan (Uang Masuk / Bunga)
        $perpanjangan = TransaksiPerpanjanganModels::with('transaksiGadai.pelanggan')
            ->whereBetween('tanggal_perpanjangan', [$startDate, $endDate])
            ->get();
        $totalPerpanjanganIn = $perpanjangan->sum('biaya_perpanjangan');

        // 4. Penjualan / Lelang (Uang Masuk)
        $penjualan = TransaksiPenjualanModels::with('transaksiGadai.pelanggan')
            ->whereBetween('tanggal_jual', [$startDate, $endDate])
            ->get();
        $totalPenjualanIn = $penjualan->sum('harga_jual');

        // Ringkasan
        $totalPemasukan = $totalTebusIn + $totalPerpanjanganIn + $totalPenjualanIn;
        $totalPengeluaran = $totalGadaiOut;
        $arusKas = $totalPemasukan - $totalPengeluaran;

        return [
            'gadaiBaru' => $gadaiBaru,
            'gadaiTebus' => $gadaiTebus,
            'perpanjangan' => $perpanjangan,
            'penjualan' => $penjualan,
            'summary' => [
                'total_gadai_out' => $totalGadaiOut,
                'total_tebus_in' => $totalTebusIn,
                'total_perpanjangan_in' => $totalPerpanjanganIn,
                'total_penjualan_in' => $totalPenjualanIn,
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'arus_kas' => $arusKas,
            ]
        ];
    }
}
