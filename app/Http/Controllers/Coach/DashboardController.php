<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $coach = auth()->user()->coach;

        if (!$coach) abort(403, 'Akun ini tidak terhubung ke data coach.');

        // Kelas yang dipegang coach
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        $namaKelas = $kelasSaya->pluck('nama_kelas');

        // Total siswa di semua kelas coach
        $totalSiswa = Siswa::whereIn('kelas', $namaKelas)->count();

        // Jadwal hari ini (berdasarkan kolom hari di tabel kelas)
        $hariIni = Carbon::now()->locale('id')->dayName; // Senin, Selasa, dst
        $hariEn  = Carbon::now()->format('l');           // Monday, Tuesday, dst
        $jadwalHariIni = $kelasSaya->filter(function ($k) use ($hariIni, $hariEn) {
            return strcasecmp($k->hari, $hariIni) === 0
                || strcasecmp($k->hari, $hariEn) === 0;
        });

        // Absensi minggu ini untuk siswa di kelas coach
        $siswaIds = Siswa::whereIn('kelas', $namaKelas)->pluck('id');
        $absensiMingguIni = Absensi::whereIn('siswa_id', $siswaIds)
            ->whereBetween('tanggal', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->count();

        return view('coach.dashboard', compact(
            'kelasSaya',
            'totalSiswa',
            'jadwalHariIni',
            'absensiMingguIni'
        ));
    }
}
