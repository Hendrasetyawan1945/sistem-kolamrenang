<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::firstOrCreate(
            ['email' => 'admin@youthswimming.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Buat akun untuk SEMUA coach yang ada
        Coach::all()->each(function ($coach) {
            User::firstOrCreate(
                ['email' => $coach->email],
                [
                    'name'     => $coach->nama,
                    'password' => Hash::make('coach123'),
                    'role'     => 'coach',
                    'coach_id' => $coach->id,
                ]
            );
        });

        // Contoh akun siswa (siswa pertama)
        $siswa = Siswa::first();
        if ($siswa) {
            User::firstOrCreate(
                ['email' => 'siswa@youthswimming.com'],
                [
                    'name'     => $siswa->nama,
                    'password' => Hash::make('siswa123'),
                    'role'     => 'siswa',
                    'siswa_id' => $siswa->id,
                ]
            );
        }
    }
}
