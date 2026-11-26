<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use App\Support\Geometri;

class PetaController extends Controller
{
    public function index()
    {
        return view('peta.index');
    }

    public function cari()
    {
        return view('peta.cari');
    }

    public function panas()
    {
        return view('peta.panas');
    }

    public function klaster()
    {
        return view('peta.klaster');
    }

    public function wilayah()
    {
        return view('peta.wilayah');
    }

    public function rute()
    {
        return view('peta.rute');
    }
    public function alamat()
{
    return view('peta.alamat');
}

    public function geojson(Request $request)
    {
        $sekolahs = Sekolah::query()
            ->when($request->jenjang, function ($query, $jenjang) {
                $query->where('jenjang', $jenjang);
            })
            ->get();

        $features = $sekolahs->map(function ($sekolah) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $sekolah->longitude,
                        (float) $sekolah->latitude,
                    ],
                ],
                'properties' => [
                    'npsn' => $sekolah->npsn,
                    'nama' => $sekolah->nama,
                    'jenjang' => $sekolah->jenjang,
                    'status' => $sekolah->status,
                    'alamat' => $sekolah->alamat,
                    'kecamatan' => $sekolah->kecamatan,
                    'telepon' => $sekolah->telepon,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function geojsonWilayah(Request $request)
    {
        $features = Sekolah::with('profil')
            ->when($request->jenjang, fn ($q, $j) => $q->where('jenjang', $j))
            ->get()
            ->groupBy('kecamatan')
            ->map(function ($kelompok, $kecamatan) {
                $titik = $kelompok
                    ->map(fn ($s) => [$s->longitude, $s->latitude])
                    ->all();

                $hull = Geometri::convexHull($titik);

                if (count($hull) < 3) {
                    return null;
                }

                $hull[] = $hull[0];

                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$hull],
                    ],
                    'properties' => [
                        'kecamatan' => $kecamatan,
                        'jumlah_sekolah' => $kelompok->count(),
                        'jumlah_siswa' => (int) $kelompok->sum(
                            fn ($s) => $s->profil?->jumlah_siswa ?? 0
                        ),
                    ],
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
