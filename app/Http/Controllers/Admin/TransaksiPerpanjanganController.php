<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TransaksiPerpanjanganController extends Controller
{
    public function index()
    {
        return view('admin.transaksi_perpanjangan.index');
    }
}
