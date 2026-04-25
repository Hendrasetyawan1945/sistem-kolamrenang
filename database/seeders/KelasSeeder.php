<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Coach;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Buat coach dulu jika belum ada
        $coaches = [
            [
                'nama' => 'Budi Santoso',
                'spesialisasi' => 'Renang Gaya Bebas',
                'pengalaman' => '5 tahun',
                'telepon' => '081234567890',
                'email' => 'budi@youthswimming.com',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Sari Dewi',
                'spesialisasi' => 'Renang Gaya Punggung',
                'pengalaman' => '3 tahun',
                'telepon' => '081234567891',
                'email' => 'sari@youthswimming.com',
                'status' => 'aktif'
            ],
            [
                'nama' => 'Ahmad Fauzi',
                'spesialisasi' => 'Renang Gaya Dada',
                'pengalaman' => '7 tahun',
                'telepon' => '081234567892',
                'email' => 'ahmad@youthswimming.com',
                'status' => 'aktif'
            ]
        ];

        foreach ($coaches as $coachData) {
            Coach::firstOrCreate(
                ['email' => $coachData['email']],
                $coachData
            );
        }

        // Ambil coach yang sudah dibuat
        $coach1 = Coach::where('email', 'budi@youthswimming.com')->first();
        $coach2 = Coach::where('email', 'sari@youthswimming.com')->first();
        $coach3 = Coach::where('email', 'ahmad@youthswimming.com')->first();

        // Buat kelas-kelas
        $kelasList = [
            [
                'nama_kelas' => 'Pemula A',
                'level' => 'pemula',
                'kapasitas' => 15,
                'harga' => 300000,
                'deskripsi' => 'Kelas untuk anak-anak pemula usia 6-8 tahun',
                'aktif' => true,
                'coach_id' => $coach1->id,
            ],
            [
                'nama_kelas' => 'Pemula B',
                'level' => 'pemula',
                'kapasitas' => 15,
                'harga' => 300000,
                'deskripsi' => 'Kelas untuk anak-anak pemula usia 6-8 tahun',
                'aktif' => true,
                'coach_id' => $coach1->id,
            ],
            [
                'nama_kelas' => 'Menengah A',
                'level' => 'menengah',
                'kapasitas' => 12,
                'harga' => 400000,
                'deskripsi' => 'Kelas untuk anak-anak menengah usia 9-12 tahun',
                'aktif' => true,
                'coach_id' => $coach2->id,
            ],
            [
                'nama_kelas' => 'Menengah B',
                'level' => 'menengah',
                'kapasitas' => 12,
                'harga' => 400000,
                'deskripsi' => 'Kelas untuk anak-anak menengah usia 9-12 tahun',
                'aktif' => true,
                'coach_id' => $coach2->id,
            ],
            [
                'nama_kelas' => 'Lanjut A',
                'level' => 'lanjut',
                'kapasitas' => 10,
                'harga' => 500000,
                'deskripsi' => 'Kelas untuk anak-anak lanjut usia 13-16 tahun',
                'aktif' => true,
                'coach_id' => $coach3->id,
            ],
            [
                'nama_kelas' => 'Prestasi',
                'level' => 'prestasi',
                'kapasitas' => 8,
                'harga' => 600000,
                'deskripsi' => 'Kelas prestasi untuk persiapan kompetisi',
                'aktif' => true,
                'coach_id' => $coach3->id,
            ]
        ];

        foreach ($kelasList as $kelasData) {
            Kelas::firstOrCreate(
                ['nama_kelas' => $kelasData['nama_kelas']],
                $kelasData
            );
        }

        echo "Berhasil membuat " . count($kelasList) . " kelas dan " . count($coaches) . " coach.\n";
    }
}