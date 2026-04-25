<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaktu;
use App\Models\CatatanWaktuLatihan;
use Illuminate\Http\Request;

class CatatanWaktuController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        // Ambil catatan waktu kompetisi
        $catatanWaktuKompetisi = CatatanWaktu::where('siswa_id', $siswa->id)
                                            ->orderBy('tanggal', 'desc')
                                            ->get();
        
        // Ambil catatan waktu latihan
        $catatanWaktuLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                                 ->orderBy('tanggal', 'desc')
                                                 ->get();
        
        // Gabungkan dan urutkan berdasarkan tanggal
        $allRecords = collect();
        
        // Tambahkan catatan kompetisi dengan flag type
        foreach($catatanWaktuKompetisi as $record) {
            $record->record_type = 'kompetisi';
            $allRecords->push($record);
        }
        
        // Tambahkan catatan latihan dengan flag type
        foreach($catatanWaktuLatihan as $record) {
            $record->record_type = 'latihan';
            // Standardisasi field untuk konsistensi tampilan
            $record->jenis_kolam = $record->kelas ?? '25m';
            $record->jenis_event = $record->jenis_latihan ?? 'latihan';
            $record->lokasi = 'Kolam Latihan';
            $allRecords->push($record);
        }
        
        // Urutkan berdasarkan tanggal dan paginate
        $catatanWaktu = $allRecords->sortByDesc('tanggal')->values();
        
        // Manual pagination
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedRecords = $catatanWaktu->slice($offset, $perPage);
        
        // Create paginator
        $catatanWaktu = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedRecords,
            $catatanWaktu->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        // Ambil personal best untuk setiap nomor lomba (prioritas kompetisi)
        $personalBests = CatatanWaktu::where('siswa_id', $siswa->id)
                                    ->selectRaw('nomor_lomba, MIN(waktu_detik) as best_time_detik, MIN(waktu) as best_time')
                                    ->groupBy('nomor_lomba')
                                    ->get()
                                    ->keyBy('nomor_lomba');
        
        // Jika tidak ada catatan kompetisi, ambil dari latihan
        if ($personalBests->count() == 0) {
            $personalBests = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                                               ->selectRaw('nomor_lomba, MIN(waktu) as best_time')
                                               ->groupBy('nomor_lomba')
                                               ->get()
                                               ->keyBy('nomor_lomba');
        }
        
        // Ambil catatan waktu terbaru (5 terakhir)
        $catatanTerbaru = $allRecords->sortByDesc('tanggal')->take(5);
        
        return view('siswa.catatan-waktu.index', compact('catatanWaktu', 'personalBests', 'catatanTerbaru'));
    }

    public function show(CatatanWaktu $catatanWaktu)
    {
        $siswa = auth()->user()->siswa;
        
        // Pastikan catatan waktu ini milik siswa yang login
        if ($catatanWaktu->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }
        
        // Ambil riwayat untuk nomor lomba yang sama
        $riwayatNomor = CatatanWaktu::where('siswa_id', $siswa->id)
                                   ->where('nomor_lomba', $catatanWaktu->nomor_lomba)
                                   ->where('id', '!=', $catatanWaktu->id)
                                   ->orderBy('tanggal', 'desc')
                                   ->limit(10)
                                   ->get();
        
        // Ambil personal best untuk nomor ini
        $personalBest = CatatanWaktu::where('siswa_id', $siswa->id)
                                   ->where('nomor_lomba', $catatanWaktu->nomor_lomba)
                                   ->orderBy('waktu_detik', 'asc')
                                   ->first();
        
        return view('siswa.catatan-waktu.show', compact('catatanWaktu', 'riwayatNomor', 'personalBest'));
    }
}