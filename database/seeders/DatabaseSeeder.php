<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. users
        DB::table('users')->insert([
            ['id_user' => 1, 'nama' => 'Administrator', 'email' => 'admin@gerlianjaya.com', 'password' => Hash::make('password'), 'role' => 'admin', 'status_aktif' => 1, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_user' => 2, 'nama' => 'Budi Santoso', 'email' => 'budi@gmail.com', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_user' => 3, 'nama' => 'Rina Putri', 'email' => 'rina@gmail.com', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_user' => 4, 'nama' => 'Andi Wijaya', 'email' => 'andi@gmail.com', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_user' => 5, 'nama' => 'Siska Lestari', 'email' => 'siska@gmail.com', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);

        // 2. m_jenis_barang
        DB::table('m_jenis_barang')->insert([
            ['id_jenis_barang' => 1, 'nama_jenis' => 'Emas', 'deskripsi' => 'Perhiasan emas', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_jenis_barang' => 2, 'nama_jenis' => 'Handphone', 'deskripsi' => 'Semua smartphone', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_jenis_barang' => 3, 'nama_jenis' => 'Laptop', 'deskripsi' => 'Laptop berbagai merk', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_jenis_barang' => 4, 'nama_jenis' => 'Elektronik', 'deskripsi' => 'Elektronik rumah tangga', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_jenis_barang' => 5, 'nama_jenis' => 'Kendaraan', 'deskripsi' => 'Motor dan Mobil', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);

        // 3. m_pelanggan
        DB::table('m_pelanggan')->insert([
            ['id_pelanggan' => 1, 'id_user' => 2, 'nama' => 'Ahmad Fauzi', 'no_hp' => '081234567890', 'alamat' => 'Malang', 'email' => 'ahmad@gmail.com', 'keterangan' => 'Pelanggan tetap', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_pelanggan' => 2, 'id_user' => 3, 'nama' => 'Budi Hartono', 'no_hp' => '081234567891', 'alamat' => 'Batu', 'email' => 'budi@gmail.com', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_pelanggan' => 3, 'id_user' => 4, 'nama' => 'Citra Lestari', 'no_hp' => '081234567892', 'alamat' => 'Malang', 'email' => 'citra@gmail.com', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_pelanggan' => 4, 'id_user' => 5, 'nama' => 'Dedi Saputra', 'no_hp' => '081234567893', 'alamat' => 'Kepanjen', 'email' => 'dedi@gmail.com', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_pelanggan' => 5, 'id_user' => null, 'nama' => 'Eka Putri', 'no_hp' => '081234567894', 'alamat' => 'Singosari', 'email' => 'eka@gmail.com', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);

        // 4. m_barang
        DB::table('m_barang')->insert([
            ['id_barang' => 1, 'id_jenis_barang' => 1, 'nama_barang' => 'Cincin Emas 24K', 'harga_beli' => 4500000.00, 'kondisi' => 'Baik', 'berat' => 8.50, 'foto_barang' => 'emas1.jpg', 'status_verifikasi' => 'pending', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 12:28:32'],
            ['id_barang' => 2, 'id_jenis_barang' => 2, 'nama_barang' => 'iPhone 13 Pro', 'harga_beli' => 9000000.00, 'kondisi' => 'Sangat Baik', 'berat' => 0.20, 'foto_barang' => 'iphone13.jpg', 'status_verifikasi' => 'ditolak', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 05:28:45'],
            ['id_barang' => 3, 'id_jenis_barang' => 3, 'nama_barang' => 'ASUS TUF Gaming', 'harga_beli' => 11000000.00, 'kondisi' => 'Baik', 'berat' => 2.30, 'foto_barang' => 'asus.jpg', 'status_verifikasi' => 'ditolak', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 05:15:55'],
            ['id_barang' => 4, 'id_jenis_barang' => 4, 'nama_barang' => 'TV Samsung 43 Inch', 'harga_beli' => 4200000.00, 'kondisi' => 'Baik', 'berat' => 10.50, 'foto_barang' => 'tv.jpg', 'status_verifikasi' => 'terverifikasi', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_barang' => 5, 'id_jenis_barang' => 5, 'nama_barang' => 'Honda Beat 2022', 'harga_beli' => 14500000.00, 'kondisi' => 'Sangat Baik', 'berat' => 90.00, 'foto_barang' => 'beat.jpg', 'status_verifikasi' => 'terverifikasi', 'keterangan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);

        // 5. t_transaksi_gadai
        DB::table('t_transaksi_gadai')->insert([
            ['id_transaksi_gadai' => 1, 'id_pelanggan' => 1, 'id_barang' => 1, 'id_user' => 2, 'kode_transaksi' => 'GD-0001', 'uang_pinjaman' => 3000000.00, 'tanggal_gadai' => '2026-06-01', 'tanggal_jatuh_tempo' => '2026-07-01', 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR001', 'jumlah_perpanjangan' => 0, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_transaksi_gadai' => 2, 'id_pelanggan' => 2, 'id_barang' => 2, 'id_user' => 2, 'kode_transaksi' => 'GD-0002', 'uang_pinjaman' => 6500000.00, 'tanggal_gadai' => '2026-06-03', 'tanggal_jatuh_tempo' => '2026-07-03', 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR002', 'jumlah_perpanjangan' => 1, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_transaksi_gadai' => 3, 'id_pelanggan' => 3, 'id_barang' => 3, 'id_user' => 3, 'kode_transaksi' => 'GD-0003', 'uang_pinjaman' => 7000000.00, 'tanggal_gadai' => '2026-06-05', 'tanggal_jatuh_tempo' => '2026-07-05', 'status' => 'ditebus', 'tanggal_ditebus' => '2026-06-25', 'total_ditebus' => 7350000.00, 'qr_code' => 'QR003', 'jumlah_perpanjangan' => 0, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_transaksi_gadai' => 4, 'id_pelanggan' => 4, 'id_barang' => 4, 'id_user' => 4, 'kode_transaksi' => 'GD-0004', 'uang_pinjaman' => 2500000.00, 'tanggal_gadai' => '2026-06-10', 'tanggal_jatuh_tempo' => '2026-07-10', 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR004', 'jumlah_perpanjangan' => 2, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_transaksi_gadai' => 5, 'id_pelanggan' => 5, 'id_barang' => 5, 'id_user' => 5, 'kode_transaksi' => 'GD-0005', 'uang_pinjaman' => 10000000.00, 'tanggal_gadai' => '2026-06-15', 'tanggal_jatuh_tempo' => '2026-07-15', 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR005', 'jumlah_perpanjangan' => 0, 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);

        // 6. t_transaksi_penjualan
        DB::table('t_transaksi_penjualan')->insert([
            ['id_penjualan' => 1, 'id_transaksi_gadai' => 5, 'id_barang' => 5, 'id_user' => 5, 'tanggal_jual' => '2026-08-01', 'harga_jual' => 16000000.00, 'biaya_lain' => 250000.00, 'laba_rugi' => 575000.00, 'catatan' => 'Motor berhasil dijual', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_penjualan' => 2, 'id_transaksi_gadai' => 4, 'id_barang' => 4, 'id_user' => 4, 'tanggal_jual' => '2026-09-20', 'harga_jual' => 5000000.00, 'biaya_lain' => 100000.00, 'laba_rugi' => 2400000.00, 'catatan' => 'TV dijual', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_penjualan' => 3, 'id_transaksi_gadai' => 2, 'id_barang' => 2, 'id_user' => 2, 'tanggal_jual' => '2026-10-10', 'harga_jual' => 9500000.00, 'biaya_lain' => 50000.00, 'laba_rugi' => 2950000.00, 'catatan' => 'iPhone dijual', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_penjualan' => 4, 'id_transaksi_gadai' => 1, 'id_barang' => 1, 'id_user' => 2, 'tanggal_jual' => '2026-08-15', 'harga_jual' => 4800000.00, 'biaya_lain' => 25000.00, 'laba_rugi' => 1775000.00, 'catatan' => 'Cincin dijual', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_penjualan' => 5, 'id_transaksi_gadai' => 3, 'id_barang' => 3, 'id_user' => 3, 'tanggal_jual' => '2026-07-30', 'harga_jual' => 12000000.00, 'biaya_lain' => 150000.00, 'laba_rugi' => 4850000.00, 'catatan' => 'Laptop dijual', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);

        // 7. t_transaksi_perpanjangan
        DB::table('t_transaksi_perpanjangan')->insert([
            ['id_perpanjangan' => 1, 'id_transaksi_gadai' => 2, 'id_user' => 2, 'tanggal_perpanjangan' => '2026-07-03', 'perpanjangan_ke' => 1, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => '2026-07-03', 'jatuh_tempo_sesudah' => '2026-08-03', 'biaya_perpanjangan' => 150000.00, 'catatan' => 'Perpanjangan pertama', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_perpanjangan' => 2, 'id_transaksi_gadai' => 4, 'id_user' => 4, 'tanggal_perpanjangan' => '2026-07-10', 'perpanjangan_ke' => 1, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => '2026-07-10', 'jatuh_tempo_sesudah' => '2026-08-10', 'biaya_perpanjangan' => 120000.00, 'catatan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_perpanjangan' => 3, 'id_transaksi_gadai' => 4, 'id_user' => 4, 'tanggal_perpanjangan' => '2026-08-10', 'perpanjangan_ke' => 2, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => '2026-08-10', 'jatuh_tempo_sesudah' => '2026-09-10', 'biaya_perpanjangan' => 120000.00, 'catatan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_perpanjangan' => 4, 'id_transaksi_gadai' => 2, 'id_user' => 2, 'tanggal_perpanjangan' => '2026-08-03', 'perpanjangan_ke' => 2, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => '2026-08-03', 'jatuh_tempo_sesudah' => '2026-09-03', 'biaya_perpanjangan' => 150000.00, 'catatan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
            ['id_perpanjangan' => 5, 'id_transaksi_gadai' => 2, 'id_user' => 2, 'tanggal_perpanjangan' => '2026-09-03', 'perpanjangan_ke' => 3, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => '2026-09-03', 'jatuh_tempo_sesudah' => '2026-10-03', 'biaya_perpanjangan' => 150000.00, 'catatan' => '', 'created_at' => '2026-06-28 11:25:18', 'updated_at' => '2026-06-28 11:25:18'],
        ]);
    }
}
