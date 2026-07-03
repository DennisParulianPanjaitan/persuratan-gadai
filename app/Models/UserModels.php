<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\TransaksiGadaiModels;
use App\Models\TransaksiPenjualanModels;
use App\Models\TransaksiPerpanjanganModels;

class UserModels extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'status_aktif',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function transaksiGadai()
    {
        return $this->hasMany(TransaksiGadaiModels::class, 'id_user');
    }

    public function transaksiPerpanjangan()
    {
        return $this->hasMany(TransaksiPerpanjanganModels::class, 'id_user');
    }

    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualanModels::class, 'id_user');
    }
}
