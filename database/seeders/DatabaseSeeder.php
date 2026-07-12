<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. users (Tidak diubah sesuai permintaan)
        DB::table('users')->insert([
            ['id_user' => 1, 'nama' => 'Administrator', 'username' => 'admin', 'password' => Hash::make('admin'), 'role' => 'admin', 'status_aktif' => 1, 'created_at' => '2026-06-28 04:25:18', 'updated_at' => '2026-06-28 04:25:18'],
            ['id_user' => 2, 'nama' => 'Budi Santoso', 'username' => '081234567890', 'password' => Hash::make('081234567890'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 04:25:18', 'updated_at' => '2026-06-28 04:25:18'],
            ['id_user' => 3, 'nama' => 'Rina Putri', 'username' => 'rina', 'password' => Hash::make('rina123'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 04:25:18', 'updated_at' => '2026-06-28 04:25:18'],
            ['id_user' => 4, 'nama' => 'Andi Wijaya', 'username' => '081234567892', 'password' => Hash::make('081234567892'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 04:25:18', 'updated_at' => '2026-06-28 04:25:18'],
            ['id_user' => 5, 'nama' => 'Siska Lestari', 'username' => '081234567893', 'password' => Hash::make('081234567893'), 'role' => 'pelanggan', 'status_aktif' => 1, 'created_at' => '2026-06-28 04:25:18', 'updated_at' => '2026-06-28 04:25:18'],
        ]);

        $now = Carbon::now();

        // 2. m_jenis_barang (Diperbanyak & Dirapikan)
        DB::table('m_jenis_barang')->insert([
            ['id_jenis_barang' => 1, 'nama_jenis' => 'Emas & Logam Mulia', 'deskripsi' => 'Perhiasan emas, batangan, berlian', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 2, 'nama_jenis' => 'Smartphone', 'deskripsi' => 'Handphone berbagai merek (Apple, Samsung, dll)', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 3, 'nama_jenis' => 'Komputer & Laptop', 'deskripsi' => 'Laptop, PC Desktop, MacBook', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 4, 'nama_jenis' => 'Elektronik Rumah Tangga', 'deskripsi' => 'Televisi, Kulkas, AC, Mesin Cuci', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 5, 'nama_jenis' => 'Kendaraan Bermotor', 'deskripsi' => 'Sepeda Motor (Honda, Yamaha) dan Mobil', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 6, 'nama_jenis' => 'Kamera & Lensa', 'deskripsi' => 'Kamera DSLR, Mirrorless, Lensa', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 7, 'nama_jenis' => 'Konsol Game', 'deskripsi' => 'PlayStation, Xbox, Nintendo', 'created_at' => $now, 'updated_at' => $now],
            ['id_jenis_barang' => 8, 'nama_jenis' => 'Jam Tangan Mewah', 'deskripsi' => 'Rolex, Seiko, smartwatch premium', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. m_pelanggan (Diperbanyak, sebagian terkait user, sebagian tidak)
        DB::table('m_pelanggan')->insert([
            ['id_pelanggan' => 1, 'id_user' => 2, 'nama' => 'Budi Santoso', 'no_hp' => '081234567890', 'alamat' => 'Jl. Veteran No. 10, Malang', 'email' => 'budi.santoso@gmail.com', 'keterangan' => 'Pelanggan VIP', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 2, 'id_user' => 3, 'nama' => 'Rina Putri', 'no_hp' => '081234567891', 'alamat' => 'Jl. Soekarno Hatta No. 45, Malang', 'email' => 'rina.putri@gmail.com', 'keterangan' => '', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 3, 'id_user' => 4, 'nama' => 'Andi Wijaya', 'no_hp' => '081234567892', 'alamat' => 'Perumahan Sawojajar, Malang', 'email' => 'andi.wijaya@gmail.com', 'keterangan' => 'Sering gadai elektronik', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 4, 'id_user' => 5, 'nama' => 'Siska Lestari', 'no_hp' => '081234567893', 'alamat' => 'Jl. Kawi No. 8, Kepanjen', 'email' => 'siska.lestari@gmail.com', 'keterangan' => '', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 5, 'id_user' => null, 'nama' => 'Eka Pratama', 'no_hp' => '081234567894', 'alamat' => 'Singosari, Kab. Malang', 'email' => 'eka@gmail.com', 'keterangan' => 'Pelanggan offline', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 6, 'id_user' => null, 'nama' => 'Dimas Anggara', 'no_hp' => '082211223344', 'alamat' => 'Jl. MT Haryono No. 12, Malang', 'email' => 'dimas.ang@yahoo.com', 'keterangan' => '', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 7, 'id_user' => null, 'nama' => 'Citra Kirana', 'no_hp' => '085566778899', 'alamat' => 'Blimbing, Malang', 'email' => 'citra.kirana@gmail.com', 'keterangan' => 'Sering gadai emas', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 8, 'id_user' => null, 'nama' => 'Fajar Nugroho', 'no_hp' => '081199887766', 'alamat' => 'Klojen, Malang', 'email' => 'fajar.nugroho@outlook.com', 'keterangan' => '', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 9, 'id_user' => null, 'nama' => 'Gita Savitri', 'no_hp' => '089911223344', 'alamat' => 'Dau, Kab. Malang', 'email' => 'gita.sav@gmail.com', 'keterangan' => 'Mahasiswa', 'created_at' => $now, 'updated_at' => $now],
            ['id_pelanggan' => 10, 'id_user' => null, 'nama' => 'Hendra Setiawan', 'no_hp' => '087755443322', 'alamat' => 'Tlogomas, Malang', 'email' => 'hendra.set@gmail.com', 'keterangan' => '', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. m_barang (Diperbanyak dan lebih bervariasi)
        DB::table('m_barang')->insert([
            ['id_barang' => 1, 'id_pelanggan' => 1, 'id_jenis_barang' => 1, 'nama_barang' => 'Cincin Emas 24K', 'harga_beli' => 4500000.00, 'kondisi' => 'Sangat Baik', 'berat' => 8.50, 'foto_barang' => 'emas1.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Lengkap dengan surat', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 2, 'id_pelanggan' => 2, 'id_jenis_barang' => 2, 'nama_barang' => 'iPhone 13 Pro 256GB', 'harga_beli' => 12000000.00, 'kondisi' => 'Sangat Baik', 'berat' => 0.20, 'foto_barang' => 'iphone13.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Fullset ex iBox', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 3, 'id_pelanggan' => 3, 'id_jenis_barang' => 3, 'nama_barang' => 'ASUS ROG Zephyrus G14', 'harga_beli' => 18000000.00, 'kondisi' => 'Baik', 'berat' => 1.60, 'foto_barang' => 'asus_rog.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Lecet pemakaian', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 4, 'id_pelanggan' => 4, 'id_jenis_barang' => 4, 'nama_barang' => 'TV Samsung Smart TV 43 Inch', 'harga_beli' => 4200000.00, 'kondisi' => 'Baik', 'berat' => 8.50, 'foto_barang' => 'tv.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Tanpa kardus', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 5, 'id_pelanggan' => 5, 'id_jenis_barang' => 5, 'nama_barang' => 'Honda Vario 150 (2022)', 'harga_beli' => 19500000.00, 'kondisi' => 'Sangat Baik', 'berat' => 112.00, 'foto_barang' => 'vario.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'BPKB dan STNK lengkap', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 6, 'id_pelanggan' => 6, 'id_jenis_barang' => 6, 'nama_barang' => 'Kamera Sony A7III Body Only', 'harga_beli' => 22000000.00, 'kondisi' => 'Baik', 'berat' => 0.65, 'foto_barang' => 'sony_a7iii.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'SC rendah, sensor bersih', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 7, 'id_pelanggan' => 7, 'id_jenis_barang' => 7, 'nama_barang' => 'PlayStation 5 Disc Edition', 'harga_beli' => 8500000.00, 'kondisi' => 'Sangat Baik', 'berat' => 4.50, 'foto_barang' => 'ps5.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Lengkap 1 stick dualsense', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 8, 'id_pelanggan' => 8, 'id_jenis_barang' => 1, 'nama_barang' => 'Kalung Emas Putih 18K', 'harga_beli' => 3200000.00, 'kondisi' => 'Kurang Baik', 'berat' => 4.20, 'foto_barang' => 'kalung.jpg', 'status_verifikasi' => 'ditolak', 'harga_gadai_sementara' => null, 'keterangan' => 'Rantai agak patah', 'alasan_penolakan' => 'Kondisi fisik barang rusak, tidak memenuhi standar jaminan.', 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 9, 'id_pelanggan' => 9, 'id_jenis_barang' => 8, 'nama_barang' => 'Apple Watch Series 7', 'harga_beli' => 6000000.00, 'kondisi' => 'Baik', 'berat' => 0.15, 'foto_barang' => 'apple_watch.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Mulus, strap original', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 10, 'id_pelanggan' => 10, 'id_jenis_barang' => 2, 'nama_barang' => 'Samsung Galaxy S22 Ultra', 'harga_beli' => 15000000.00, 'kondisi' => 'Sangat Baik', 'berat' => 0.22, 'foto_barang' => 's22ultra.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Garansi SEIN', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 11, 'id_pelanggan' => 3, 'id_jenis_barang' => 3, 'nama_barang' => 'MacBook Air M1 2020', 'harga_beli' => 13500000.00, 'kondisi' => 'Baik', 'berat' => 1.29, 'foto_barang' => 'macbook_m1.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Battery Health 89%', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 12, 'id_pelanggan' => 1, 'id_jenis_barang' => 1, 'nama_barang' => 'Gelang Emas Kuning 22K', 'harga_beli' => 7800000.00, 'kondisi' => 'Sangat Baik', 'berat' => 12.00, 'foto_barang' => 'gelang_emas.jpg', 'status_verifikasi' => 'pending', 'harga_gadai_sementara' => null, 'keterangan' => 'Baru dibeli 2 bulan lalu', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 13, 'id_pelanggan' => 4, 'id_jenis_barang' => 4, 'nama_barang' => 'Kulkas LG 2 Pintu', 'harga_beli' => 3500000.00, 'kondisi' => 'Baik', 'berat' => 45.00, 'foto_barang' => 'kulkas.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Dingin normal', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 14, 'id_pelanggan' => 7, 'id_jenis_barang' => 5, 'nama_barang' => 'Yamaha NMAX 2021', 'harga_beli' => 28000000.00, 'kondisi' => 'Sangat Baik', 'berat' => 130.00, 'foto_barang' => 'nmax.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Pajak hidup', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 15, 'id_pelanggan' => 2, 'id_jenis_barang' => 1, 'nama_barang' => 'Anting Emas 24K', 'harga_beli' => 2500000.00, 'kondisi' => 'Baik', 'berat' => 3.50, 'foto_barang' => 'anting.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Lengkap', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 16, 'id_pelanggan' => 5, 'id_jenis_barang' => 2, 'nama_barang' => 'Xiaomi 12 Pro', 'harga_beli' => 8000000.00, 'kondisi' => 'Sangat Baik', 'berat' => 0.20, 'foto_barang' => 'xiaomi12.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Mulus', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id_barang' => 17, 'id_pelanggan' => 8, 'id_jenis_barang' => 3, 'nama_barang' => 'Laptop HP Pavilion', 'harga_beli' => 9500000.00, 'kondisi' => 'Baik', 'berat' => 1.80, 'foto_barang' => 'hp_pavilion.jpg', 'status_verifikasi' => 'terverifikasi', 'harga_gadai_sementara' => null, 'keterangan' => 'Lecet sikit', 'alasan_penolakan' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 5. t_transaksi_gadai
        // Simulasi transaksi dengan status yang beragam (aktif, ditebus, dijual)
        DB::table('t_transaksi_gadai')->insert([
            ['id_transaksi_gadai' => 1, 'id_pelanggan' => 1, 'id_barang' => 1, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(3)->format('Ymd').'-001', 'uang_pinjaman' => 3000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(3), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(2), 'status' => 'ditebus', 'tanggal_ditebus' => Carbon::now()->subMonths(2)->addDays(5), 'total_ditebus' => 3150000.00, 'qr_code' => 'QR-GD-001', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(3), 'updated_at' => Carbon::now()->subMonths(2)->addDays(5)],
            
            ['id_transaksi_gadai' => 2, 'id_pelanggan' => 2, 'id_barang' => 2, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(4)->format('Ymd').'-002', 'uang_pinjaman' => 8000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(4), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(3), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-002', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(4), 'updated_at' => Carbon::now()->subMonths(3)->addDays(15)],
            
            ['id_transaksi_gadai' => 3, 'id_pelanggan' => 3, 'id_barang' => 3, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(2)->format('Ymd').'-003', 'uang_pinjaman' => 12000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(2), 'tanggal_jatuh_tempo' => Carbon::now()->addDays(5), 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-003', 'jumlah_perpanjangan' => 2, 'created_at' => Carbon::now()->subMonths(2), 'updated_at' => Carbon::now()->subMonths(1)],
            
            ['id_transaksi_gadai' => 4, 'id_pelanggan' => 4, 'id_barang' => 4, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonth()->format('Ymd').'-004', 'uang_pinjaman' => 2500000.00, 'tanggal_gadai' => Carbon::now()->subMonth(), 'tanggal_jatuh_tempo' => Carbon::now()->subDays(3), 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-004', 'jumlah_perpanjangan' => 1, 'created_at' => Carbon::now()->subMonth(), 'updated_at' => Carbon::now()->subDays(5)],
            
            ['id_transaksi_gadai' => 5, 'id_pelanggan' => 5, 'id_barang' => 5, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subDays(15)->format('Ymd').'-005', 'uang_pinjaman' => 14000000.00, 'tanggal_gadai' => Carbon::now()->subDays(15), 'tanggal_jatuh_tempo' => Carbon::now()->addDays(15), 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-005', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subDays(15), 'updated_at' => Carbon::now()->subDays(15)],
            
            ['id_transaksi_gadai' => 6, 'id_pelanggan' => 6, 'id_barang' => 6, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(2)->format('Ymd').'-006', 'uang_pinjaman' => 15000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(2), 'tanggal_jatuh_tempo' => Carbon::now()->subDays(5), 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-006', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(2), 'updated_at' => Carbon::now()->subMonths(2)],
            
            ['id_transaksi_gadai' => 7, 'id_pelanggan' => 7, 'id_barang' => 7, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(5)->format('Ymd').'-007', 'uang_pinjaman' => 5000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(5), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(4), 'status' => 'ditebus', 'tanggal_ditebus' => Carbon::now()->subMonths(4)->addDays(1), 'total_ditebus' => 5250000.00, 'qr_code' => 'QR-GD-007', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(5), 'updated_at' => Carbon::now()->subMonths(4)->addDays(1)],
            
            ['id_transaksi_gadai' => 8, 'id_pelanggan' => 9, 'id_barang' => 9, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subDays(20)->format('Ymd').'-008', 'uang_pinjaman' => 3500000.00, 'tanggal_gadai' => Carbon::now()->subDays(20), 'tanggal_jatuh_tempo' => Carbon::now()->subDays(7), 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-008', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subDays(20), 'updated_at' => Carbon::now()->subDays(20)],
            
            ['id_transaksi_gadai' => 9, 'id_pelanggan' => 10, 'id_barang' => 10, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(2)->format('Ymd').'-009', 'uang_pinjaman' => 10000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(2), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(1), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-009', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(2), 'updated_at' => Carbon::now()->subDays(10)],

            ['id_transaksi_gadai' => 10, 'id_pelanggan' => 3, 'id_barang' => 11, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(1)->format('Ymd').'-010', 'uang_pinjaman' => 9000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(1), 'tanggal_jatuh_tempo' => Carbon::now()->subDays(2), 'status' => 'aktif', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-010', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(1), 'updated_at' => Carbon::now()->subMonths(1)],

            ['id_transaksi_gadai' => 11, 'id_pelanggan' => 4, 'id_barang' => 13, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(4)->format('Ymd').'-011', 'uang_pinjaman' => 2000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(4), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(3), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-011', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(4), 'updated_at' => Carbon::now()->subMonths(3)->addDays(5)],
            
            ['id_transaksi_gadai' => 12, 'id_pelanggan' => 7, 'id_barang' => 14, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(3)->format('Ymd').'-012', 'uang_pinjaman' => 15000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(3), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(2), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-012', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(3), 'updated_at' => Carbon::now()->subMonths(2)->addDays(2)],
            
            ['id_transaksi_gadai' => 13, 'id_pelanggan' => 2, 'id_barang' => 15, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(6)->format('Ymd').'-013', 'uang_pinjaman' => 1800000.00, 'tanggal_gadai' => Carbon::now()->subMonths(6), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(5), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-013', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(6), 'updated_at' => Carbon::now()->subMonths(5)->addDays(10)],

            ['id_transaksi_gadai' => 14, 'id_pelanggan' => 5, 'id_barang' => 16, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(2)->format('Ymd').'-014', 'uang_pinjaman' => 5000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(2), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(1), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-014', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(2), 'updated_at' => Carbon::now()->subMonths(1)->addDays(3)],

            ['id_transaksi_gadai' => 15, 'id_pelanggan' => 8, 'id_barang' => 17, 'id_user' => 1, 'kode_transaksi' => 'GD-'.Carbon::now()->subMonths(5)->format('Ymd').'-015', 'uang_pinjaman' => 6000000.00, 'tanggal_gadai' => Carbon::now()->subMonths(5), 'tanggal_jatuh_tempo' => Carbon::now()->subMonths(4), 'status' => 'dijual', 'tanggal_ditebus' => null, 'total_ditebus' => null, 'qr_code' => 'QR-GD-015', 'jumlah_perpanjangan' => 0, 'created_at' => Carbon::now()->subMonths(5), 'updated_at' => Carbon::now()->subMonths(4)->addDays(1)],
        ]);

        // 6. t_transaksi_penjualan (Sesuai dengan barang yang statusnya 'dijual')
        DB::table('t_transaksi_penjualan')->insert([
            ['id_penjualan' => 1, 'id_transaksi_gadai' => 2, 'id_barang' => 2, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subMonths(3)->addDays(15), 'harga_jual' => 9500000.00, 'biaya_lain' => 150000.00, 'laba_rugi' => 1350000.00, 'catatan' => 'Dijual karena jatuh tempo dan pelanggan tidak merespon', 'created_at' => Carbon::now()->subMonths(3)->addDays(15), 'updated_at' => Carbon::now()->subMonths(3)->addDays(15)],
            
            ['id_penjualan' => 2, 'id_transaksi_gadai' => 9, 'id_barang' => 10, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subDays(10), 'harga_jual' => 11500000.00, 'biaya_lain' => 200000.00, 'laba_rugi' => 1300000.00, 'catatan' => 'Barang mulus, cepat terjual', 'created_at' => Carbon::now()->subDays(10), 'updated_at' => Carbon::now()->subDays(10)],

            ['id_penjualan' => 3, 'id_transaksi_gadai' => 11, 'id_barang' => 13, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subMonths(2), 'harga_jual' => 2200000.00, 'biaya_lain' => 50000.00, 'laba_rugi' => 150000.00, 'catatan' => 'Lelang borongan', 'created_at' => Carbon::now()->subMonths(2), 'updated_at' => Carbon::now()->subMonths(2)],
            
            ['id_penjualan' => 4, 'id_transaksi_gadai' => 12, 'id_barang' => 14, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subMonth(), 'harga_jual' => 16500000.00, 'biaya_lain' => 300000.00, 'laba_rugi' => 1200000.00, 'catatan' => 'Terjual ke dealer', 'created_at' => Carbon::now()->subMonth(), 'updated_at' => Carbon::now()->subMonth()],
            
            ['id_penjualan' => 5, 'id_transaksi_gadai' => 13, 'id_barang' => 15, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subMonths(4), 'harga_jual' => 2000000.00, 'biaya_lain' => 50000.00, 'laba_rugi' => 150000.00, 'catatan' => 'Terjual perorangan', 'created_at' => Carbon::now()->subMonths(4), 'updated_at' => Carbon::now()->subMonths(4)],

            ['id_penjualan' => 6, 'id_transaksi_gadai' => 14, 'id_barang' => 16, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subDays(15), 'harga_jual' => 5500000.00, 'biaya_lain' => 100000.00, 'laba_rugi' => 400000.00, 'catatan' => 'Laku di marketplace', 'created_at' => Carbon::now()->subDays(15), 'updated_at' => Carbon::now()->subDays(15)],

            ['id_penjualan' => 7, 'id_transaksi_gadai' => 15, 'id_barang' => 17, 'id_user' => 1, 'tanggal_jual' => Carbon::now()->subMonths(3), 'harga_jual' => 6300000.00, 'biaya_lain' => 150000.00, 'laba_rugi' => 150000.00, 'catatan' => 'Terjual ke mahasiswa', 'created_at' => Carbon::now()->subMonths(3), 'updated_at' => Carbon::now()->subMonths(3)],
        ]);

        // 7. t_transaksi_perpanjangan (Terkait transaksi yang memiliki perpanjangan)
        DB::table('t_transaksi_perpanjangan')->insert([
            // Transaksi 3 memiliki 2 perpanjangan
            ['id_perpanjangan' => 1, 'id_transaksi_gadai' => 3, 'id_user' => 1, 'tanggal_perpanjangan' => Carbon::now()->subMonth(), 'perpanjangan_ke' => 1, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => Carbon::now()->subMonth(), 'jatuh_tempo_sesudah' => Carbon::now(), 'biaya_perpanjangan' => 600000.00, 'catatan' => 'Perpanjangan pertama', 'created_at' => Carbon::now()->subMonth(), 'updated_at' => Carbon::now()->subMonth()],
            ['id_perpanjangan' => 2, 'id_transaksi_gadai' => 3, 'id_user' => 1, 'tanggal_perpanjangan' => Carbon::now(), 'perpanjangan_ke' => 2, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => Carbon::now(), 'jatuh_tempo_sesudah' => Carbon::now()->addMonth(), 'biaya_perpanjangan' => 600000.00, 'catatan' => 'Perpanjangan kedua', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Transaksi 4 memiliki 1 perpanjangan
            ['id_perpanjangan' => 3, 'id_transaksi_gadai' => 4, 'id_user' => 1, 'tanggal_perpanjangan' => Carbon::now()->subDays(5), 'perpanjangan_ke' => 1, 'tambahan_bulan' => 1, 'jatuh_tempo_sebelum' => Carbon::now()->subDays(5), 'jatuh_tempo_sesudah' => Carbon::now()->addDays(25), 'biaya_perpanjangan' => 125000.00, 'catatan' => 'Perpanjangan bulanan via transfer', 'created_at' => Carbon::now()->subDays(5), 'updated_at' => Carbon::now()->subDays(5)],
        ]);
        // 8. Call PembayaranOnlineSeeder
        $this->call([
            PembayaranOnlineSeeder::class,
        ]);
    }
}

