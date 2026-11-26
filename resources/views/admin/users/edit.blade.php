@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="max-w-2xl mx-auto">

        <h1 class="text-2xl font-bold mb-4">
            Edit Pengguna
        </h1>

        <div class="bg-white rounded shadow p-6">

            <form method="POST"
                  action="{{ route('admin.users.update', $user) }}">

                @csrf
                @method('PUT')

                @include('admin.users._form')

            </form>

        </div>

    </div>
@endsection
