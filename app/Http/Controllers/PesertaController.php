<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesertaRequest;
use App\Http\Requests\UpdatePesertaRequest;
use App\Models\Peserta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Peserta::query();

        $this->applyFilters($query, $request);

        $query->withCount('pemeriksaan')
            ->with(['latestPemeriksaan']);

        $peserta = $query->latest()->paginate(10)->appends($request->query());
        $filters = $request->only(['search', 'jenis_kelamin', 'umur_min', 'umur_max']);

        return view('admin.peserta.index', compact('peserta', 'filters'));
    }

    public function create(): View
    {
        return view('admin.peserta.create');
    }

    public function store(StorePesertaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['umur'] = $this->calculateUmur($validated['umur'] ?? null, $validated['tanggal_lahir'] ?? null);
        $validated['kode'] = $this->generateKode();

        Peserta::create($validated);

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil ditambahkan.');
    }

    public function edit(Peserta $peserta): View
    {
        return view('admin.peserta.edit', compact('peserta'));
    }

    public function update(UpdatePesertaRequest $request, Peserta $peserta): RedirectResponse
    {
        $validated = $request->validated();
        $validated['umur'] = $this->calculateUmur($validated['umur'] ?? null, $validated['tanggal_lahir'] ?? null);

        $peserta->update($validated);

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil diperbarui.');
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        $peserta->delete();

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil dihapus.');
    }

    /**
     * Apply search and filter conditions to query
     */
    private function applyFilters($query, Request $request): void
    {
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($gender = $request->query('jenis_kelamin')) {
            $query->where('jenis_kelamin', $gender);
        }

        if ($request->filled('umur_min')) {
            $query->where('umur', '>=', (int)$request->query('umur_min'));
        }

        if ($request->filled('umur_max')) {
            $query->where('umur', '<=', (int)$request->query('umur_max'));
        }
    }

    /**
     * Generate unique participant code
     */
    private function generateKode(): string
    {
        $latest = Peserta::orderByDesc('id')->first();
        $latestNumber = $latest && $latest->kode ? (int)substr($latest->kode, 2) : 0;

        return 'PS' . str_pad((string)($latestNumber + 1), 7, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate age from birth date or return provided age
     */
    private function calculateUmur(?int $umur, ?string $tanggalLahir): ?int
    {
        if (!is_null($umur)) {
            return $umur;
        }

        return $tanggalLahir ? Carbon::parse($tanggalLahir)->age : null;
    }
}

