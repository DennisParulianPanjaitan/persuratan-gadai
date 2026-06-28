<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangModels;

class JenisBarangModels extends Model
{
    use HasFactory;

    protected $table = 'm_jenis_barang';

    protected $primaryKey = 'id_jenis_barang';

    protected $fillable = [
        'nama_jenis',
        'deskripsi'
    ];

    public function barang()
    {
        return $this->hasMany(BarangModels::class, 'id_jenis_barang');
    }
}
