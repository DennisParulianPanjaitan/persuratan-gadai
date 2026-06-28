<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModels;
use App\Models\PelangganModels;
use App\Models\TransaksiPenjualanModels;
use App\Models\TransaksiPerpanjanganModels;
use App\Models\UserModels;

class TransaksiGadaiModels extends Model
{
    use HasFactory;

    protected $table = 't_transaksi_gadai';

    protected $primaryKey = 'id_transaksi_gadai';

    protected $fillable = [
        'id_pelanggan',
        'id_barang',
        'id_user',
        'kode_transaksi',
        'uang_pinjaman',
        'tanggal_gadai',
        'tanggal_jatuh_tempo',
        'status',
        'tanggal_ditebus',
        'total_ditebus',
        'qr_code',
        'jumlah_perpanjangan'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(PelangganModels::class, 'id_pelanggan');
    }

    public function barang()
    {
        return $this->belongsTo(BarangModels::class, 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(UserModels::class, 'id_user');
    }

    public function perpanjangan()
    {
        return $this->hasMany(TransaksiPerpanjanganModels::class, 'id_transaksi_gadai');
    }

    public function penjualan()
    {
        return $this->hasOne(TransaksiPenjualanModels::class, 'id_transaksi_gadai');
    }
}
