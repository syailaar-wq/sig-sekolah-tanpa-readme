<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sekolah | SIG Sekolah</title>

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

        header h1 {
            margin: 0 0 8px;
        }

        header p {
            margin: 0;
        }

        main {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #15803d;
            color: white;
        }

        .btn-map {
            background: #2563eb;
            color: white;
        }

        .search-form {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            min-width: 220px;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-search {
            background: #166534;
            color: white;
        }

        .btn-reset {
            background: #64748b;
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            overflow-x: auto;
            box-shadow: 0 2px 8px #0000000d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        th {
            background: #f8fafc;
        }

        .alert {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

        .action {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-edit {
            background: #facc15;
            color: #422006;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
        }

        .pagination-info {
            margin-top: 18px;
            color: #64748b;
            font-size: 14px;
        }

        .pagination {
            margin-top: 15px;
        }

        .pagination nav div:first-child {
            margin-bottom: 10px;
        }

        .pagination nav span,
        .pagination nav a {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid #cbd5e1;
            color: #166534;
            background: white;
        }

        .pagination nav span[aria-current="page"] {
            background: #166534;
            color: white;
            border-color: #166534;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #64748b;
        }
    </style>
</head>

<body>

    <header>
        <h1>SIG Sekolah</h1>
        <p>Sistem Informasi Geografis Pemetaan Sekolah</p>
    </header>

    <main>

        <div class="toolbar">
            <div>
                <h2>Data Sekolah</h2>
                <p>Kelola informasi dan lokasi sekolah.</p>
            </div>

            <div class="action">
                <a href="{{ route('dinas.sekolah.create') }}"
                   class="btn btn-primary">
                    + Tambah Sekolah
                </a>

                <a href="{{ route('peta.index') }}"
                   class="btn btn-map">
                    Lihat Peta
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <form action="{{ route('dinas.sekolah.index') }}"
              method="GET"
              class="search-form">

            <input
                type="text"
                name="q"
                placeholder="Cari nama sekolah, NPSN, atau kecamatan..."
                value="{{ request('q') }}"
            >

            <button type="submit" class="btn btn-search">
                Cari
            </button>

            <a href="{{ route('dinas.sekolah.index') }}"
               class="btn btn-reset">
                Reset
            </a>

        </form>

        <div class="table-container">

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Sekolah</th>
                        <th>NPSN</th>
                        <th>Jenjang</th>
                        <th>Status</th>
                        <th>Kecamatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sekolahs as $sekolah)
                        <tr>
                            <td>{{ $sekolahs->firstItem() + $loop->index }}</td>
                            <td>{{ $sekolah->nama }}</td>
                            <td>{{ $sekolah->npsn }}</td>
                            <td>{{ $sekolah->jenjang }}</td>
                            <td>{{ $sekolah->status }}</td>
                            <td>{{ $sekolah->kecamatan }}</td>

                            <td>
                                <div class="action">

                                    <a href="{{ route('sekolah.edit', $sekolah->id) }}"
                                       class="btn btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('dinas.sekolah.destroy', $sekolah->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="empty">
                                Data sekolah tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-info">
                Menampilkan {{ $sekolahs->firstItem() ?? 0 }}
                sampai {{ $sekolahs->lastItem() ?? 0 }}
                dari {{ $sekolahs->total() }} data sekolah.
            </div>

            <div class="pagination">
                {{ $sekolahs->links() }}
            </div>

        </div>

    </main>

    <footer>
        SIG Sekolah &copy; {{ date('Y') }}
    </footer>

</body>
</html>
