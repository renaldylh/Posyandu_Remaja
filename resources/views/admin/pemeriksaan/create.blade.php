@extends('admin.app')

@section('title', 'Tambah Pemeriksaan')

@section('content')
<div class="card shadow-sm p-3">
    <h5 class="mb-3">Tambah Pemeriksaan</h5>

    <form action="{{ route('admin.pemeriksaan.store') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">Peserta</label>
            <select name="peserta_id" class="form-select @error('peserta_id') is-invalid @enderror" required>
                <option value="">Pilih Peserta</option>
                @foreach ($peserta as $id => $nama)
                    <option value="{{ $id }}" @selected(old('peserta_id') == $id)>{{ $nama }}</option>
                @endforeach
            </select>
            @error('peserta_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Tanggal Pemeriksaan</label>
            <input type="datetime-local" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan') }}" class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror" required>
            @error('tanggal_pemeriksaan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tinggi Badan (cm)</label>
            <input type="number" step="0.01" name="tinggi_badan" value="{{ old('tinggi_badan') }}" class="form-control @error('tinggi_badan') is-invalid @enderror" min="0" required>
            @error('tinggi_badan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Berat Badan (kg)</label>
            <input type="number" step="0.01" name="berat_badan" value="{{ old('berat_badan') }}" class="form-control @error('berat_badan') is-invalid @enderror" min="0" required>
            @error('berat_badan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Lingkar Lengan (cm)</label>
            <input type="number" step="0.01" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="form-control @error('lingkar_lengan') is-invalid @enderror" min="0">
            @error('lingkar_lengan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tekanan Darah</label>
            <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}" class="form-control @error('tekanan_darah') is-invalid @enderror" placeholder="Contoh: 120/80">
            @error('tekanan_darah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Gula Darah</label>
            <input type="number" step="0.01" name="gula_darah" value="{{ old('gula_darah') }}" class="form-control @error('gula_darah') is-invalid @enderror" min="0">
            @error('gula_darah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Hemoglobin</label>
            <input type="number" step="0.01" name="hemoglobin" value="{{ old('hemoglobin') }}" class="form-control @error('hemoglobin') is-invalid @enderror" min="0">
            @error('hemoglobin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('admin.pemeriksaan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent negative values on all number inputs
    const numericInputs = document.querySelectorAll('input[type="number"]');
    
    numericInputs.forEach(function(input) {
        // Block minus key
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'e' || e.key === 'E') {
                e.preventDefault();
            }
        });

        // Validate and correct value on input/change
        ['input', 'change', 'blur'].forEach(function(eventType) {
            input.addEventListener(eventType, function(e) {
                let val = parseFloat(e.target.value);
                if (!isNaN(val) && val < 0) {
                    e.target.value = 0;
                }
            });
        });
    });
});
</script>
@endpush
