<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use App\Models\PelangganModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatGadaiController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = PelangganModels::where('id_user', Auth::id())->first();

        if (!$pelanggan) {
            return redirect()->route('pelanggan.dashboard')->with('error', 'Profil pelanggan Anda tidak ditemukan.');
        }

        $baseQuery = BarangModels::where(function($query) use ($pelanggan) {
            $query->where('id_pelanggan', $pelanggan->id_pelanggan)
                  ->orWhereHas('transaksiGadai', function($q) use ($pelanggan) {
                      $q->where('id_pelanggan', $pelanggan->id_pelanggan);
                  });
        });

        $counts = [
            'semua' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status_verifikasi', 'pending')->doesntHave('transaksiGadai')->count(),
            'diterima' => (clone $baseQuery)->where('status_verifikasi', 'terverifikasi')->doesntHave('transaksiGadai')->count(),
            'ditolak' => (clone $baseQuery)->where('status_verifikasi', 'ditolak')->doesntHave('transaksiGadai')->count(),
            'gadai' => (clone $baseQuery)->whereHas('transaksiGadai', function($q) { 
                $q->where('status', 'aktif')->whereDate('tanggal_jatuh_tempo', '>=', now()->startOfDay()); 
            })->count(),
            'jatuh_tempo' => (clone $baseQuery)->whereHas('transaksiGadai', function($q) { 
                $q->where('status', 'aktif')->whereDate('tanggal_jatuh_tempo', '<', now()->startOfDay()); 
            })->count(),
            'ditebus' => (clone $baseQuery)->whereHas('transaksiGadai', function($q) { $q->where('status', 'ditebus'); })->count(),
            'dijual' => (clone $baseQuery)->whereHas('transaksiGadai', function($q) { $q->where('status', 'dijual'); })->count(),
        ];

        $barangQuery = (clone $baseQuery)->with(['jenisBarang', 'transaksiGadai' => function($q) {
            $q->latest('created_at');
        }]);

        // Filter status
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'pending') {
                $barangQuery->where('status_verifikasi', 'pending')->doesntHave('transaksiGadai');
            } elseif ($status === 'diterima') {
                $barangQuery->where('status_verifikasi', 'terverifikasi')->doesntHave('transaksiGadai');
            } elseif ($status === 'ditolak') {
                $barangQuery->where('status_verifikasi', 'ditolak')->doesntHave('transaksiGadai');
            } elseif ($status === 'gadai') {
                $barangQuery->whereHas('transaksiGadai', function($q) {
                    $q->where('status', 'aktif')->whereDate('tanggal_jatuh_tempo', '>=', now()->startOfDay());
                });
            } elseif ($status === 'jatuh_tempo') {
                $barangQuery->whereHas('transaksiGadai', function($q) {
                    $q->where('status', 'aktif')->whereDate('tanggal_jatuh_tempo', '<', now()->startOfDay());
                });
            } elseif (in_array($status, ['ditebus', 'dijual'])) {
                $barangQuery->whereHas('transaksiGadai', function($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        $barangList = $barangQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pelanggan.riwayat.index', compact('barangList', 'counts'));
    }

    public function show($id)
    {
        $pelanggan = PelangganModels::where('id_user', Auth::id())->firstOrFail();

        $barang = BarangModels::with(['jenisBarang', 'transaksiGadai'])
            ->where(function($query) use ($pelanggan) {
                $query->where('id_pelanggan', $pelanggan->id_pelanggan)
                      ->orWhereHas('transaksiGadai', function($q) use ($pelanggan) {
                          $q->where('id_pelanggan', $pelanggan->id_pelanggan);
                      });
            })
            ->findOrFail($id);

        return view('pelanggan.riwayat.show', compact('barang'));
    }

    public function destroy($id)
    {
        $pelanggan = PelangganModels::where('id_user', Auth::id())->firstOrFail();

        $barang = BarangModels::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn('status_verifikasi', ['pending', 'ditolak', 'terverifikasi'])
            ->doesntHave('transaksiGadai')
            ->findOrFail($id);

        $barang->delete();

        return redirect()->route('pelanggan.riwayat.index')->with('success', 'Riwayat pengajuan berhasil dihapus.');
    }
}
