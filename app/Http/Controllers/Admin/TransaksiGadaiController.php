<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TransaksiGadaiController extends Controller
{
    public function index()
    {
        return view('admin.transaksi_gadai.index');
    }
}
