<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sekolahs = Sekolah::orderBy('id')->take(2)->get();

        User::updateOrCreate(
            ['email' => 'admin@sig.test'],
            [
                'name' => 'Admin Sistem',
                'password' => 'password123',
                'role' => 'admin',
                'sekolah_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'dinas@sig.test'],
            [
                'name' => 'Dinas Pendidikan',
                'password' => 'password123',
                'role' => 'dinas',
                'sekolah_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'sekolah1@sig.test'],
            [
                'name' => 'Operator Sekolah 1',
                'password' => 'password123',
                'role' => 'sekolah',
                'sekolah_id' => $sekolahs->get(0)?->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'sekolah2@sig.test'],
            [
                'name' => 'Operator Sekolah 2',
                'password' => 'password123',
                'role' => 'sekolah',
                'sekolah_id' => $sekolahs->get(1)?->id,
            ]
        );
    }
}