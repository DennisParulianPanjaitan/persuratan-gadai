<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function gadai()
    {
        return view('admin.laporan.gadai');
    }

    public function perpanjangan()
    {
        return view('admin.laporan.perpanjangan');
    }

    public function penjualan()
    {
        return view('admin.laporan.penjualan');
    }
}
