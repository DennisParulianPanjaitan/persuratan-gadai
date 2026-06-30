<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use Illuminate\Http\Request;

class PenyerahanBarangController extends Controller
{
    public function index(Request $request)
    {
        $barangQuery = BarangModels::query()
            ->with('jenisBarang')
            ->where('status_verifikasi', 'terverifikasi')
            ->whereDoesntHave('transaksiGadai');

        if ($request->filled('tanggal')) {
            $barangQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $barangQuery->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        $barangList = $barangQuery->latest('id_barang')->paginate(10)->withQueryString();

        return view('admin.penyerahan_barang.index', compact('barangList'));
    }

    public function show(BarangModels $barang)
    {
        // Pastikan barang terverifikasi dan belum punya transaksi gadai
        if ($barang->status_verifikasi !== 'terverifikasi' || $barang->transaksiGadai()->exists()) {
            return redirect()->route('admin.penyerahan_barang.index')
                ->with('error', 'Barang tidak valid untuk diproses.');
        }

        $barang->load('jenisBarang');
        $pelangganList = \App\Models\PelangganModels::orderBy('nama')->get();

        return view('admin.penyerahan_barang.show', compact('barang', 'pelangganList'));
    }

    public function terima(Request $request, BarangModels $barang): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'id_pelanggan'        => 'required|exists:m_pelanggan,id_pelanggan',
            'uang_pinjaman'       => 'required|numeric|min:1',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:today',
        ], [
            'id_pelanggan.required'        => 'Pelanggan wajib dipilih.',
            'id_pelanggan.exists'          => 'Data pelanggan tidak ditemukan.',
            'uang_pinjaman.required'       => 'Uang pinjaman wajib diisi.',
            'uang_pinjaman.numeric'        => 'Uang pinjaman harus berupa angka.',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggal_jatuh_tempo.date'     => 'Format tanggal tidak valid.',
        ]);

        // Generate Kode Transaksi
        $kodeTransaksi = 'TRX-GADAI-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));

        // Ambil User ID (kasir) atau fallback ke 1 jika belum ada auth
        $idUser = auth()->id() ?? 1;

        \App\Models\TransaksiGadaiModels::create([
            'id_pelanggan'        => $request->id_pelanggan,
            'id_barang'           => $barang->id_barang,
            'id_user'             => $idUser,
            'kode_transaksi'      => $kodeTransaksi,
            'uang_pinjaman'       => $request->uang_pinjaman,
            'tanggal_gadai'       => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status'              => 'aktif',
            'jumlah_perpanjangan' => 0,
        ]);

        return redirect()
            ->route('admin.penyerahan_barang.index')
            ->with('success', "Transaksi gadai berhasil diproses. (Kode: {$kodeTransaksi})");
    }

    public function tolak(BarangModels $barang): \Illuminate\Http\RedirectResponse
    {
        // Ubah status barang menjadi ditolak
        $barang->update([
            'status_verifikasi' => 'ditolak',
        ]);

        return redirect()
            ->route('admin.penyerahan_barang.index')
            ->with('success', "Barang \"{$barang->nama_barang}\" batal diproses dan telah ditolak.");
    }
}

