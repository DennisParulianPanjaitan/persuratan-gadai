<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JenisBarangController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TransaksiGadaiController;
use App\Http\Controllers\Admin\TransaksiPenjualanController;
use App\Http\Controllers\Admin\TransaksiPerpanjanganController;
use App\Http\Controllers\Admin\PenyerahanBarangController;
use App\Http\Controllers\Admin\PengajuanGadaiController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/dashboard');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard',
        [DashboardController::class,'index'])
        ->name('dashboard');

    Route::resource('jenis-barang',
        JenisBarangController::class);

    Route::resource('pelanggan',
        PelangganController::class);

    Route::resource('barang',
        BarangController::class);

    Route::resource('transaksi-gadai',
        TransaksiGadaiController::class);

    Route::resource('transaksi-perpanjangan',
        TransaksiPerpanjanganController::class);

    Route::resource('transaksi-penjualan',
        TransaksiPenjualanController::class);

    Route::get('/laporan',
        [LaporanController::class,'index'])
        ->name('laporan');

    Route::get('/profile',
        [ProfileController::class,'index'])
        ->name('profile');

Route::resource('pengajuan-gadai', PengajuanGadaiController::class);

    Route::patch('pengajuan-gadai/{barang:id_barang}/terima', [PengajuanGadaiController::class, 'terima'])
        ->name('pengajuan-gadai.terima');

    Route::patch('pengajuan-gadai/{barang:id_barang}/tolak', [PengajuanGadaiController::class, 'tolak'])
        ->name('pengajuan-gadai.tolak');

Route::resource('penyerahan-barang', PenyerahanBarangController::class);

});
