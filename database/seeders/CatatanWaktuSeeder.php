<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\CatatanWaktu;
use Illuminate\Database\Seeder;

class CatatanWaktuSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = Siswa::where('status', 'aktif')->get();
        
        if ($siswas->isEmpty()) {
            return;
        }

        $nomorLombas = [
            '50m Freestyle',
            '100m Freestyle',
            '50m Backstroke',
            '100m Backstroke',
            '50m Breaststroke',
            '100m Breaststroke',
            '50m Butterfly',
            '100m Butterfly',
        ];

        $jenisEvents = ['latihan', 'time_trial', 'kejuaraan'];
        $jenisKolams = ['25m', '50m'];
        $lokasis = ['Kolam Renang Senayan', 'GBK Aquatic Center', 'Kolam Renang Cikini', 'Kolam Renang Tebet'];

        // Buat 50 catatan waktu random
        for ($i = 0; $i < 50; $i++) {
            $siswa = $siswas->random();
            $nomorLomba = $nomorLombas[array_rand($nomorLombas)];
            $jenisEvent = $jenisEvents[array_rand($jenisEvents)];
            $jenisKolam = $jenisKolams[array_rand($jenisKolams)];
            
            // Generate waktu random berdasarkan nomor lomba
            if (strpos($nomorLomba, '50m') !== false) {
                // 50m: 25-45 detik
                $detik = rand(25, 45);
                $milidetik = rand(0, 99);
                $waktu = sprintf("00:%02d.%02d", $detik, $milidetik);
                $waktuDetik = $detik + ($milidetik / 100);
            } else {
                // 100m: 55-90 detik (0:55 - 1:30)
                $totalDetik = rand(55, 90);
                $menit = floor($totalDetik / 60);
                $detik = $totalDetik % 60;
                $milidetik = rand(0, 99);
                $waktu = sprintf("%02d:%02d.%02d", $menit, $detik, $milidetik);
                $waktuDetik = $totalDetik + ($milidetik / 100);
            }

            CatatanWaktu::create([
                'siswa_id' => $siswa->id,
                'tanggal' => date('Y-m-d', strtotime('-' . rand(0, 90) . ' days')),
                'nomor_lomba' => $nomorLomba,
                'jenis_kolam' => $jenisKolam,
                'waktu' => $waktu,
                'waktu_detik' => $waktuDetik,
                'lokasi' => $lokasis[array_rand($lokasis)],
                'jenis_event' => $jenisEvent,
                'keterangan' => $jenisEvent == 'kejuaraan' ? 'Kejuaraan Antar Club' : null,
            ]);
        }
    }
}
