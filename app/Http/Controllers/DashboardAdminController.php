<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Peserta;
use App\Models\Petugas;
use App\Models\User;
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

        $totalBidan = User::where('role', 'BIDAN')->count();
        $totalAdmin = User::where('role', 'ADMIN')->count();
        $totalKader = User::where('role', 'KADER')->count();

        $pemeriksaanHariIni = Pemeriksaan::whereDate('tanggal_pemeriksaan', $now->toDateString())->count();
        $pemeriksaanBulanIni = Pemeriksaan::whereYear('tanggal_pemeriksaan', $now->year)
            ->whereMonth('tanggal_pemeriksaan', $now->month)
            ->count();
        $pemeriksaanTahunIni = Pemeriksaan::whereYear('tanggal_pemeriksaan', $now->year)->count();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'totalLaki',
            'totalPerempuan',
            'totalBidan',
            'totalAdmin',
            'totalKader',
            'pemeriksaanHariIni',
            'pemeriksaanBulanIni',
            'pemeriksaanTahunIni'
        ));
    }
}
