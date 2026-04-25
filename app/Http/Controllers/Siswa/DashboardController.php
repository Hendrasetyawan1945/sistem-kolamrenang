<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Absensi;
use App\Models\Kelas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        // Status iuran bulan ini
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $iuranBulanIni = Pembayaran::where('siswa_id', $siswa->id)
                                  ->where('bulan', $bulanIni)
                                  ->where('tahun', $tahunIni)
                                  ->where('jenis_pembayaran', 'iuran_rutin')
                                  ->first();
        
        // Jadwal kelas - gunakan relasi kelas() method
        $jadwalKelas = $siswa->kelas()->first();
        
        // Absensi bulan ini
        $absensiCount = Absensi::where('siswa_id', $siswa->id)
                              ->whereMonth('tanggal', Carbon::now()->month)
                              ->whereYear('tanggal', Carbon::now()->year)
                              ->count();
        
        // Total tunggakan
        $totalTunggakan = Pembayaran::where('siswa_id', $siswa->id)
                                   ->where('status', 'belum_lunas')
                                   ->sum('sisa_bayar');

        return view('siswa.dashboard', compact(
            'siswa', 
            'iuranBulanIni', 
            'jadwalKelas', 
            'absensiCount', 
            'totalTunggakan'
        ));
    }
}