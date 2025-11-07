@extends('admin.app')

@section('title', 'Daftar Peserta')

@section('content')
<div class="card shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <h5 class="mb-0">Daftar Peserta</h5>
        <a href="{{ route('admin.peserta.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Peserta
        </a>
    </div>

    <form method="GET" class="card card-body mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cari Nama / Kode / NIK</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Kata kunci">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select">
                    <option value="">Semua</option>
                    <option value="Laki-laki" @selected(($filters['jenis_kelamin'] ?? '') === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(($filters['jenis_kelamin'] ?? '') === 'Perempuan')>Perempuan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Umur Min</label>
                <input type="number" name="umur_min" value="{{ $filters['umur_min'] ?? '' }}" min="0" max="120" class="form-control" placeholder="0">
            </div>
            <div class="col-md-2">
                <label class="form-label">Umur Max</label>
                <input type="number" name="umur_max" value="{{ $filters['umur_max'] ?? '' }}" min="0" max="120" class="form-control" placeholder="120">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.peserta.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                    Reset
                </a>
            </div>
        </div>
    </form>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-success">
                <tr>
                    <th>Aksi</th>
                    <th>No</th>
                    <th>No Peserta</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Umur</th>
                    <th>NIK</th>
                    <th>Total Pemeriksaan</th>
                    <th>Pemeriksaan Terakhir</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peserta as $index => $row)
                    <tr>
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.peserta.edit', $row) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.peserta.destroy', $row) }}" method="POST" onsubmit="return confirm('Hapus peserta ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $peserta->firstItem() + $index }}</td>
                        <td>{{ $row->kode }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ optional($row->tanggal_lahir)->translatedFormat('d F Y') ?? '-' }}</td>
                        <td>{{ $row->umur ? $row->umur . ' th' : '-' }}</td>
                        <td>{{ $row->nik }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $row->pemeriksaan_count ?? 0 }}</span>
                            @if(($row->pemeriksaan_count ?? 0) > 0)
                                <div class="small text-muted mt-1">Total</div>
                            @endif
                        </td>
                        <td>
                            @if($row->latestPemeriksaan)
                                <div>{{ $row->latestPemeriksaan->tanggal_pemeriksaan?->translatedFormat('d F Y H:i') }}</div>
                                <div class="small text-muted">
                                    Tekanan Darah: {{ $row->latestPemeriksaan->tekanan_darah ?? '-' }}<br>
                                    Hemoglobin: {{ $row->latestPemeriksaan->hemoglobin ?? '-' }}<br>
                                    Gula Darah: {{ $row->latestPemeriksaan->gula_darah ?? '-' }}
                                </div>
                            @else
                                <span class="text-muted">Belum ada data</span>
                            @endif
                        </td>
                        <td>{{ $row->alamat }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">Belum ada data peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $peserta->links() }}
    </div>
</div>
@endsection
