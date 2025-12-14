<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    // ==================== Relationships ====================

    public function pemeriksaan(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    public function latestPemeriksaan(): HasOne
    {
        return $this->hasOne(Pemeriksaan::class)->latestOfMany('tanggal_pemeriksaan');
    }

    // ==================== Accessors ====================

    /**
     * Get dynamic age from birth date or stored value
     */
    public function getUmurAttribute($value): ?int
    {
        // Always calculate from birth date if available for accuracy
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->age;
        }

        return $value ? (int) $value : null;
    }

    // ==================== Query Scopes ====================

    /**
     * Filter by gender
     */
    public function scopeGender(Builder $query, string $gender): Builder
    {
        return $query->where('jenis_kelamin', $gender);
    }

    /**
     * Filter by age range
     */
    public function scopeAgeRange(Builder $query, ?int $min = null, ?int $max = null): Builder
    {
        if (!is_null($min)) {
            $query->where('umur', '>=', $min);
        }

        if (!is_null($max)) {
            $query->where('umur', '<=', $max);
        }

        return $query;
    }

    /**
     * Search by name, code, or NIK
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
                ->orWhere('kode', 'like', "%{$keyword}%")
                ->orWhere('nik', 'like', "%{$keyword}%");
        });
    }
}

