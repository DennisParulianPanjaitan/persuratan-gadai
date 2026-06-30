<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGadaiModels;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan Halaman Utama (Landing Page)
     */
    public function index()
    {
        // Ambil data barang yang sedang dijual untuk marquee promosi di landing page
        $barangDijual = TransaksiGadaiModels::with('barang')
            ->where('status', 'dijual')
            ->whereNotIn('id_transaksi_gadai', function($query) {
                $query->select('id_transaksi_gadai')->from('t_transaksi_penjualan');
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('public.landing', compact('barangDijual'));
    }

    /**
     * Proses pengecekan kode transaksi
     */
    public function cekStatus(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|string|max:50'
        ]);

        $kode = $request->input('kode_transaksi');

        // Cari transaksi berdasarkan kode transaksi dan load relasi barang
        $transaksi = TransaksiGadaiModels::with('barang')
            ->where('kode_transaksi', strtoupper($kode))
            ->first();

        if ($transaksi) {
            // Kembalikan ke halaman landing dengan data transaksi
            return back()->with('hasil_pencarian', $transaksi)->withInput();
        } else {
            // Kembalikan error jika tidak ditemukan
            return back()->with('error_pencarian', 'Maaf, data gadai dengan kode transaksi tersebut tidak ditemukan.')->withInput();
        }
    }
}
