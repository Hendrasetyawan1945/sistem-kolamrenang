<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaktu;
use App\Models\CatatanWaktuLatihan;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        // Personal best per nomor lomba dari catatan waktu kompetisi
        $personalBests = CatatanWaktu::where('siswa_id', $siswa->id)
                                    ->selectRaw('nomor_lomba, MIN(waktu_detik) as best_time_detik, MIN(waktu) as best_time, 
                                                MAX(tanggal) as latest_date,
                                                COUNT(*) as total_attempts')
                                    ->groupBy('nomor_lomba')
                                    ->orderBy('nomor_lomba')
                                    ->get();
        
        // Jika tidak ada catatan kompetisi, ambil dari catatan latihan
        if ($personalBests->count() == 0) {
            $personalBests = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                               ->selectRaw('nomor_lomba, MIN(waktu) as best_time, 
                                                           MAX(tanggal) as latest_date,
                                                           COUNT(*) as total_attempts')
                                               ->groupBy('nomor_lomba')
                                               ->orderBy('nomor_lomba')
                                               ->get();
        }
        
        // Catatan waktu terbaru (10 terakhir) - prioritas kompetisi, lalu latihan
        $catatanTerbaru = CatatanWaktu::where('siswa_id', $siswa->id)
                                     ->orderBy('tanggal', 'desc')
                                     ->limit(10)
                                     ->get();
        
        if ($catatanTerbaru->count() < 10) {
            $catatanLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                                ->orderBy('tanggal', 'desc')
                                                ->limit(10 - $catatanTerbaru->count())
                                                ->get();
            $catatanTerbaru = $catatanTerbaru->merge($catatanLatihan)->sortByDesc('tanggal')->take(10);
        }
        
        // Statistik umum - gabungan kompetisi dan latihan
        $totalCatatanKompetisi = CatatanWaktu::where('siswa_id', $siswa->id)->count();
        $totalCatatanLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)->count();
        $totalCatatan = $totalCatatanKompetisi + $totalCatatanLatihan;
        
        $totalNomorLombaKompetisi = CatatanWaktu::where('siswa_id', $siswa->id)
                                               ->distinct('nomor_lomba')
                                               ->count('nomor_lomba');
        $totalNomorLombaLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                                    ->distinct('nomor_lomba')
                                                    ->count('nomor_lomba');
        $totalNomorLomba = max($totalNomorLombaKompetisi, $totalNomorLombaLatihan);
        
        // Progress bulan ini vs bulan lalu
        $bulanIniKompetisi = CatatanWaktu::where('siswa_id', $siswa->id)
                                        ->whereMonth('tanggal', now()->month)
                                        ->whereYear('tanggal', now()->year)
                                        ->count();
        $bulanIniLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                             ->whereMonth('tanggal', now()->month)
                                             ->whereYear('tanggal', now()->year)
                                             ->count();
        $bulanIni = $bulanIniKompetisi + $bulanIniLatihan;
        
        $bulanLaluKompetisi = CatatanWaktu::where('siswa_id', $siswa->id)
                                         ->whereMonth('tanggal', now()->subMonth()->month)
                                         ->whereYear('tanggal', now()->subMonth()->year)
                                         ->count();
        $bulanLaluLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                              ->whereMonth('tanggal', now()->subMonth()->month)
                                              ->whereYear('tanggal', now()->subMonth()->year)
                                              ->count();
        $bulanLalu = $bulanLaluKompetisi + $bulanLaluLatihan;

        // Data grafik perkembangan per nomor lomba (semua catatan, diurutkan tanggal)
        $nomorLombaList = CatatanWaktu::where('siswa_id', $siswa->id)
                                      ->distinct('nomor_lomba')
                                      ->pluck('nomor_lomba');

        $grafikData = [];
        foreach ($nomorLombaList as $nomor) {
            $records = CatatanWaktu::where('siswa_id', $siswa->id)
                                   ->where('nomor_lomba', $nomor)
                                   ->orderBy('tanggal')
                                   ->get(['tanggal', 'waktu', 'waktu_detik']);
            if ($records->count() >= 1) {
                $grafikData[$nomor] = $records->map(fn($r) => [
                    'tanggal' => $r->tanggal->format('d/m/Y'),
                    'waktu'   => $r->waktu,
                    'detik'   => (float) $r->waktu_detik,
                ])->values();
            }
        }

        return view('siswa.prestasi.index', compact(
            'personalBests', 
            'catatanTerbaru', 
            'totalCatatan', 
            'totalNomorLomba',
            'bulanIni',
            'bulanLalu',
            'grafikData'
        ));
    }
}