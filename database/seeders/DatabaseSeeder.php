<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PaketKuota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed kelas dan coach terlebih dahulu
        $this->call(KelasSeeder::class);
        
        // Seed siswa dan pembayaran
        $this->call(SiswaSeeder::class);

        // Seed catatan waktu
        $this->call(CatatanWaktuSeeder::class);

        // Seed users (admin, coach, siswa)
        $this->call(UserSeeder::class);

        // Seed paket kuota
        PaketKuota::create([
            'nama_paket' => 'Paket 8x',
            'jumlah_pertemuan' => 8,
            'harga' => 400000,
            'keterangan' => 'Paket hemat untuk pemula',
            'aktif' => true,
        ]);

        PaketKuota::create([
            'nama_paket' => 'Paket 12x',
            'jumlah_pertemuan' => 12,
            'harga' => 550000,
            'keterangan' => 'Paket populer untuk latihan rutin',
            'aktif' => true,
        ]);

        PaketKuota::create([
            'nama_paket' => 'Paket Bulanan',
            'jumlah_pertemuan' => 16,
            'harga' => 700000,
            'keterangan' => 'Unlimited latihan selama 1 bulan',
            'aktif' => true,
        ]);

        // Seed jersey sizes
        \App\Models\JerseySize::create([
            'nama_size' => 'XS',
            'lebar_dada' => 38,
            'panjang_badan' => 48,
            'panjang_lengan' => 14,
            'tinggi_badan_min' => 110,
            'tinggi_badan_max' => 120,
            'berat_badan_min' => 20,
            'berat_badan_max' => 25,
            'umur_min' => 6,
            'umur_max' => 8,
            'stok' => 10,
        ]);

        \App\Models\JerseySize::create([
            'nama_size' => 'S',
            'lebar_dada' => 42,
            'panjang_badan' => 52,
            'panjang_lengan' => 16,
            'tinggi_badan_min' => 120,
            'tinggi_badan_max' => 130,
            'berat_badan_min' => 25,
            'berat_badan_max' => 30,
            'umur_min' => 8,
            'umur_max' => 10,
            'stok' => 15,
        ]);

        \App\Models\JerseySize::create([
            'nama_size' => 'M',
            'lebar_dada' => 46,
            'panjang_badan' => 56,
            'panjang_lengan' => 18,
            'tinggi_badan_min' => 130,
            'tinggi_badan_max' => 140,
            'berat_badan_min' => 30,
            'berat_badan_max' => 40,
            'umur_min' => 10,
            'umur_max' => 12,
            'stok' => 20,
        ]);

        \App\Models\JerseySize::create([
            'nama_size' => 'L',
            'lebar_dada' => 50,
            'panjang_badan' => 60,
            'panjang_lengan' => 20,
            'tinggi_badan_min' => 140,
            'tinggi_badan_max' => 150,
            'berat_badan_min' => 40,
            'berat_badan_max' => 50,
            'umur_min' => 12,
            'umur_max' => 14,
            'stok' => 15,
        ]);

        \App\Models\JerseySize::create([
            'nama_size' => 'XL',
            'lebar_dada' => 54,
            'panjang_badan' => 64,
            'panjang_lengan' => 22,
            'tinggi_badan_min' => 150,
            'tinggi_badan_max' => 165,
            'berat_badan_min' => 50,
            'berat_badan_max' => 65,
            'umur_min' => 14,
            'umur_max' => 16,
            'stok' => 10,
        ]);
    }
}
