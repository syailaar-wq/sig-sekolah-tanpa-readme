@php
    $cls = 'w-full border rounded px-3 py-2';
@endphp

<div class="space-y-4">

    {{-- Nama --}}
    <div>
        <label class="block font-medium mb-1">Nama Lengkap</label>
        <input type="text"
               name="name"
               value="{{ old('name', $user->name) }}"
               class="{{ $cls }}"
               required>

        @error('name')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label class="block font-medium mb-1">Email</label>
        <input type="email"
               name="email"
               value="{{ old('email', $user->email) }}"
               class="{{ $cls }}"
               required>

        @error('email')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Role --}}
    <div>
        <label class="block font-medium mb-1">Role Pengguna</label>

        <select name="role" id="role" class="{{ $cls }}" required>
            <option value="admin"
                @selected(old('role', $user->role) === 'admin')>
                Admin
            </option>

            <option value="dinas"
                @selected(old('role', $user->role) === 'dinas')>
                Dinas Pendidikan
            </option>

            <option value="sekolah"
                @selected(old('role', $user->role ?? 'sekolah') === 'sekolah')>
                Operator Sekolah
            </option>
        </select>

        @error('role')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Sekolah --}}
    <div id="blok-sekolah">
        <label class="block font-medium mb-1">Sekolah</label>

        <select name="sekolah_id" class="{{ $cls }}">
            <option value="">Pilih Sekolah</option>

            @foreach ($sekolahs as $sekolah)
                <option value="{{ $sekolah->id }}"
                    @selected((string) old('sekolah_id', $user->sekolah_id) === (string) $sekolah->id)>
                    {{ $sekolah->nama }}
                </option>
            @endforeach
        </select>

        @error('sekolah_id')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <label class="block font-medium mb-1">Password</label>

        <input type="password"
               name="password"
               class="{{ $cls }}"
               autocomplete="new-password"
               @required(! $user->exists)>

        @if ($user->exists)
            <p class="text-sm text-gray-500">
                Kosongkan jika tidak ingin mengubah password.
            </p>
        @endif

        @error('password')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label class="block font-medium mb-1">
            Konfirmasi Password
        </label>

        <input type="password"
               name="password_confirmation"
               class="{{ $cls }}"
               autocomplete="new-password"
               @required(! $user->exists)>
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit"
                class="px-4 py-2 bg-blue-700 text-white rounded">
            Simpan Pengguna
        </button>

        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 bg-gray-200 rounded">
            Batal
        </a>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const role = document.getElementById('role');
        const blokSekolah = document.getElementById('blok-sekolah');

        function aturSekolah() {
            blokSekolah.classList.toggle(
                'hidden',
                role.value !== 'sekolah'
            );
        }

        role.addEventListener('change', aturSekolah);
        aturSekolah();
    });
</script>
@endpush
