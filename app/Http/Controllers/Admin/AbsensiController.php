<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $kelas = $request->get('kelas');

        $query = Siswa::where('status', 'aktif');
        
        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $siswas = $query->orderBy('nama')->get();

        // Load absensi untuk tanggal ini
        $siswas->load(['absensis' => function ($q) use ($tanggal) {
            $q->where('tanggal', $tanggal);
        }]);

        $kelasList = Siswa::where('status', 'aktif')
            ->distinct()
            ->pluck('kelas')
            ->sort();

        return view('admin.absensi.index', compact('siswas', 'tanggal', 'kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        Absensi::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'status' => $request->status,
                'jam_masuk' => $request->jam_masuk,
                'jam_keluar' => $request->jam_keluar,
                'keterangan' => $request->keterangan,
            ]
        );

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $kelas = $request->get('kelas');

        $query = Siswa::where('status', 'aktif');
        
        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $siswas = $query->orderBy('nama')->get();

        // Hitung statistik absensi per siswa
        $siswas->each(function ($siswa) use ($bulan, $tahun) {
            $absensis = Absensi::where('siswa_id', $siswa->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $siswa->total_hadir = $absensis->where('status', 'hadir')->count();
            $siswa->total_izin = $absensis->where('status', 'izin')->count();
            $siswa->total_sakit = $absensis->where('status', 'sakit')->count();
            $siswa->total_alpha = $absensis->where('status', 'alpha')->count();
            $siswa->total_absensi = $absensis->count();
        });

        $kelasList = Siswa::where('status', 'aktif')
            ->distinct()
            ->pluck('kelas')
            ->sort();

        return view('admin.absensi.rekap', compact('siswas', 'bulan', 'tahun', 'kelasList'));
    }
}
