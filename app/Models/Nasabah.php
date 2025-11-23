<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'foto_ktp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekenings()
    {
        return $this->hasMany(Rekening::class);
    }

    public function kredits()
    {
        return $this->hasMany(Kredit::class);
    }

    public function depositos()
    {
        return $this->hasMany(Deposito::class);
    }
}
