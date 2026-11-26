<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sekolah extends Model
{
    protected $fillable = [
        'npsn',
        'nama',
        'jenjang',
        'status',
        'alamat',
        'kecamatan',
        'telepon',
        'latitude',
        'longitude',
    ];

    public function profil(): HasOne
    {
        return $this->hasOne(ProfilSekolah::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
