<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PelangganModels;
use App\Models\TransaksiGadaiModels;
use App\Models\BarangModels;

class DashboardController extends Controller
{
    public function index()
    {
        // Cari data pelanggan berdasarkan user yang sedang login
        $pelanggan = PelangganModels::where('id_user', Auth::id())->first();

        $transaksiGadai = collect();
        $barangGadai = collect();
        $totalPinjaman = 0;
        $totalBarangAktif = 0;

        if ($pelanggan) {
            $transaksiGadai = TransaksiGadaiModels::with('barang')
                ->where('id_pelanggan', $pelanggan->id_pelanggan)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $barangGadai = BarangModels::with(['transaksiGadai' => function($q) {
                $q->latest('created_at');
            }])
                ->where(function($query) use ($pelanggan) {
                    $query->where('id_pelanggan', $pelanggan->id_pelanggan)
                          ->orWhereHas('transaksiGadai', function($q) use ($pelanggan) {
                              $q->where('id_pelanggan', $pelanggan->id_pelanggan);
                          });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $totalPinjaman = $transaksiGadai->filter(function($t) {
                return $t->status === 'aktif' && \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->startOfDay() >= now()->startOfDay();
            })->sum('uang_pinjaman');

            $totalBarangAktif = $transaksiGadai->filter(function($t) {
                return $t->status === 'aktif' && \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->startOfDay() >= now()->startOfDay();
            })->count();
        }

        return view('pelanggan.dashboard.index', compact('pelanggan', 'transaksiGadai', 'barangGadai', 'totalPinjaman', 'totalBarangAktif'));
    }
}
