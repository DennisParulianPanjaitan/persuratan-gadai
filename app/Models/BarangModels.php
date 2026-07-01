<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisBarangModels;
use App\Models\TransaksiGadaiModels;
use App\Models\TransaksiPenjualanModels;

class BarangModels extends Model
{
    use HasFactory;

    protected $table = 'm_barang';

    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'id_jenis_barang',
        'id_pelanggan',
        'nama_barang',
        'harga_beli',
        'kondisi',
        'berat',
        'foto_barang',
        'status_verifikasi',
        'harga_gadai_sementara',
        'keterangan',
        'alasan_penolakan'
    ];

    public function getRouteKeyName()
    {
        return 'id_barang';
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarangModels::class, 'id_jenis_barang');
    }

    public function pelanggan()
    {
        return $this->belongsTo(\App\Models\PelangganModels::class, 'id_pelanggan');
    }

    public function transaksiGadai()
    {
        return $this->hasMany(TransaksiGadaiModels::class, 'id_barang');
    }

    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualanModels::class, 'id_barang');
    }
}
