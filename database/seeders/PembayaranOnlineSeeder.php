<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranOnlineSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('t_pembayaran_online')->insert([
            [
                'id_pembayaran' => 1,
                'id_transaksi_gadai' => 4, // Transaksi aktif
                'jenis_pembayaran' => 'perpanjangan',
                'jumlah_bulan' => 1,
                'nominal_bayar' => 125000.00,
                'bukti_pembayaran' => 'https://via.placeholder.com/400x600.png?text=Bukti+Transfer+Perpanjangan',
                'status' => 'menunggu_konfirmasi',
                'catatan_admin' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pembayaran' => 2,
                'id_transaksi_gadai' => 5, // Transaksi aktif
                'jenis_pembayaran' => 'tebus',
                'jumlah_bulan' => null,
                'nominal_bayar' => 14000000.00,
                'bukti_pembayaran' => 'https://via.placeholder.com/400x600.png?text=Bukti+Transfer+Tebus',
                'status' => 'menunggu_konfirmasi',
                'catatan_admin' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_pembayaran' => 3,
                'id_transaksi_gadai' => 8, // Transaksi aktif
                'jenis_pembayaran' => 'tebus',
                'jumlah_bulan' => null,
                'nominal_bayar' => 3500000.00,
                'bukti_pembayaran' => 'https://via.placeholder.com/400x600.png?text=Bukti+Palsu',
                'status' => 'ditolak',
                'catatan_admin' => 'Bukti transfer blur dan tidak valid, harap upload ulang',
                'created_at' => $now->subDays(2),
                'updated_at' => $now->subDays(1),
            ]
        ]);
    }
}
