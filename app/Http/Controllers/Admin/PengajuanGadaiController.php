<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use App\Models\TransaksiGadaiModels;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PengajuanGadaiController extends Controller
{
    public function index(Request $request)
    {
        $barangQuery = BarangModels::query()
            ->with('jenisBarang')
            ->where('status_verifikasi', 'pending');

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

        $barangList = $barangQuery->latest('id_barang')->paginate(5)->withQueryString();

        return view('admin.pengajuan_gadai.index', compact('barangList'));
    }

    public function terima(Request $request, BarangModels $barang): RedirectResponse
    {
        // Validasi harga gadai dari SweetAlert
        $request->validate([
            'harga_gadai' => 'required|numeric|min:1',
        ], [
            'harga_gadai.required' => 'Harga gadai wajib diisi.',
            'harga_gadai.numeric'  => 'Harga gadai harus berupa angka.',
            'harga_gadai.min'      => 'Harga gadai harus lebih dari 0.',
        ]);

        $hargaGadai = (float) $request->harga_gadai;

        // Update status barang menjadi terverifikasi dan simpan harga sementara
        $barang->update([
            'status_verifikasi' => 'terverifikasi',
            'harga_gadai_sementara' => $hargaGadai,
        ]);

        return redirect()
            ->route('admin.pengajuan-gadai.index')
            ->with('success', "Barang \"{$barang->nama_barang}\" berhasil diterima (Harga Gadai: Rp " . number_format($hargaGadai, 0, ',', '.') . "). Menunggu penyerahan barang fisik.");
    }

    public function tolak(BarangModels $barang): RedirectResponse
    {
        $barang->update([
            'status_verifikasi' => 'ditolak',
        ]);

        return redirect()
            ->route('admin.pengajuan-gadai.index')
            ->with('success', 'Barang berhasil ditolak.');
    }
}

