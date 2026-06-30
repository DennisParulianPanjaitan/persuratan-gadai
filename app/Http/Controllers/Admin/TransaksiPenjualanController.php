<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TransaksiGadaiModels;
use App\Models\TransaksiPenjualanModels;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $siapJualQuery = TransaksiGadaiModels::with(['pelanggan', 'barang', 'user'])
            ->where('status', 'dijual')
            ->whereDoesntHave('penjualan');

        $sudahTerjualQuery = TransaksiPenjualanModels::with(['transaksiGadai.pelanggan', 'transaksiGadai.barang', 'user']);

        if (request()->filled('tanggal')) {
            $siapJualQuery->whereDate('updated_at', request('tanggal'));
            $sudahTerjualQuery->whereDate('tanggal_jual', request('tanggal'));
        }

        if (request()->filled('search')) {
            $searchTerm = request('search');
            
            $siapJualQuery->where(function($q) use ($searchTerm) {
                $q->where('kode_transaksi', 'like', "%{$searchTerm}%")
                  ->orWhereHas('pelanggan', function($qPelanggan) use ($searchTerm) {
                      $qPelanggan->where('nama', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('barang', function($qBarang) use ($searchTerm) {
                      $qBarang->where('nama_barang', 'like', "%{$searchTerm}%");
                  });
            });

            $sudahTerjualQuery->whereHas('transaksiGadai', function($q) use ($searchTerm) {
                $q->where('kode_transaksi', 'like', "%{$searchTerm}%")
                  ->orWhereHas('pelanggan', function($qPelanggan) use ($searchTerm) {
                      $qPelanggan->where('nama', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('barang', function($qBarang) use ($searchTerm) {
                      $qBarang->where('nama_barang', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $siapJualList = $siapJualQuery->orderBy('updated_at', 'desc')->paginate(5, ['*'], 'siap_page')->withQueryString();
        $sudahTerjualList = $sudahTerjualQuery->orderBy('tanggal_jual', 'desc')->paginate(5, ['*'], 'terjual_page')->withQueryString();

        return view('admin.transaksi_penjualan.index', compact('siapJualList', 'sudahTerjualList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_transaksi_gadai' => 'required|exists:t_transaksi_gadai,id_transaksi_gadai',
            'harga_jual' => 'required|numeric|min:1',
            'catatan' => 'nullable|string',
        ]);

        $transaksiGadai = TransaksiGadaiModels::findOrFail($request->id_transaksi_gadai);

        if ($transaksiGadai->status !== 'dijual' || $transaksiGadai->penjualan()->exists()) {
            return redirect()->route('admin.transaksi_penjualan.index')
                ->with('error', 'Barang ini tidak valid untuk dijual atau sudah terjual.');
        }

        $labaRugi = $request->harga_jual - $transaksiGadai->uang_pinjaman;

        TransaksiPenjualanModels::create([
            'id_transaksi_gadai' => $transaksiGadai->id_transaksi_gadai,
            'id_barang'          => $transaksiGadai->id_barang,
            'id_user'            => Auth::id() ?? 1,
            'tanggal_jual'       => Carbon::now()->format('Y-m-d'),
            'harga_jual'         => $request->harga_jual,
            'biaya_lain'         => 0,
            'laba_rugi'          => $labaRugi,
            'catatan'            => $request->catatan,
        ]);

        return redirect()->route('admin.transaksi_penjualan.index')
            ->with('success', "Barang lelang dari transaksi {$transaksiGadai->kode_transaksi} berhasil dijual.");
    }
}
