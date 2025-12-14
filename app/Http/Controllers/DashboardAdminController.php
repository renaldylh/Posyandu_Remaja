<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardAdminController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();

        // Optimized: Single query for peserta stats using groupBy
        $pesertaStats = Peserta::selectRaw('jenis_kelamin, COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $totalPeserta = $pesertaStats->sum();
        $totalLaki = $pesertaStats->get('Laki-laki', 0);
        $totalPerempuan = $pesertaStats->get('Perempuan', 0);

        // Optimized: Single query for user stats using groupBy
        $userStats = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $totalBidan = $userStats->get('BIDAN', 0);
        $totalAdmin = $userStats->get('ADMIN', 0);
        $totalKader = $userStats->get('KADER', 0);

        // Optimized: Single query for pemeriksaan stats using conditional counts
        $pemeriksaanStats = Pemeriksaan::selectRaw("
            SUM(CASE WHEN DATE(tanggal_pemeriksaan) = ? THEN 1 ELSE 0 END) as hari_ini,
            SUM(CASE WHEN YEAR(tanggal_pemeriksaan) = ? AND MONTH(tanggal_pemeriksaan) = ? THEN 1 ELSE 0 END) as bulan_ini,
            SUM(CASE WHEN YEAR(tanggal_pemeriksaan) = ? THEN 1 ELSE 0 END) as tahun_ini
        ", [$now->toDateString(), $now->year, $now->month, $now->year])
            ->first();

        $pemeriksaanHariIni = (int) ($pemeriksaanStats->hari_ini ?? 0);
        $pemeriksaanBulanIni = (int) ($pemeriksaanStats->bulan_ini ?? 0);
        $pemeriksaanTahunIni = (int) ($pemeriksaanStats->tahun_ini ?? 0);

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
