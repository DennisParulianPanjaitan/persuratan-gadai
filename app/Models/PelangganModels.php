<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiGadaiModels;

class PelangganModels extends Model
{
    use HasFactory;

    protected $table = 'm_pelanggan';

    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'id_user',
        'nama',
        'no_hp',
        'alamat',
        'email',
        'keterangan'
    ];

    public function transaksiGadai()
    {
        return $this->hasMany(TransaksiGadaiModels::class, 'id_pelanggan');
    }

    public function user()
    {
        return $this->belongsTo(UserModels::class, 'id_user', 'id_user');
    }
}
