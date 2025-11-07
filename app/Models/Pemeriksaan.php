<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';

    protected $fillable = [
        'peserta_id',
        'tanggal_pemeriksaan',
        'tinggi_badan',
        'berat_badan',
        'tekanan_darah',
        'gula_darah',
        'hemoglobin',
        'lingkar_lengan',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'datetime',
        'tinggi_badan' => 'float',
        'berat_badan' => 'float',
        'gula_darah' => 'float',
        'hemoglobin' => 'float',
        'lingkar_lengan' => 'float',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }
}
