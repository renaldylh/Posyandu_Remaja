@extends('admin.app')

@section('title', 'Daftar Pemeriksaan')

@section('content')
<div class="card shadow-sm p-3">
    <div class="d-flex mb-3 gap-2">
        <select class="form-select w-25">
            <option selected>Dusun</option>
        </select>
        <select class="form-select w-25">
            <option selected>RW</option>
        </select>
        <select class="form-select w-25">
            <option selected>RT</option>
        </select>
        <button class="btn btn-success">Download Rekap</button>
    </div>

    <h5>Data Hasil Pemeriksaan</h5>

    <div class="d-flex mb-3 gap-2 flex-wrap">
        <select class="form-select w-25"><option>Status Gizi</option></select>
        <select class="form-select w-25"><option>Status Anemia</option></select>
        <select class="form-select w-25"><option>Tekanan Darah</option></select>
        <select class="form-select w-25"><option>Gula Darah</option></select>
        <select class="form-select w-25"><option>Status Darah</option></select>
        <select class="form-select w-25"><option>Bulan</option></select>
        <select class="form-select w-25"><option>Tahun</option></select>
        <input type="text" class="form-control w-25" placeholder="Cari Peserta...">
        <button class="btn btn-warning">Reset</button>
    </div>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-success">
            <tr>
                <th>No</th>
                <th>Tanggal Periksa</th>
                <th>No Peserta</th>
                <th>Nama</th>
                <th>Tinggi Badan</th>
                <th>Berat Badan</th>
                <th>Lingkar Lengan Atas</th>
                <th>Lingkar Perut</th>
                <th>Tekanan Darah</th>
                <th>Gula Darah</th>
                <th>Hemoglobin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Minggu, 17 Agustus 2024 09:00</td>
                <td>PS0000001</td>
                <td>Mafa</td>
                <td>166</td>
                <td>60</td>
                <td>28</td>
                <td>48</td>
                <td>110/80</td>
                <td>169</td>
                <td>14</td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button class="btn btn-secondary">Batal</button>
        <button class="btn btn-success">Tambah Data</button>
    </div>
</div>
@endsection
