<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sekolah | SIG Sekolah</title>

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
    <h2>Tambah Data Sekolah</h2>

    <div class="form-container">

        <form action="{{ route('dinas.sekolah.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>NPSN</label>
                <input type="text" name="npsn" maxlength="8"
                       value="{{ old('npsn') }}" required>
                @error('npsn')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="nama"
                       value="{{ old('nama') }}" required>
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
                            {{ old('jenjang') == $jenjang ? 'selected' : '' }}>
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
                        {{ old('status') == 'Negeri' ? 'selected' : '' }}>
                        Negeri
                    </option>
                    <option value="Swasta"
                        {{ old('status') == 'Swasta' ? 'selected' : '' }}>
                        Swasta
                    </option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Kecamatan</label>
                <input type="text" name="kecamatan"
                       value="{{ old('kecamatan') }}" required>
                @error('kecamatan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Telepon</label>
                <input type="text" name="telepon"
                       value="{{ old('telepon') }}">
                @error('telepon')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Pilih Lokasi Sekolah pada Peta</label>
                <div id="map-picker"></div>
                <p class="map-info">
                    Klik pada peta atau geser marker untuk menentukan lokasi sekolah.
                </p>
            </div>

            <div class="form-group">
                <label>Latitude</label>
                <input type="number" step="any" name="latitude"
                       id="latitude" value="{{ old('latitude') }}" required>
                @error('latitude')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Longitude</label>
                <input type="number" step="any" name="longitude"
                       id="longitude" value="{{ old('longitude') }}" required>
                @error('longitude')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-save">
                Simpan Data
            </button>

            <a href="{{ route('dinas.sekolah.index') }}"
               class="btn btn-back">
                Kembali
            </a>

        </form>

    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const defaultLatitude = -3.3194;
    const defaultLongitude = 114.5900;

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    const initialLatitude = parseFloat(latitudeInput.value) || defaultLatitude;
    const initialLongitude = parseFloat(longitudeInput.value) || defaultLongitude;

    const map = L.map('map-picker').setView(
        [initialLatitude, initialLongitude], 13
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker(
        [initialLatitude, initialLongitude],
        { draggable: true }
    ).addTo(map);

    function updateCoordinates(latitude, longitude) {
        latitudeInput.value = latitude.toFixed(7);
        longitudeInput.value = longitude.toFixed(7);
    }

    map.on('click', function (event) {
        marker.setLatLng(event.latlng);

        updateCoordinates(
            event.latlng.lat,
            event.latlng.lng
        );
    });

    marker.on('dragend', function () {
        const position = marker.getLatLng();

        updateCoordinates(
            position.lat,
            position.lng
        );
    });

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

    updateCoordinates(initialLatitude, initialLongitude);
</script>

</body>
</html>
