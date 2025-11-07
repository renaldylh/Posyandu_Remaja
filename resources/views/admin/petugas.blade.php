@extends('admin.app')

@section('title', 'Daftar Petugas')

@section('content')
<div class="card shadow-sm p-3">
    <div class="d-flex mb-3 gap-2">
        <input type="text" class="form-control w-25" placeholder="Cari Petugas...">
        <select class="form-select w-25">
            <option selected>Posisi</option>
            <option>Admin</option>
            <option>Perugas Pencatatan</option>
        </select>
        <button class="btn btn-success">RISET</button>
    </div>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-success">
            <tr>
                <th>Aksi</th>
                <th>No</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Posisi</th>
                <th>No. Telepon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></button>
                </td>
                <td>1</td>
                <td>mafa49@gmail.com</td>
                <td>Mafa</td>
                <td>Admin</td>
                <td>085611526198</td>
                <td>Ds. Kuta No. 310, Kec. Belik Pemalang</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
