@extends('admin.app')

@section('title', 'Ubah Petugas')

@section('content')
<div class="card shadow-sm p-3">
    <h5 class="mb-3">Ubah Petugas</h5>

    <form action="{{ route('admin.petugas.update', $petugas) }}" method="POST" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $petugas->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $petugas->email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Posisi</label>
            <input type="text" name="posisi" value="{{ old('posisi', $petugas->posisi) }}" class="form-control @error('posisi') is-invalid @enderror" required>
            @error('posisi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">No. Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon', $petugas->telepon) }}" class="form-control @error('telepon') is-invalid @enderror">
            @error('telepon')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $petugas->alamat) }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
