<?php

namespace App\Http\Controllers;

use App\Models\BarangModels;
use Illuminate\Http\Request;

class PublicSuratGadaiController extends Controller
{
    public function show(BarangModels $barang)
    {
        $barang->load(['jenisBarang', 'pelanggan']);
        $transaksi = \App\Models\TransaksiGadaiModels::with(['pelanggan', 'user'])
            ->where('id_barang', $barang->id_barang)
            ->latest()
            ->first();
        
        return view('public.surat_gadai.show', compact('barang', 'transaksi'));
    }
}
