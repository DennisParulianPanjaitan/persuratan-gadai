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

Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');
Route::post('/cek-status', [\App\Http\Controllers\LandingController::class, 'cekStatus'])->name('cek_status');

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/surat-gadai/{barang}', [\App\Http\Controllers\PublicSuratGadaiController::class, 'show'])
    ->name('public.surat_gadai');

Route::middleware(['auth', 'role:admin', 'no-cache'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard',
        [DashboardController::class,'index'])
        ->name('dashboard');

    Route::resource('jenis-barang',
        JenisBarangController::class)->names('jenis_barang');

    Route::resource('pelanggan',
        PelangganController::class);

    Route::resource('barang',
        BarangController::class);

Route::resource('transaksi-gadai', TransaksiGadaiController::class)->names('transaksi_gadai');

    Route::patch('transaksi-gadai/{transaksi_gadai}/tebus', [TransaksiGadaiController::class, 'tebus'])
        ->name('transaksi_gadai.tebus');

    Route::patch('transaksi-gadai/{transaksi_gadai}/jual', [TransaksiGadaiController::class, 'jual'])
        ->name('transaksi_gadai.jual');

    Route::patch('transaksi-gadai/{transaksi_gadai}/perpanjang', [TransaksiGadaiController::class, 'perpanjang'])
        ->name('transaksi_gadai.perpanjang');

    Route::resource('transaksi-penjualan',
        TransaksiPenjualanController::class)->names('transaksi_penjualan');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

    Route::get('/profile',
        [ProfileController::class,'index'])
        ->name('profile');

Route::resource('pengajuan-gadai', PengajuanGadaiController::class)->names('pengajuan_gadai');

    Route::patch('pengajuan-gadai/{barang:id_barang}/terima', [PengajuanGadaiController::class, 'terima'])
        ->name('pengajuan_gadai.terima');

    Route::patch('pengajuan-gadai/{barang:id_barang}/tolak', [PengajuanGadaiController::class, 'tolak'])
        ->name('pengajuan_gadai.tolak');

Route::resource('penyerahan-barang', PenyerahanBarangController::class)
    ->parameters(['penyerahan-barang' => 'barang'])
    ->names('penyerahan_barang');

    Route::patch('penyerahan-barang/{barang:id_barang}/terima', [PenyerahanBarangController::class, 'terima'])
        ->name('penyerahan_barang.terima');

    Route::patch('penyerahan-barang/{barang:id_barang}/tolak', [PenyerahanBarangController::class, 'tolak'])
        ->name('penyerahan_barang.tolak');

});

Route::middleware(['auth', 'role:pelanggan', 'no-cache'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pelanggan\DashboardController::class, 'index'])->name('dashboard');
});
