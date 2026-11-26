@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')

<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-1">
        {{ $sekolah->nama }}
    </h1>

    <p class="text-gray-600 mb-4">
        NPSN: {{ $sekolah->npsn }}
    </p>

    @include('sekolah._tab', ['sekolah' => $sekolah])

    <h2 class="text-xl font-bold mb-4">
        Form Profil Sekolah
    </h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @php
        $fields = [
            'kepala_sekolah' => ['Kepala Sekolah', 'text'],
            'email' => ['Email Sekolah', 'email'],
            'website' => ['Website', 'url'],
            'tahun_berdiri' => ['Tahun Berdiri', 'number'],
            'jumlah_siswa' => ['Jumlah Siswa', 'number'],
            'jumlah_guru' => ['Jumlah Guru', 'number'],
        ];

        $inputClass = 'w-full border rounded px-3 py-2';
    @endphp

    <form method="POST"
          action="{{ route('sekolah.profil.update', $sekolah) }}"
          class="bg-white rounded shadow p-6 grid md:grid-cols-2 gap-5">

        @csrf
        @method('PUT')

        @foreach ($fields as $name => $field)
            <div>
                <label class="block font-medium mb-1" for="{{ $name }}">
                    {{ $field[0] }}
                </label>

                <input
                    type="{{ $field[1] }}"
                    id="{{ $name }}"
                    name="{{ $name }}"
                    value="{{ old($name, $profil->$name) }}"
                    class="{{ $inputClass }}"
                >

                @error($name)
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <div>
            <label class="block font-medium mb-1" for="akreditasi">
                Akreditasi
            </label>

            <select name="akreditasi" id="akreditasi" class="{{ $inputClass }}">
                <option value="">-- Pilih Akreditasi --</option>

                @foreach (['A', 'B', 'C', 'Belum Terakreditasi'] as $pilihan)
                    <option value="{{ $pilihan }}"
                        @selected(old('akreditasi', $profil->akreditasi) === $pilihan)>
                        {{ $pilihan }}
                    </option>
                @endforeach
            </select>

            @error('akreditasi')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block font-medium mb-1" for="deskripsi">
                Deskripsi Sekolah
            </label>

            <textarea
                name="deskripsi"
                id="deskripsi"
                rows="5"
                class="{{ $inputClass }}"
            >{{ old('deskripsi', $profil->deskripsi) }}</textarea>

            @error('deskripsi')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <button type="submit"
                    class="bg-blue-700 text-white px-5 py-2 rounded">
                Simpan Profil
            </button>
        </div>

    </form>

</div>

@endsection
