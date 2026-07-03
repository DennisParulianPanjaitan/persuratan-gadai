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
                'id_transaksi_gadai' => 2, // Asumsi id_transaksi_gadai 2 masih aktif
                'jenis_pembayaran' => 'perpanjangan',
                'jumlah_bulan' => 1,
                'nominal_bayar' => 450000.00,
                'bukti_pembayaran' => 'https://via.placeholder.com/400x600.png?text=Bukti+Transfer',
                'status' => 'menunggu_konfirmasi',
                'catatan_admin' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
