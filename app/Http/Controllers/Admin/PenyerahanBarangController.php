<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PenyerahanBarangController extends Controller
{
    public function index()
    {
        return view('admin.penyerahan_barang.index');
    }
}
