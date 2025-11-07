<?php

namespace App\Http\Controllers;

use App\Exports\PemeriksaanExport;
use App\Models\Pemeriksaan;
use App\Models\Peserta;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemeriksaanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pemeriksaan::with(['peserta.latestPemeriksaan', 'peserta']);

        if ($search = $request->query('search')) {
            $query->whereHas('peserta', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($gender = $request->query('jenis_kelamin')) {
            $query->whereHas('peserta', fn ($q) => $q->where('jenis_kelamin', $gender));
        }

        if ($request->filled('umur_min')) {
            $query->whereHas('peserta', fn ($q) => $q->where('umur', '>=', (int)$request->query('umur_min')));
        }

        if ($request->filled('umur_max')) {
            $query->whereHas('peserta', fn ($q) => $q->where('umur', '<=', (int)$request->query('umur_max')));
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->query('tanggal_mulai'));
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->query('tanggal_selesai'));
        }

        $pemeriksaan = $query->latest('tanggal_pemeriksaan')->paginate(10)->appends($request->query());

        $filters = $request->only(['search', 'jenis_kelamin', 'umur_min', 'umur_max', 'tanggal_mulai', 'tanggal_selesai']);

        return view('admin.pemeriksaan.index', compact('pemeriksaan', 'filters'));
    }

    public function export(Request $request)
    {
        $filename = 'rekap_pemeriksaan_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PemeriksaanExport($request->only([
            'search',
            'jenis_kelamin',
            'umur_min',
            'umur_max',
            'tanggal_mulai',
            'tanggal_selesai',
        ])), $filename);
    }

    public function create(): View
    {
        $peserta = Peserta::orderBy('nama')->pluck('nama', 'id');

        return view('admin.pemeriksaan.create', compact('peserta'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'peserta_id' => ['required', 'exists:peserta,id'],
            'tanggal_pemeriksaan' => ['required', 'date_format:Y-m-d\TH:i'],
            'tinggi_badan' => ['required', 'numeric', 'min:0'],
            'berat_badan' => ['required', 'numeric', 'min:0'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'hemoglobin' => ['nullable', 'numeric', 'min:0'],
            'gula_darah' => ['nullable', 'numeric', 'min:0'],
            'lingkar_lengan' => ['nullable', 'numeric', 'min:0'],
        ]);

        Pemeriksaan::create($validated);

        return redirect()->route('admin.pemeriksaan.index')->with('status', 'Data pemeriksaan berhasil ditambahkan.');
    }

    public function edit(Pemeriksaan $pemeriksaan): View
    {
        $peserta = Peserta::orderBy('nama')->pluck('nama', 'id');

        return view('admin.pemeriksaan.edit', compact('pemeriksaan', 'peserta'));
    }

    public function update(Request $request, Pemeriksaan $pemeriksaan): RedirectResponse
    {
        $validated = $request->validate([
            'peserta_id' => ['required', 'exists:peserta,id'],
            'tanggal_pemeriksaan' => ['required', 'date_format:Y-m-d\TH:i'],
            'tinggi_badan' => ['required', 'numeric', 'min:0'],
            'berat_badan' => ['required', 'numeric', 'min:0'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'hemoglobin' => ['nullable', 'numeric', 'min:0'],
            'gula_darah' => ['nullable', 'numeric', 'min:0'],
            'lingkar_lengan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $pemeriksaan->update($validated);

        return redirect()->route('admin.pemeriksaan.index')->with('status', 'Data pemeriksaan berhasil diperbarui.');
    }

    public function destroy(Pemeriksaan $pemeriksaan): RedirectResponse
    {
        $pemeriksaan->delete();

        return redirect()->route('admin.pemeriksaan.index')->with('status', 'Data pemeriksaan berhasil dihapus.');
    }
}
