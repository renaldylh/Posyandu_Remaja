<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    // ==================== Relationships ====================

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    // ==================== Query Scopes ====================

    /**
     * Filter by date range
     */
    public function scopeDateRange(Builder $query, ?string $startDate = null, ?string $endDate = null): Builder
    {
        if ($startDate) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Filter by today
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('tanggal_pemeriksaan', now()->toDateString());
    }

    /**
     * Filter by current month
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereYear('tanggal_pemeriksaan', now()->year)
            ->whereMonth('tanggal_pemeriksaan', now()->month);
    }

    /**
     * Filter by current year
     */
    public function scopeThisYear(Builder $query): Builder
    {
        return $query->whereYear('tanggal_pemeriksaan', now()->year);
    }
}

