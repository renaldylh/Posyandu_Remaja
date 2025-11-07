<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index(): View
    {
        $petugas = Petugas::latest()->paginate(10);

        return view('admin.petugas.index', compact('petugas'));
    }

    public function create(): View
    {
        return view('admin.petugas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:petugas,email'],
            'posisi' => ['required', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        Petugas::create($validated);

        return redirect()->route('admin.petugas.index')->with('status', 'Petugas berhasil ditambahkan.');
    }

    public function edit(Petugas $petugas): View
    {
        return view('admin.petugas.edit', compact('petugas'));
    }

    public function update(Request $request, Petugas $petugas): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('petugas')->ignore($petugas->id)],
            'posisi' => ['required', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $petugas->update($validated);

        return redirect()->route('admin.petugas.index')->with('status', 'Petugas berhasil diperbarui.');
    }

    public function destroy(Petugas $petugas): RedirectResponse
    {
        $petugas->delete();

        return redirect()->route('admin.petugas.index')->with('status', 'Petugas berhasil dihapus.');
    }
}
