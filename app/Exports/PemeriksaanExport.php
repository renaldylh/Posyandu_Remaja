<?php

namespace App\Exports;

use App\Models\Pemeriksaan;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PemeriksaanExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    protected array $filters;
    protected int $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = Pemeriksaan::with('peserta');

        if ($search = Arr::get($this->filters, 'search')) {
            $query->whereHas('peserta', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($gender = Arr::get($this->filters, 'jenis_kelamin')) {
            $query->whereHas('peserta', fn ($q) => $q->where('jenis_kelamin', $gender));
        }

        if ($umurMin = Arr::get($this->filters, 'umur_min')) {
            $query->whereHas('peserta', fn ($q) => $q->where('umur', '>=', (int)$umurMin));
        }

        if ($umurMax = Arr::get($this->filters, 'umur_max')) {
            $query->whereHas('peserta', fn ($q) => $q->where('umur', '<=', (int)$umurMax));
        }

        if ($tanggalMulai = Arr::get($this->filters, 'tanggal_mulai')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai = Arr::get($this->filters, 'tanggal_selesai')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $tanggalSelesai);
        }

        return $query->latest('tanggal_pemeriksaan')->get();
    }

    public function map($pemeriksaan): array
    {
        $this->rowNumber++;

        $peserta = $pemeriksaan->peserta;

        return [
            $this->rowNumber,
            optional($pemeriksaan->tanggal_pemeriksaan)->format('Y-m-d H:i'),
            optional($peserta)->kode,
            optional($peserta)->nama,
            optional($peserta)->jenis_kelamin,
            optional($peserta)->umur,
            $pemeriksaan->tinggi_badan,
            $pemeriksaan->berat_badan,
            $pemeriksaan->tekanan_darah,
            $pemeriksaan->hemoglobin,
            $pemeriksaan->gula_darah,
            $pemeriksaan->lingkar_lengan,
            optional($peserta)->nik,
            optional($peserta)->alamat,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Periksa',
            'No Peserta',
            'Nama Peserta',
            'Jenis Kelamin',
            'Umur',
            'Tinggi Badan (cm)',
            'Berat Badan (kg)',
            'Tekanan Darah',
            'Hemoglobin',
            'Gula Darah',
            'Lingkar Lengan (cm)',
            'NIK',
            'Alamat',
        ];
    }
}
