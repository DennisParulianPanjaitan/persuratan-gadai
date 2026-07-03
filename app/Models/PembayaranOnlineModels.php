<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranOnlineModels extends Model
{
    use HasFactory;

    protected $table = 't_pembayaran_online';
    protected $primaryKey = 'id_pembayaran';
    
    protected $fillable = [
        'id_transaksi_gadai',
        'jenis_pembayaran',
        'jumlah_bulan',
        'nominal_bayar',
        'bukti_pembayaran',
        'status',
        'catatan_admin'
    ];

    public function transaksiGadai(): BelongsTo
    {
        return $this->belongsTo(TransaksiGadaiModels::class, 'id_transaksi_gadai', 'id_transaksi_gadai');
    }
}
