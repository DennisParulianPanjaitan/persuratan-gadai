<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use App\Models\PelangganModels;
use App\Models\PembayaranOnlineModels;
use App\Models\TransaksiGadaiModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    public function bayarTebus(Request $request, $id_transaksi_gadai)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nominal_bayar' => 'required|numeric|min:1'
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
            'nominal_bayar.required' => 'Terjadi kesalahan, nominal tidak terbaca'
        ]);

        $transaksi = TransaksiGadaiModels::where('id_transaksi_gadai', $id_transaksi_gadai)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Cek apakah sudah ada pembayaran pending
        $pending = PembayaranOnlineModels::where('id_transaksi_gadai', $transaksi->id_transaksi_gadai)
            ->where('status', 'menunggu_konfirmasi')
            ->first();

        if ($pending) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan pembayaran yang sedang menunggu konfirmasi.');
        }

        $buktiUrl = '';
        if ($request->hasFile('bukti_pembayaran')) {
            $cloudinaryUrl = env('CLOUDINARY_URL');
            if ($cloudinaryUrl) {
                $cloudinary = new \Cloudinary\Cloudinary($cloudinaryUrl);
                $upload = $cloudinary->uploadApi()->upload($request->file('bukti_pembayaran')->getRealPath(), [
                    'folder' => 'gerlian-jaya/pembayaran'
                ]);
                $buktiUrl = $upload['secure_url'];
            } else {
                $buktiUrl = $request->file('bukti_pembayaran')->store('pembayaran', 'public');
            }
        }

        PembayaranOnlineModels::create([
            'id_transaksi_gadai' => $transaksi->id_transaksi_gadai,
            'jenis_pembayaran' => 'tebus',
            'jumlah_bulan' => null,
            'nominal_bayar' => $request->nominal_bayar,
            'bukti_pembayaran' => $buktiUrl,
            'status' => 'menunggu_konfirmasi'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran tebus gadai berhasil dikirim dan sedang menunggu konfirmasi Admin.');
    }

    public function bayarPerpanjang(Request $request, $id_transaksi_gadai)
    {
        $request->validate([
            'jumlah_bulan' => 'required|integer|min:1|max:4',
            'nominal_bayar' => 'required|numeric|min:1',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'jumlah_bulan.required' => 'Jumlah bulan wajib diisi',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        $transaksi = TransaksiGadaiModels::where('id_transaksi_gadai', $id_transaksi_gadai)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Cek apakah sudah ada pembayaran pending
        $pending = PembayaranOnlineModels::where('id_transaksi_gadai', $transaksi->id_transaksi_gadai)
            ->where('status', 'menunggu_konfirmasi')
            ->first();

        if ($pending) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan pembayaran yang sedang menunggu konfirmasi.');
        }

        $buktiUrl = '';
        if ($request->hasFile('bukti_pembayaran')) {
            $cloudinaryUrl = env('CLOUDINARY_URL');
            if ($cloudinaryUrl) {
                $cloudinary = new \Cloudinary\Cloudinary($cloudinaryUrl);
                $upload = $cloudinary->uploadApi()->upload($request->file('bukti_pembayaran')->getRealPath(), [
                    'folder' => 'gerlian-jaya/pembayaran'
                ]);
                $buktiUrl = $upload['secure_url'];
            } else {
                $buktiUrl = $request->file('bukti_pembayaran')->store('pembayaran', 'public');
            }
        }

        PembayaranOnlineModels::create([
            'id_transaksi_gadai' => $transaksi->id_transaksi_gadai,
            'jenis_pembayaran' => 'perpanjangan',
            'jumlah_bulan' => $request->jumlah_bulan,
            'nominal_bayar' => $request->nominal_bayar,
            'bukti_pembayaran' => $buktiUrl,
            'status' => 'menunggu_konfirmasi'
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran perpanjangan gadai berhasil dikirim dan sedang menunggu konfirmasi Admin.');
    }
}
