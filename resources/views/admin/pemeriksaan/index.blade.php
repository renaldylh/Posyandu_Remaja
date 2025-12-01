@extends('admin.app')

@section('title', 'Daftar Pemeriksaan')

@section('content')
<div class="card shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <h5 class="mb-0">Daftar Pemeriksaan</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#downloadModal">
                <i class="bi bi-download"></i> Download Rekap Full
            </button>
            <a href="{{ route('admin.pemeriksaan.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Pemeriksaan
            </a>
        </div>
    </div>

    <!-- Download Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="downloadModalLabel">Pilih Format Download</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pemeriksaan.export', array_filter($filters ?? [])) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Download Excel
                        </a>
                        <a href="{{ route('admin.pemeriksaan.export-pdf', array_filter($filters ?? [])) }}" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="card card-body mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cari Nama / Kode / NIK</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Kata kunci">
            </div>
            <div class="col-md-2">
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
                <input type="number" name="umur_max" value="{{ $filters['umur_max'] ?? '' }}" min="0" max="120" class="form-control" placeholder="19">
            </div>
            <div class="col-md-3">
                <label class="form-label">Rentang Tanggal Pemeriksaan</label>
                <div class="input-group">
                    <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="form-control">
                    <span class="input-group-text">s/d</span>
                    <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="form-control">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm text-center">Filter</button>
                <a href="{{ route('admin.pemeriksaan.index') }}" class="btn btn-outline-secondary btn-sm text-center" title="Reset filter">
                    Reset
                </a>
                <button type="button" class="btn btn-outline-danger btn-sm text-center" onclick="downloadFilteredPDF()">
                    <i class="bi bi-file-earmark-pdf"></i> Download Filter (PDF)
                </button>
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
                    <th>Tanggal Periksa</th>
                    <th>No Peserta</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Kelamin</th>
                    <th>Usia</th>
                    <th>Tinggi Badan (cm)</th>
                    <th>Berat Badan (kg)</th>
                    <th>Tekanan Darah</th>
                    <th>Hemoglobin</th>
                    <th>Gula Darah</th>
                    <th>Lingkar Lengan (cm)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pemeriksaan as $index => $row)
                    @php
                        $umur = optional($row->peserta)->umur;
                        $rowClass = ($umur < 10 && $umur > 19) ? 'table-warning' : '';
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.pemeriksaan.edit', $row) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.pemeriksaan.destroy', $row) }}" method="POST" onsubmit="return confirm('Hapus data pemeriksaan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $pemeriksaan->firstItem() + $index }}</td>
                        <td>{{ optional($row->tanggal_pemeriksaan)->translatedFormat('l, d F Y H:i') ?? '-' }}</td>
                        <td>{{ optional($row->peserta)->kode ?? '-' }}</td>
                        <td>{{ optional($row->peserta)->nama ?? '-' }}</td>
                        <td>{{ optional($row->peserta)->jenis_kelamin ?? '-' }}</td>
                        <td>{{ optional($row->peserta)->umur ? optional($row->peserta)->umur . ' th' : '-' }}</td>
                        <td>{{ number_format($row->tinggi_badan, 2) }}</td>
                        <td>{{ number_format($row->berat_badan, 2) }}</td>
                        <td>{{ $row->tekanan_darah ?? '-' }}</td>
                        <td>{{ $row->hemoglobin !== null ? number_format($row->hemoglobin, 2) : '-' }}</td>
                        <td>{{ $row->gula_darah !== null ? number_format($row->gula_darah, 2) : '-' }}</td>
                        <td>{{ $row->lingkar_lengan !== null ? number_format($row->lingkar_lengan, 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">Belum ada data pemeriksaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $pemeriksaan->links() }}
    </div>
</div>

<script>
function downloadFilteredPDF() {
    const form = document.querySelector('form[method="GET"]');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Add all filter parameters
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    // Redirect to PDF export with filter parameters
    window.location.href = `{{ route('admin.pemeriksaan.export-pdf') }}?${params.toString()}`;
}
</script>
@endsection
