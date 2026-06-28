<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiGadaiModels;
use App\Models\UserModels;

class TransaksiPerpanjanganModels extends Model
{
    use HasFactory;

    protected $table = 't_transaksi_perpanjangan';

    protected $primaryKey = 'id_perpanjangan';

    protected $fillable = [
        'id_transaksi_gadai',
        'id_user',
        'tanggal_perpanjangan',
        'perpanjangan_ke',
        'tambahan_bulan',
        'jatuh_tempo_sebelum',
        'jatuh_tempo_sesudah',
        'biaya_perpanjangan',
        'catatan'
    ];

    public function transaksiGadai()
    {
        return $this->belongsTo(TransaksiGadaiModels::class, 'id_transaksi_gadai');
    }

    public function user()
    {
        return $this->belongsTo(UserModels::class, 'id_user');
    }
}
