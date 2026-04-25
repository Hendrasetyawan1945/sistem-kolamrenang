<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        $bulanInput = $request->get('bulan', Carbon::now()->format('Y-m'));
        
        // Parse bulan dengan helper function yang robust
        [$bulanNum, $tahun, $bulan] = $this->parseBulanInput($bulanInput);
        
        $absensiList = Absensi::where('siswa_id', $siswa->id)
                             ->whereYear('tanggal', $tahun)
                             ->whereMonth('tanggal', $bulanNum)
                             ->orderBy('tanggal', 'desc')
                             ->get();
        
        // Statistik kehadiran
        $totalHadir = $absensiList->where('status', 'hadir')->count();
        $totalSakit = $absensiList->where('status', 'sakit')->count();
        $totalIzin = $absensiList->where('status', 'izin')->count();
        $totalAlpha = $absensiList->where('status', 'alpha')->count();
        $totalAbsensi = $absensiList->count();
        
        $persentaseKehadiran = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;

        return view('siswa.kehadiran.index', compact(
            'absensiList', 
            'bulan', 
            'totalHadir', 
            'totalSakit', 
            'totalIzin', 
            'totalAlpha', 
            'persentaseKehadiran'
        ));
    }

    /**
     * Parse input bulan ke format yang konsisten
     * Handle berbagai format: '4', '04', '2026-04', 'April', dll
     */
    private function parseBulanInput($input): array
    {
        // Default ke bulan/tahun sekarang
        $bulanInt = (int)date('m');
        $tahunInt = (int)date('Y');
        
        if (empty($input)) {
            return [$bulanInt, $tahunInt, date('Y-m')];
        }
        
        // Jika input hanya angka 1-12 (bulan saja)
        if (is_numeric($input) && $input >= 1 && $input <= 12) {
            $bulanInt = (int)$input;
            $tahunInt = (int)date('Y');
        }
        // Jika format YYYY-MM
        else if (preg_match('/^(\d{4})-(\d{1,2})$/', $input, $matches)) {
            $tahunInt = (int)$matches[1];
            $bulanInt = (int)$matches[2];
        }
        // Jika format MM-YYYY (tidak standar tapi handle juga)
        else if (preg_match('/^(\d{1,2})-(\d{4})$/', $input, $matches)) {
            $bulanInt = (int)$matches[1];
            $tahunInt = (int)$matches[2];
        }
        // Default jika tidak bisa di-parse
        else {
            $bulanInt = (int)date('m');
            $tahunInt = (int)date('Y');
        }
        
        // Validasi bulan
        if ($bulanInt < 1 || $bulanInt > 12) {
            $bulanInt = (int)date('m');
        }
        
        // Format bulan untuk konsistensi
        $bulanFormatted = sprintf('%04d-%02d', $tahunInt, $bulanInt);
        
        return [$bulanInt, $tahunInt, $bulanFormatted];
    }
}