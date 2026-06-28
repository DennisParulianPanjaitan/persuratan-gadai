<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        return view('admin.transaksi_penjualan.index');
    }
}
