<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Carbon\Carbon;

class PesertaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Peserta::query();

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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'umur' => ['nullable', 'integer', 'min:0', 'max:120'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'nik' => ['required', 'string', 'max:20', 'unique:peserta,nik'],
            'alamat' => ['nullable', 'string'],
        ]);

        $validated['umur'] = $this->resolveUmur($validated['umur'] ?? null, $validated['tanggal_lahir'] ?? null);
        $validated['kode'] = $this->generateKode();

        Peserta::create($validated);

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil ditambahkan.');
    }

    public function edit(Peserta $peserta): View
    {
        return view('admin.peserta.edit', compact('peserta'));
    }

    public function update(Request $request, Peserta $peserta): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'umur' => ['nullable', 'integer', 'min:0', 'max:120'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'nik' => ['required', 'string', 'max:20', Rule::unique('peserta', 'nik')->ignore($peserta->id)],
            'alamat' => ['nullable', 'string'],
        ]);

        $validated['umur'] = $this->resolveUmur($validated['umur'] ?? null, $validated['tanggal_lahir'] ?? null);
        $peserta->update($validated);

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil diperbarui.');
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        $peserta->delete();

        return redirect()->route('admin.peserta.index')->with('status', 'Peserta berhasil dihapus.');
    }

    private function generateKode(): string
    {
        $latest = Peserta::orderByDesc('id')->first();
        $latestNumber = $latest && $latest->kode ? (int)substr($latest->kode, 2) : 0;
        $number = $latestNumber + 1;

        return 'PS' . str_pad((string)$number, 7, '0', STR_PAD_LEFT);
    }

    private function resolveUmur(?int $umur, ?string $tanggalLahir): ?int
    {
        if (!is_null($umur)) {
            return $umur;
        }

        if ($tanggalLahir) {
            return Carbon::parse($tanggalLahir)->age;
        }

        return null;
    }
}
