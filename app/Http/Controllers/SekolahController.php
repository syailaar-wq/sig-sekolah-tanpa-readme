<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SekolahController extends Controller
{
    // Menampilkan daftar sekolah
    public function index(Request $request)
    {
        $sekolahs = Sekolah::query()
    ->when($request->q, function ($query, $q) {
        $query->where(function ($w) use ($q) {
            $w->whereLike('nama', "%{$q}%")
              ->orWhereLike('npsn', "%{$q}%")
              ->orWhereLike('kecamatan', "%{$q}%");
        });
    })
    ->orderBy('nama')
    ->paginate(10)
    ->withQueryString();
    }

    // Menampilkan form tambah sekolah
    public function create()
    {
        return view('sekolah.create');
    }

    // Menyimpan data sekolah baru
    public function store(Request $request)
    {
        Sekolah::create($request->validate($this->rules()));

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    // Menampilkan form edit sekolah
    public function edit(Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        return view('sekolah.edit', compact('sekolah'));
    }

    // Memperbarui data sekolah
    public function update(Request $request, Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        $data = $request->validate($this->rules($sekolah->id));

        // NPSN tidak boleh diubah oleh operator sekolah
        if ($request->user()->isSekolah()) {
            unset($data['npsn']);
        }

        $sekolah->update($data);

        return $this->kembali($request)
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    // Menghapus data sekolah
    public function destroy(Sekolah $sekolah)
    {
        $sekolah->delete();

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }

    // Mengarahkan pengguna kembali sesuai role
    private function kembali(Request $request)
    {
        return $request->user()->isDinas()
            ? redirect()->route('dinas.sekolah.index')
            : redirect()->route('dashboard');
    }

    // Aturan validasi data sekolah
    private function rules(?int $id = null): array
    {
        return [
            'npsn' => [
                'required',
                'digits:8',
                Rule::unique('sekolahs', 'npsn')->ignore($id),
            ],

            'nama' => ['required', 'string', 'max:255'],

            'jenjang' => [
                'required',
                Rule::in(['SD', 'SMP', 'SMA', 'SMK']),
            ],

            'status' => [
                'required',
                Rule::in(['Negeri', 'Swasta']),
            ],

            'alamat' => ['required', 'string'],

            'kecamatan' => ['required', 'string', 'max:100'],

            'telepon' => ['nullable', 'string', 'max:20'],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
        ];
    }
}
