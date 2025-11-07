<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Peserta;
use App\Models\Petugas;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardAdminController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();

        $totalPeserta = Peserta::count();
        $totalLaki = Peserta::where('jenis_kelamin', 'Laki-laki')->count();
        $totalPerempuan = Peserta::where('jenis_kelamin', 'Perempuan')->count();

        $totalPendaftaran = Petugas::where('posisi', 'Pendaftaran')->count();
        $totalPencatatan = Petugas::where('posisi', 'Pencatatan')->count();
        $totalPelaporan = Petugas::where('posisi', 'Pelaporan')->count();

        $pemeriksaanHariIni = Pemeriksaan::whereDate('tanggal_pemeriksaan', $now->toDateString())->count();
        $pemeriksaanBulanIni = Pemeriksaan::whereYear('tanggal_pemeriksaan', $now->year)
            ->whereMonth('tanggal_pemeriksaan', $now->month)
            ->count();
        $pemeriksaanTahunIni = Pemeriksaan::whereYear('tanggal_pemeriksaan', $now->year)->count();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'totalLaki',
            'totalPerempuan',
            'totalPendaftaran',
            'totalPencatatan',
            'totalPelaporan',
            'pemeriksaanHariIni',
            'pemeriksaanBulanIni',
            'pemeriksaanTahunIni'
        ));
    }
}
