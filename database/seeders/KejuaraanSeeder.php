<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kejuaraan;

class KejuaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kejuaraaans = [
            [
                'nama_kejuaraan' => 'Kejuaraan Renang Nasional 2026',
                'penyelenggara' => 'PRSI Jakarta',
                'tanggal_kejuaraan' => '2026-05-15',
                'lokasi' => 'GBK Aquatic Center',
                'biaya_pendaftaran' => 250000,
                'batas_pendaftaran' => '2026-04-30',
                'deskripsi' => 'Kejuaraan renang tingkat nasional untuk semua kategori usia. Lomba meliputi gaya bebas, gaya punggung, gaya dada, dan gaya kupu-kupu.',
                'kategori' => ['KU-10', 'KU-12', 'KU-14', 'Senior'],
                'status' => 'pendaftaran',
                'keterangan' => null,
            ],
            [
                'nama_kejuaraan' => 'Kejuaraan Antar Klub Jakarta',
                'penyelenggara' => 'Pengprov PRSI DKI Jakarta',
                'tanggal_kejuaraan' => '2026-03-20',
                'lokasi' => 'Kolam Renang Senayan',
                'biaya_pendaftaran' => 150000,
                'batas_pendaftaran' => '2026-03-05',
                'deskripsi' => 'Kejuaraan antar klub renang se-Jakarta. Event tahunan untuk mempererat silaturahmi antar klub.',
                'kategori' => ['KU-8', 'KU-10', 'KU-12'],
                'status' => 'selesai',
                'keterangan' => 'Berhasil meraih 3 medali emas, 2 perak, dan 4 perunggu',
            ],
            [
                'nama_kejuaraan' => 'Kejuaraan Renang Usia Dini',
                'penyelenggara' => 'Klub Renang Nusantara',
                'tanggal_kejuaraan' => '2026-06-10',
                'lokasi' => 'Kolam Renang Cibubur',
                'biaya_pendaftaran' => 100000,
                'batas_pendaftaran' => '2026-05-25',
                'deskripsi' => 'Kejuaraan khusus untuk perenang usia dini. Fokus pada pengembangan teknik dasar dan mental bertanding.',
                'kategori' => ['KU-6', 'KU-8', 'KU-10'],
                'status' => 'akan_datang',
                'keterangan' => null,
            ],
            [
                'nama_kejuaraan' => 'Open Tournament Swimming Championship',
                'penyelenggara' => 'Jakarta Swimming Federation',
                'tanggal_kejuaraan' => '2026-07-22',
                'lokasi' => 'Aquatic Stadium Jakarta',
                'biaya_pendaftaran' => 300000,
                'batas_pendaftaran' => '2026-07-05',
                'deskripsi' => 'Turnamen terbuka tingkat internasional dengan peserta dari berbagai negara ASEAN.',
                'kategori' => ['KU-12', 'KU-14', 'Senior', 'Master'],
                'status' => 'akan_datang',
                'keterangan' => null,
            ],
            [
                'nama_kejuaraan' => 'Kejuaraan Renang Pelajar DKI Jakarta',
                'penyelenggara' => 'Dinas Pendidikan DKI Jakarta',
                'tanggal_kejuaraan' => '2026-08-17',
                'lokasi' => 'Kolam Renang Gelora Bung Karno',
                'biaya_pendaftaran' => 75000,
                'batas_pendaftaran' => '2026-08-01',
                'deskripsi' => 'Kejuaraan khusus pelajar dalam rangka memperingati HUT RI ke-81.',
                'kategori' => ['KU-10', 'KU-12', 'KU-14'],
                'status' => 'pendaftaran_tutup',
                'keterangan' => 'Pendaftaran sudah penuh dengan 200 peserta',
            ],
        ];

        foreach ($kejuaraaans as $kejuaraan) {
            Kejuaraan::create($kejuaraan);
        }
    }
}
