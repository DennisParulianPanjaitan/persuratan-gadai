<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangModels;
use App\Models\JenisBarangModels;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangQuery = BarangModels::query()->with('jenisBarang');

        if ($request->filled('tanggal')) {
            $barangQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('jenis_barang')) {
            $barangQuery->where('id_jenis_barang', $request->jenis_barang);
        }

        if ($request->filled('status')) {
            $barangQuery->where('status_verifikasi', strtolower($request->status));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $barangQuery->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('kondisi', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        $barangList = $barangQuery->latest('id_barang')->paginate(5)->withQueryString();
        $jenisBarangList = JenisBarangModels::query()->orderBy('nama_jenis')->get();

        return view('admin.barang.index', compact('barangList', 'jenisBarangList'));
    }

    public function show(BarangModels $barang)
    {
        $barang->load('jenisBarang');

        return view('admin.barang.show', compact('barang'));
    }
}
