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
}

