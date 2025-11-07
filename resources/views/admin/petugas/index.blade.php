@extends('admin.app')

@section('title', 'Daftar Petugas')

@section('content')
<div class="card shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0">Daftar Petugas</h5>
        <a href="{{ route('admin.petugas.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Petugas
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-success">
                <tr>
                    <th>Aksi</th>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Posisi</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($petugas as $index => $row)
                    <tr>
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.petugas.edit', $row) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.petugas.destroy', $row) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td>{{ $petugas->firstItem() + $index }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->posisi }}</td>
                        <td>{{ $row->telepon ?: '-' }}</td>
                        <td>{{ $row->alamat ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Belum ada data petugas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $petugas->links() }}
    </div>
</div>
@endsection
