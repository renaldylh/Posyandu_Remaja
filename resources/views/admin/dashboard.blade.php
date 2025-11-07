@extends('admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4">

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill text-success card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">DAFTAR PESERTA</h6>
                    <h4>{{ $totalPeserta }} Peserta</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-gender-male text-primary card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL LAKI - LAKI</h6>
                    <h4>{{ $totalLaki }} Peserta</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-gender-female text-warning card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PEREMPUAN</h6>
                    <h4>{{ $totalPerempuan }} Peserta</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-lines-fill text-danger card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PETUGAS PENDAFTARAN</h6>
                    <h4>{{ $totalPendaftaran }} Petugas</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-clipboard-check text-purple card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PETUGAS PENCATATAN</h6>
                    <h4>{{ $totalPencatatan }} Petugas</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-bar-chart-line text-indigo card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PETUGAS PELAPORAN</h6>
                    <h4>{{ $totalPelaporan }} Petugas</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar-day text-success card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PEMERIKSAAN HARI INI</h6>
                    <h4>{{ $pemeriksaanHariIni }} Pemeriksaan</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar-month text-brown card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PEMERIKSAAN BULAN INI</h6>
                    <h4>{{ $pemeriksaanBulanIni }} Pemeriksaan</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar3 text-pink card-icon me-3"></i>
                <div>
                    <h6 class="mb-1">TOTAL PEMERIKSAAN TAHUN INI</h6>
                    <h4>{{ $pemeriksaanTahunIni }} Pemeriksaan</h4>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
