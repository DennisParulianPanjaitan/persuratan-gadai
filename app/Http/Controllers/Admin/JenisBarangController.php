<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisBarangModels;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function index(Request $request)
    {
        $jenisBarangQuery = JenisBarangModels::query();

        if ($request->filled('tanggal')) {
            $jenisBarangQuery->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $jenisBarangQuery->where(function ($query) use ($search) {
                $query->where('nama_jenis', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $jenisBarangList = $jenisBarangQuery->latest('id_jenis_barang')->paginate(5)->withQueryString();

        return view('admin.jenis_barang.index', compact('jenisBarangList'));
    }
}
