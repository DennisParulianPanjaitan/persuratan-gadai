<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModels;
use App\Models\TransaksiGadaiModels;
use App\Models\UserModels;

class TransaksiPenjualanModels extends Model
{
    use HasFactory;

    protected $table = 't_transaksi_penjualan';

    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'id_transaksi_gadai',
        'id_barang',
        'id_user',
        'tanggal_jual',
        'harga_jual',
        'biaya_lain',
        'laba_rugi',
        'catatan'
    ];

    public function transaksiGadai()
    {
        return $this->belongsTo(TransaksiGadaiModels::class, 'id_transaksi_gadai');
    }

    public function barang()
    {
        return $this->belongsTo(BarangModels::class, 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(UserModels::class, 'id_user');
    }
}
