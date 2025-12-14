<?php

namespace App\Http\Controllers;

use App\Exports\PemeriksaanExport;
use App\Http\Requests\StorePemeriksaanRequest;
use App\Http\Requests\UpdatePemeriksaanRequest;
use App\Models\Pemeriksaan;
use App\Models\Peserta;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemeriksaanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pemeriksaan::with(['peserta.latestPemeriksaan', 'peserta']);

        $this->applyFilters($query, $request);

        $pemeriksaan = $query->latest('tanggal_pemeriksaan')->paginate(10)->appends($request->query());
        $filters = $request->only(['search', 'jenis_kelamin', 'umur_min', 'umur_max', 'tanggal_mulai', 'tanggal_selesai']);

        return view('admin.pemeriksaan.index', compact('pemeriksaan', 'filters'));
    }

    public function export(Request $request)
    {
        $filename = 'rekap_pemeriksaan_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PemeriksaanExport($this->getExportFilters($request)), $filename);
    }

    public function exportPdf(Request $request)
    {
        $data = (new PemeriksaanExport($this->getExportFilters($request)))->collection();

        $html = view('admin.pemeriksaan.export_pdf', compact('data'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'rekap_pemeriksaan_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(
            fn() => print($dompdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    public function create(): View
    {
        $peserta = Peserta::orderBy('nama')->pluck('nama', 'id');

        return view('admin.pemeriksaan.create', compact('peserta'));
    }

    public function store(StorePemeriksaanRequest $request): RedirectResponse
    {
        Pemeriksaan::create($request->validated());

        return redirect()->route('admin.pemeriksaan.index')->with('status', 'Data pemeriksaan berhasil ditambahkan.');
    }

    public function edit(Pemeriksaan $pemeriksaan): View
    {
        $peserta = Peserta::orderBy('nama')->pluck('nama', 'id');

        return view('admin.pemeriksaan.edit', compact('pemeriksaan', 'peserta'));
    }

    public function update(UpdatePemeriksaanRequest $request, Pemeriksaan $pemeriksaan): RedirectResponse
    {
        $pemeriksaan->update($request->validated());

        return redirect()->route('admin.pemeriksaan.index')->with('status', 'Data pemeriksaan berhasil diperbarui.');
    }

    public function destroy(Pemeriksaan $pemeriksaan): RedirectResponse
    {
        $pemeriksaan->delete();

        return redirect()->route('admin.pemeriksaan.index')->with('status', 'Data pemeriksaan berhasil dihapus.');
    }

    /**
     * Apply search and filter conditions to query
     */
    private function applyFilters($query, Request $request): void
    {
        if ($search = $request->query('search')) {
            $query->whereHas('peserta', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($gender = $request->query('jenis_kelamin')) {
            $query->whereHas('peserta', fn($q) => $q->where('jenis_kelamin', $gender));
        }

        if ($request->filled('umur_min')) {
            $query->whereHas('peserta', fn($q) => $q->where('umur', '>=', (int)$request->query('umur_min')));
        }

        if ($request->filled('umur_max')) {
            $query->whereHas('peserta', fn($q) => $q->where('umur', '<=', (int)$request->query('umur_max')));
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pemeriksaan', '>=', $request->query('tanggal_mulai'));
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pemeriksaan', '<=', $request->query('tanggal_selesai'));
        }
    }

    /**
     * Get filter parameters for export
     */
    private function getExportFilters(Request $request): array
    {
        return $request->only([
            'search',
            'jenis_kelamin',
            'umur_min',
            'umur_max',
            'tanggal_mulai',
            'tanggal_selesai',
        ]);
    }
}

