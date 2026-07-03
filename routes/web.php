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

// Lupa Password
Route::get('/forgot-password', [\App\Http\Controllers\AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password/check', [\App\Http\Controllers\AuthController::class, 'checkUsername'])->name('password.check');
Route::get('/forgot-password/reset', [\App\Http\Controllers\AuthController::class, 'showResetPassword'])->name('password.reset.form');
Route::post('/forgot-password/reset', [\App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.reset');

// Register
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

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

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

    Route::get('/pembayaran', [\App\Http\Controllers\Admin\PembayaranOnlineController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{id}', [\App\Http\Controllers\Admin\PembayaranOnlineController::class, 'show'])->name('pembayaran.show');
    Route::patch('/pembayaran/{id}/terima', [\App\Http\Controllers\Admin\PembayaranOnlineController::class, 'terima'])->name('pembayaran.terima');
    Route::patch('/pembayaran/{id}/tolak', [\App\Http\Controllers\Admin\PembayaranOnlineController::class, 'tolak'])->name('pembayaran.tolak');

});

Route::middleware(['auth', 'role:pelanggan', 'no-cache'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pelanggan\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pengajuan-gadai/create', [\App\Http\Controllers\Pelanggan\PengajuanGadaiController::class, 'create'])->name('pengajuan_gadai.create');
    Route::post('/pengajuan-gadai', [\App\Http\Controllers\Pelanggan\PengajuanGadaiController::class, 'store'])->name('pengajuan_gadai.store');
    
    Route::get('/riwayat-gadai', [\App\Http\Controllers\Pelanggan\RiwayatGadaiController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat-gadai/{id}', [\App\Http\Controllers\Pelanggan\RiwayatGadaiController::class, 'show'])->name('riwayat.show');
    Route::delete('/riwayat-gadai/{id}', [\App\Http\Controllers\Pelanggan\RiwayatGadaiController::class, 'destroy'])->name('riwayat.destroy');
    
    // Pembayaran Online
    Route::post('/riwayat-gadai/{id_transaksi_gadai}/tebus', [\App\Http\Controllers\Pelanggan\RiwayatGadaiController::class, 'bayarTebus'])->name('riwayat.bayar_tebus');
    Route::post('/riwayat-gadai/{id_transaksi_gadai}/perpanjang', [\App\Http\Controllers\Pelanggan\RiwayatGadaiController::class, 'bayarPerpanjang'])->name('riwayat.bayar_perpanjang');


    // Profile
    Route::get('/profile', [\App\Http\Controllers\Pelanggan\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Pelanggan\ProfileController::class, 'update'])->name('profile.update');
});
