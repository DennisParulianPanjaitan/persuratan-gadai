<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TransaksiGadaiModels;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransaksiGadaiController extends Controller
{
    public function index()
    {
        $query = TransaksiGadaiModels::with(['pelanggan', 'barang', 'user'])
            ->where('status', 'aktif');

        if (request()->filled('tanggal')) {
            $query->whereDate('tanggal_gadai', request('tanggal'));
        }

        if (request()->filled('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_transaksi', 'like', "%{$searchTerm}%")
                  ->orWhereHas('pelanggan', function($qPelanggan) use ($searchTerm) {
                      $qPelanggan->where('nama', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('barang', function($qBarang) use ($searchTerm) {
                      $qBarang->where('nama_barang', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $transaksiList = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.transaksi_gadai.index', compact('transaksiList'));
    }

    public function tebus(Request $request, TransaksiGadaiModels $transaksi_gadai)
    {
        $request->validate([
            'total_ditebus' => 'required|numeric|min:1',
        ]);

        if ($transaksi_gadai->status !== 'aktif') {
            return redirect()->route('admin.transaksi_gadai.index')
                ->with('error', 'Transaksi sudah tidak aktif atau sudah ditebus sebelumnya.');
        }

        $transaksi_gadai->update([
            'status' => 'ditebus',
            'tanggal_ditebus' => Carbon::now()->format('Y-m-d'),
            'total_ditebus' => $request->total_ditebus,
        ]);

        return redirect()->route('admin.transaksi_gadai.index')
            ->with('success', "Barang untuk transaksi {$transaksi_gadai->kode_transaksi} berhasil ditebus.");
    }

    public function jual(TransaksiGadaiModels $transaksi_gadai)
    {
        if ($transaksi_gadai->status !== 'aktif') {
            return redirect()->route('admin.transaksi_gadai.index')
                ->with('error', 'Transaksi sudah tidak aktif atau sudah diproses.');
        }

        $transaksi_gadai->update([
            'status' => 'dijual',
        ]);

        return redirect()->route('admin.transaksi_gadai.index')
            ->with('success', "Status barang untuk transaksi {$transaksi_gadai->kode_transaksi} berhasil diubah menjadi dijual/lelang.");
    }

    public function perpanjang(Request $request, TransaksiGadaiModels $transaksi_gadai)
    {
        $request->validate([
            'tambahan_bulan' => 'required|integer|min:1|max:4',
            'biaya_perpanjangan' => 'required|numeric|min:0',
        ]);

        if ($transaksi_gadai->status !== 'aktif') {
            return redirect()->route('admin.transaksi_gadai.index')
                ->with('error', 'Transaksi sudah tidak aktif atau sudah diproses.');
        }

        $jatuhTempoLama = Carbon::parse($transaksi_gadai->tanggal_jatuh_tempo);
        $jatuhTempoBaru = $jatuhTempoLama->copy()->addMonths((int) $request->tambahan_bulan)->format('Y-m-d');
        $perpanjanganKe = $transaksi_gadai->jumlah_perpanjangan + 1;

        \App\Models\TransaksiPerpanjanganModels::create([
            'id_transaksi_gadai' => $transaksi_gadai->id_transaksi_gadai,
            'id_user' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'tanggal_perpanjangan' => Carbon::now()->format('Y-m-d'),
            'perpanjangan_ke' => $perpanjanganKe,
            'tambahan_bulan' => $request->tambahan_bulan,
            'jatuh_tempo_sebelum' => $transaksi_gadai->tanggal_jatuh_tempo,
            'jatuh_tempo_sesudah' => $jatuhTempoBaru,
            'biaya_perpanjangan' => $request->biaya_perpanjangan,
            'catatan' => null,
        ]);

        $transaksi_gadai->update([
            'tanggal_jatuh_tempo' => $jatuhTempoBaru,
            'jumlah_perpanjangan' => $perpanjanganKe,
        ]);

        return redirect()->route('admin.transaksi_gadai.index')
            ->with('success', "Transaksi {$transaksi_gadai->kode_transaksi} berhasil diperpanjang {$request->tambahan_bulan} bulan hingga " . Carbon::parse($jatuhTempoBaru)->format('d M Y') . ".");
    }
}
