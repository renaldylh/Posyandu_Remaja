@extends('admin.app')

@section('title', 'Daftar Peserta')

@section('content')
<div class="card shadow-sm p-3">
    <div class="d-flex mb-3 gap-2">
        <input type="text" class="form-control w-25" placeholder="Cari Nama...">
        <select class="form-select w-25">
            <option selected>Dusun</option>
            <option>Dusun 1</option>
            <option>Dusun 2</option>
        </select>
        <select class="form-select w-25">
            <option selected>RW</option>
            <option>01</option>
            <option>02</option>
        </select>
        <select class="form-select w-25">
            <option selected>RT</option>
            <option>01</option>
            <option>02</option>
        </select>
        <button class="btn btn-success">RISET</button>
    </div>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-success">
            <tr>
                <th>Aksi</th>
                <th>No Peserta</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th>NIK</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></button>
                </td>
                <td>PS0000001</td>
                <td>Mafa</td>
                <td>04/10/2000</td>
                <td>25</td>
                <td>32781004100000001</td>
                <td>Ds. Kuta No. 310, Kec. Belik Pemalang</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
