<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sekolah | SIG Sekolah</title>

    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        header {
            background: #166534;
            color: white;
            padding: 22px 7%;
        }

        main {
            max-width: 800px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #0000000d;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        input[readonly] {
            background: #e2e8f0;
            cursor: not-allowed;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        #map-picker {
            width: 100%;
            height: 350px;
            border-radius: 8px;
            margin-top: 10px;
            z-index: 1;
        }

        .map-info {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-save {
            background: #15803d;
            color: white;
        }

        .btn-back {
            background: #64748b;
            color: white;
        }
    </style>
</head>

<body>

<header>
    <h1>SIG Sekolah</h1>
    <p>Sistem Informasi Geografis Pemetaan Sekolah</p>
</header>

<main>
    <h2>Edit Data Sekolah</h2>
    @include('sekolah._tab', ['sekolah' => $sekolah])

    <div class="form-container">

        <form action="{{ route('sekolah.update', $sekolah->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>NPSN</label>
                <input type="text" name="npsn" maxlength="8"
                       value="{{ old('npsn', $sekolah->npsn) }}"
                       @readonly(auth()->user()->isSekolah())
                       required>
                @error('npsn')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="nama"
                       value="{{ old('nama', $sekolah->nama) }}" required>
                @error('nama')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Jenjang</label>
                <select name="jenjang" required>
                    <option value="">-- Pilih Jenjang --</option>
                    @foreach (['SD', 'SMP', 'SMA', 'SMK'] as $jenjang)
                        <option value="{{ $jenjang }}"
                            {{ old('jenjang', $sekolah->jenjang) == $jenjang ? 'selected' : '' }}>
                            {{ $jenjang }}
                        </option>
                    @endforeach
                </select>
                @error('jenjang')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Status Sekolah</label>
                <select name="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Negeri"
                        {{ old('status', $sekolah->status) == 'Negeri' ? 'selected' : '' }}>
                        Negeri
                    </option>
                    <option value="Swasta"
                        {{ old('status', $sekolah->status) == 'Swasta' ? 'selected' : '' }}>
                        Swasta
                    </option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="3" required>{{ old('alamat', $sekolah->alamat) }}</textarea>
                @error('alamat')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Kecamatan</label>
                <input type="text" name="kecamatan"
                       value="{{ old('kecamatan', $sekolah->kecamatan) }}" required>
                @error('kecamatan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="telepon"
                       value="{{ old('telepon', $sekolah->telepon) }}">
                @error('telepon')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Pilih Lokasi Sekolah pada Peta</label>
                <div id="map-picker"></div>
                <p class="map-info">
                    Klik peta atau geser marker untuk mengubah lokasi sekolah.
                </p>
            </div>

            <div class="form-group">
                <label>Latitude</label>
                <input type="number" step="any" name="latitude"
                       id="latitude"
                       value="{{ old('latitude', $sekolah->latitude) }}" required>
                @error('latitude')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Longitude</label>
                <input type="number" step="any" name="longitude"
                       id="longitude"
                       value="{{ old('longitude', $sekolah->longitude) }}" required>
                @error('longitude')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-save">
                Perbarui Data
            </button>

            <a href="{{ auth()->user()->isDinas()
                        ? route('dinas.sekolah.index')
                        : route('dashboard') }}"
               class="btn btn-back">
                Batal
            </a>

        </form>
    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    // Mengambil koordinat sekolah yang sedang diedit
    let initialLatitude = parseFloat(latitudeInput.value);
    let initialLongitude = parseFloat(longitudeInput.value);

    // Koordinat cadangan jika data koordinat kosong atau tidak valid
    if (
        !Number.isFinite(initialLatitude) ||
        !Number.isFinite(initialLongitude) ||
        initialLatitude < -90 || initialLatitude > 90 ||
        initialLongitude < -180 || initialLongitude > 180
    ) {
        initialLatitude = -3.3194;
        initialLongitude = 114.5900;

        latitudeInput.value = initialLatitude;
        longitudeInput.value = initialLongitude;
    }

    // Membuat peta
    const map = L.map('map-picker').setView(
        [initialLatitude, initialLongitude],
        15
    );

    // Menampilkan peta OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Membuat marker yang bisa digeser
    const marker = L.marker(
        [initialLatitude, initialLongitude],
        { draggable: true }
    ).addTo(map);

    marker.bindPopup('Lokasi sekolah yang sedang diedit').openPopup();

    // Memperbarui koordinat pada input
    function updateCoordinates(latitude, longitude) {
        latitudeInput.value = latitude.toFixed(7);
        longitudeInput.value = longitude.toFixed(7);
    }

    // Jika peta diklik, pindahkan marker
    map.on('click', function (event) {
        marker.setLatLng(event.latlng);

        updateCoordinates(
            event.latlng.lat,
            event.latlng.lng
        );
    });

    // Jika marker digeser, perbarui koordinat
    marker.on('dragend', function () {
        const position = marker.getLatLng();

        updateCoordinates(
            position.lat,
            position.lng
        );
    });

    // Jika latitude atau longitude diubah manual
    function updateMarkerFromInput() {
        const latitude = parseFloat(latitudeInput.value);
        const longitude = parseFloat(longitudeInput.value);

        if (
            Number.isFinite(latitude) &&
            Number.isFinite(longitude) &&
            latitude >= -90 && latitude <= 90 &&
            longitude >= -180 && longitude <= 180
        ) {
            marker.setLatLng([latitude, longitude]);
            map.panTo([latitude, longitude]);
        }
    }

    latitudeInput.addEventListener('change', updateMarkerFromInput);
    longitudeInput.addEventListener('change', updateMarkerFromInput);

    // Memastikan ukuran peta ditampilkan dengan benar
    setTimeout(function () {
        map.invalidateSize();
    }, 300);

});
</script>

</body>
</html>
