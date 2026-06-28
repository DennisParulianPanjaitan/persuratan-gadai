<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
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

    public function terima(BarangModels $barang): RedirectResponse
    {
        $barang->update([
            'status_verifikasi' => 'terverifikasi',
        ]);

        return redirect()
            ->route('admin.pengajuan-gadai.index')
            ->with('success', 'Barang berhasil diterima dan status diubah menjadi terverifikasi.');
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
