<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePesertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pesertaId = $this->route('peserta')?->id;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'umur' => ['nullable', 'integer', 'min:0', 'max:120'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'nik' => ['required', 'string', 'max:20', Rule::unique('peserta', 'nik')->ignore($pesertaId)],
            'alamat' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama peserta wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
        ];
    }
}
