<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemeriksaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peserta_id' => ['required', 'exists:peserta,id'],
            'tanggal_pemeriksaan' => ['required', 'date_format:Y-m-d\TH:i'],
            'tinggi_badan' => ['required', 'numeric', 'min:0'],
            'berat_badan' => ['required', 'numeric', 'min:0'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'hemoglobin' => ['nullable', 'numeric', 'min:0'],
            'gula_darah' => ['nullable', 'numeric', 'min:0'],
            'lingkar_lengan' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'peserta_id.required' => 'Peserta wajib dipilih.',
            'peserta_id.exists' => 'Peserta tidak ditemukan.',
            'tanggal_pemeriksaan.required' => 'Tanggal pemeriksaan wajib diisi.',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'berat_badan.required' => 'Berat badan wajib diisi.',
        ];
    }
}
