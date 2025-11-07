@extends('admin.app')

@section('title', 'Ubah Peserta')

@section('content')
<div class="card shadow-sm p-3">
    <h5 class="mb-3">Ubah Peserta</h5>

    <form action="{{ route('admin.peserta.update', $peserta) }}" method="POST" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $peserta->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($peserta->tanggal_lahir)->format('Y-m-d')) }}" class="form-control @error('tanggal_lahir') is-invalid @enderror">
            @error('tanggal_lahir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Umur</label>
            <input type="number" name="umur" value="{{ old('umur', $peserta->umur) }}" class="form-control @error('umur') is-invalid @enderror" min="0" max="120" placeholder="Otomatis dari tanggal lahir jika kosong">
            @error('umur')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" @selected(old('jenis_kelamin', $peserta->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
                <option value="Perempuan" @selected(old('jenis_kelamin', $peserta->jenis_kelamin) === 'Perempuan')>Perempuan</option>
            </select>
            @error('jenis_kelamin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $peserta->nik) }}" class="form-control @error('nik') is-invalid @enderror" required>
            @error('nik')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $peserta->alamat) }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('admin.peserta.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
