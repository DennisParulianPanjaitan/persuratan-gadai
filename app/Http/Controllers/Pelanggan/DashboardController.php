<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PelangganModels;
use App\Models\TransaksiGadaiModels;

class DashboardController extends Controller
{
    public function index()
    {
        // Cari data pelanggan berdasarkan user yang sedang login
        $pelanggan = PelangganModels::where('id_user', Auth::id())->first();

        $transaksiGadai = collect();
        $totalPinjaman = 0;
        $totalBarangAktif = 0;

        if ($pelanggan) {
            $transaksiGadai = TransaksiGadaiModels::with('barang')
                ->where('id_pelanggan', $pelanggan->id_pelanggan)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalPinjaman = $transaksiGadai->where('status', 'aktif')->sum('uang_pinjaman');
            $totalBarangAktif = $transaksiGadai->where('status', 'aktif')->count();
        }

        return view('pelanggan.dashboard.index', compact('pelanggan', 'transaksiGadai', 'totalPinjaman', 'totalBarangAktif'));
    }
}
