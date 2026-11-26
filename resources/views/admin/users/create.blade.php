@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="max-w-2xl mx-auto">

        <h1 class="text-2xl font-bold mb-4">
            Tambah Pengguna
        </h1>

        <div class="bg-white rounded shadow p-6">

            <form method="POST"
                  action="{{ route('admin.users.store') }}">

                @csrf

                @include('admin.users._form')

            </form>

        </div>

    </div>
@endsection
