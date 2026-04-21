<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            [
                'nama' => 'Fillo Navyandra Bintang Irawan',
                'tanggal_lahir' => '2014-05-15',
                'jenis_kelamin' => 'L',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'nama_ortu' => 'Budi Irawan',
                'telepon' => '081234567890',
                'email' => 'budi.irawan@email.com',
                'paket' => '12x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Ghaisan Ghaits Fatih',
                'tanggal_lahir' => '2013-08-22',
                'jenis_kelamin' => 'L',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta',
                'nama_ortu' => 'Ahmad Fatih',
                'telepon' => '081234567891',
                'email' => 'ahmad.fatih@email.com',
                'paket' => 'bulanan',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Heri Budiman',
                'tanggal_lahir' => '2015-03-10',
                'jenis_kelamin' => 'L',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta',
                'nama_ortu' => 'Slamet Budiman',
                'telepon' => '081234567892',
                'email' => 'slamet.budiman@email.com',
                'paket' => '8x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Iwan Setiawan',
                'tanggal_lahir' => '2014-11-05',
                'jenis_kelamin' => 'L',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Thamrin No. 90, Jakarta',
                'nama_ortu' => 'Bambang Setiawan',
                'telepon' => '081234567893',
                'email' => 'bambang.setiawan@email.com',
                'paket' => '12x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'John Doe',
                'tanggal_lahir' => '2013-07-18',
                'jenis_kelamin' => 'L',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Kuningan No. 12, Jakarta',
                'nama_ortu' => 'Michael Doe',
                'telepon' => '081234567894',
                'email' => 'michael.doe@email.com',
                'paket' => 'bulanan',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Kamila Syahira',
                'tanggal_lahir' => '2014-09-25',
                'jenis_kelamin' => 'P',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Senopati No. 34, Jakarta',
                'nama_ortu' => 'Hendra Syahir',
                'telepon' => '081234567895',
                'email' => 'hendra.syahir@email.com',
                'paket' => '12x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Karima Fayruzzani',
                'tanggal_lahir' => '2015-01-30',
                'jenis_kelamin' => 'P',
                'kelas' => 'KU-10',
                'alamat' => 'Jl. Rasuna Said No. 56, Jakarta',
                'nama_ortu' => 'Fajar Ruzzani',
                'telepon' => '081234567896',
                'email' => 'fajar.ruzzani@email.com',
                'paket' => '8x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Sarah Putri Maharani',
                'tanggal_lahir' => '2013-12-12',
                'jenis_kelamin' => 'P',
                'kelas' => 'KU-12',
                'alamat' => 'Jl. HR Rasuna Said No. 88, Jakarta',
                'nama_ortu' => 'Agus Maharani',
                'telepon' => '081234567897',
                'email' => 'agus.maharani@email.com',
                'paket' => 'bulanan',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Andi Pratama',
                'tanggal_lahir' => '2014-06-20',
                'jenis_kelamin' => 'L',
                'kelas' => 'KU-12',
                'alamat' => 'Jl. Casablanca No. 99, Jakarta',
                'nama_ortu' => 'Dedi Pratama',
                'telepon' => '081234567898',
                'email' => 'dedi.pratama@email.com',
                'paket' => '12x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Bella Anastasia',
                'tanggal_lahir' => '2015-04-08',
                'jenis_kelamin' => 'P',
                'kelas' => 'KU-12',
                'alamat' => 'Jl. Kemang Raya No. 77, Jakarta',
                'nama_ortu' => 'Robert Anastasia',
                'telepon' => '081234567899',
                'email' => 'robert.anastasia@email.com',
                'paket' => '8x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Citra Dewi Lestari',
                'tanggal_lahir' => '2013-10-15',
                'jenis_kelamin' => 'P',
                'kelas' => 'prestasi',
                'alamat' => 'Jl. Menteng No. 55, Jakarta',
                'nama_ortu' => 'Eko Lestari',
                'telepon' => '081234567800',
                'email' => 'eko.lestari@email.com',
                'paket' => 'bulanan',
                'status' => 'aktif',
            ],
            [
                'nama' => 'David Kurniawan',
                'tanggal_lahir' => '2014-02-28',
                'jenis_kelamin' => 'L',
                'kelas' => 'prestasi',
                'alamat' => 'Jl. Cikini No. 22, Jakarta',
                'nama_ortu' => 'Hadi Kurniawan',
                'telepon' => '081234567801',
                'email' => 'hadi.kurniawan@email.com',
                'paket' => '12x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Elena Wijaya',
                'tanggal_lahir' => '2015-05-17',
                'jenis_kelamin' => 'P',
                'kelas' => 'pemula',
                'alamat' => 'Jl. Tebet No. 33, Jakarta',
                'nama_ortu' => 'Gunawan Wijaya',
                'telepon' => '081234567802',
                'email' => 'gunawan.wijaya@email.com',
                'paket' => '8x',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Fajar Ramadhan',
                'tanggal_lahir' => '2014-08-09',
                'jenis_kelamin' => 'L',
                'kelas' => 'pemula',
                'alamat' => 'Jl. Pancoran No. 44, Jakarta',
                'nama_ortu' => 'Irfan Ramadhan',
                'telepon' => '081234567803',
                'email' => 'irfan.ramadhan@email.com',
                'paket' => 'bulanan',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Gita Permata Sari',
                'tanggal_lahir' => '2013-11-23',
                'jenis_kelamin' => 'P',
                'kelas' => 'menengah',
                'alamat' => 'Jl. Pasar Minggu No. 66, Jakarta',
                'nama_ortu' => 'Joko Permata',
                'telepon' => '081234567804',
                'email' => 'joko.permata@email.com',
                'paket' => '12x',
                'status' => 'aktif',
            ],
        ];

        foreach ($siswas as $data) {
            $siswa = Siswa::create($data);

            // Buat pembayaran random untuk beberapa bulan
            $tahun = date('Y');
            $bulanBayar = [1, 2, 3, 4]; // Jan-Apr sudah bayar (sesuai gambar)

            foreach ($bulanBayar as $bulan) {
                Pembayaran::create([
                    'siswa_id' => $siswa->id,
                    'jenis_pembayaran' => 'iuran_rutin',
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'jumlah' => 500000,
                    'tanggal_bayar' => date('Y-m-d', strtotime("$tahun-$bulan-15")),
                    'metode_pembayaran' => ['Tunai', 'Transfer', 'QRIS'][rand(0, 2)],
                ]);
            }
        }
    }
}
