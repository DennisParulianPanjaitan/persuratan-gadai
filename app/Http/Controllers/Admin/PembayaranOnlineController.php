<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranOnlineModels;
use App\Models\TransaksiGadaiModels;
use App\Models\TransaksiPerpanjanganModels;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PembayaranOnlineController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranOnlineModels::with(['transaksiGadai.pelanggan', 'transaksiGadai.barang']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default show pending
            $query->where('status', 'menunggu_konfirmasi');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('transaksiGadai', function($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function($q2) use ($search) {
                      $q2->whereHas('user', function($q3) use ($search) {
                          $q3->where('nama', 'like', "%{$search}%");
                      });
                  });
            });
        }

        $pembayaranList = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $counts = [
            'semua' => PembayaranOnlineModels::count(),
            'pending' => PembayaranOnlineModels::where('status', 'menunggu_konfirmasi')->count(),
            'disetujui' => PembayaranOnlineModels::where('status', 'disetujui')->count(),
            'ditolak' => PembayaranOnlineModels::where('status', 'ditolak')->count(),
        ];

        return view('admin.pembayaran.index', compact('pembayaranList', 'counts'));
    }

    public function show($id)
    {
        $pembayaran = PembayaranOnlineModels::with(['transaksiGadai.pelanggan', 'transaksiGadai.barang'])->findOrFail($id);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function terima(Request $request, $id)
    {
        $pembayaran = PembayaranOnlineModels::with('transaksiGadai')->findOrFail($id);

        if ($pembayaran->status !== 'menunggu_konfirmasi') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $transaksi = $pembayaran->transaksiGadai;

        if ($pembayaran->jenis_pembayaran === 'tebus') {
            $transaksi->update([
                'status' => 'ditebus',
                'tanggal_ditebus' => Carbon::now(),
                'total_ditebus' => $pembayaran->nominal_bayar
            ]);
        } elseif ($pembayaran->jenis_pembayaran === 'perpanjangan') {
            $jatuhTempoLama = Carbon::parse($transaksi->tanggal_jatuh_tempo);
            $jatuhTempoBaru = (clone $jatuhTempoLama)->addMonths($pembayaran->jumlah_bulan);

            // Record di tabel transaksi_perpanjangan
            TransaksiPerpanjanganModels::create([
                'id_transaksi_gadai' => $transaksi->id_transaksi_gadai,
                'id_user' => auth()->id(), // Admin yang melakukan approval
                'tanggal_perpanjangan' => Carbon::now(),
                'perpanjangan_ke' => $transaksi->jumlah_perpanjangan + 1,
                'tambahan_bulan' => $pembayaran->jumlah_bulan,
                'jatuh_tempo_sebelum' => $jatuhTempoLama,
                'jatuh_tempo_sesudah' => $jatuhTempoBaru,
                'biaya_perpanjangan' => $pembayaran->nominal_bayar,
                'catatan' => 'Perpanjangan otomatis via pembayaran online',
            ]);

            $transaksi->update([
                'tanggal_gadai' => Carbon::now(),
                'tanggal_jatuh_tempo' => $jatuhTempoBaru,
                'jumlah_perpanjangan' => $transaksi->jumlah_perpanjangan + 1
            ]);
        }

        $pembayaran->update([
            'status' => 'disetujui'
        ]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil disetujui dan transaksi telah diperbarui.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500'
        ]);

        $pembayaran = PembayaranOnlineModels::findOrFail($id);

        if ($pembayaran->status !== 'menunggu_konfirmasi') {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $pembayaran->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran telah ditolak.');
    }
}
