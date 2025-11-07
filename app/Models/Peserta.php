<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'kode',
        'nama',
        'tanggal_lahir',
        'umur',
        'jenis_kelamin',
        'nik',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'umur' => 'integer',
    ];

    public function pemeriksaan(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    public function latestPemeriksaan(): HasOne
    {
        return $this->hasOne(Pemeriksaan::class)->latestOfMany('tanggal_pemeriksaan');
    }

    public function getUmurAttribute($value): ?int
    {
        if (!is_null($value)) {
            return (int) $value;
        }

        return $this->tanggal_lahir?->age;
    }
}
