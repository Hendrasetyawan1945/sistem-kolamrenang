<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\CatatanWaktu;
use App\Models\CatatanWaktuLatihan;
use App\Models\Kolam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestasiController extends Controller
{
    // ── CATATAN WAKTU (Resmi: Kejuaraan / Time Trial) ─────────────
    public function catatanWaktu(Request $request)
    {
        $siswaId    = $request->get('siswa_id');
        $nomorLomba = $request->get('nomor_lomba');
        $jenisEvent = $request->get('jenis_event');
        $kelas      = $request->get('kelas');

        $query = CatatanWaktu::with('siswa');
        if ($siswaId)    $query->where('siswa_id', $siswaId);
        if ($nomorLomba) $query->where('nomor_lomba', $nomorLomba);
        if ($jenisEvent) $query->where('jenis_event', $jenisEvent);
        if ($kelas)      $query->whereHas('siswa', fn($q) => $q->where('kelas', $kelas));

        $catatans    = $query->latest('tanggal')->get();
        $siswas      = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $nomorLombas = CatatanWaktu::distinct()->pluck('nomor_lomba')->sort();
        $kelasList   = Siswa::where('status', 'aktif')->distinct()->pluck('kelas')->sort();
        $kolams      = Kolam::where('aktif', true)->orderBy('nama')->get();

        return view('admin.prestasi.catatan-waktu', compact('catatans', 'siswas', 'nomorLombas', 'kelasList', 'kolams'));
    }

    public function storeCatatanWaktu(Request $request)
    {
        $request->validate([
            'siswa_id'    => 'required|exists:siswas,id',
            'tanggal'     => 'required|date',
            'nomor_lomba' => 'required|string',
            'jenis_kolam' => 'required|in:25m,50m',
            'waktu'       => 'required|string',
            'jenis_event' => 'required|in:latihan,kejuaraan,time_trial',
        ]);

        CatatanWaktu::create([
            'siswa_id'    => $request->siswa_id,
            'tanggal'     => $request->tanggal,
            'nomor_lomba' => $request->nomor_lomba,
            'jenis_kolam' => $request->jenis_kolam,
            'waktu'       => $request->waktu,
            'waktu_detik' => CatatanWaktu::waktuKeDetik($request->waktu),
            'lokasi'      => $request->lokasi,
            'jenis_event' => $request->jenis_event,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Catatan waktu berhasil ditambahkan.');
    }

    public function updateCatatanWaktu(Request $request, CatatanWaktu $catatanWaktu)
    {
        $request->validate([
            'siswa_id'    => 'required|exists:siswas,id',
            'tanggal'     => 'required|date',
            'nomor_lomba' => 'required|string',
            'jenis_kolam' => 'required|in:25m,50m',
            'waktu'       => 'required|string',
            'jenis_event' => 'required|in:latihan,kejuaraan,time_trial',
        ]);

        $catatanWaktu->update([
            'siswa_id'    => $request->siswa_id,
            'tanggal'     => $request->tanggal,
            'nomor_lomba' => $request->nomor_lomba,
            'jenis_kolam' => $request->jenis_kolam,
            'waktu'       => $request->waktu,
            'waktu_detik' => CatatanWaktu::waktuKeDetik($request->waktu),
            'lokasi'      => $request->lokasi,
            'jenis_event' => $request->jenis_event,
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Catatan waktu berhasil diperbarui.');
    }

    public function destroyCatatanWaktu(CatatanWaktu $catatanWaktu)
    {
        $catatanWaktu->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    // ── PERSONAL BEST ─────────────────────────────────────────────
    public function personalBest(Request $request)
    {
        $siswaId    = $request->get('siswa_id');
        $nomorLomba = $request->get('nomor_lomba');

        $query = CatatanWaktu::select('siswa_id', 'nomor_lomba', 'jenis_kolam', DB::raw('MIN(waktu_detik) as best_time'))
            ->with('siswa')->groupBy('siswa_id', 'nomor_lomba', 'jenis_kolam');

        if ($siswaId)    $query->where('siswa_id', $siswaId);
        if ($nomorLomba) $query->where('nomor_lomba', $nomorLomba);

        $personalBests = $query->get();

        foreach ($personalBests as $pb) {
            $detail = CatatanWaktu::where('siswa_id', $pb->siswa_id)
                ->where('nomor_lomba', $pb->nomor_lomba)
                ->where('jenis_kolam', $pb->jenis_kolam)
                ->where('waktu_detik', $pb->best_time)->first();
            $pb->waktu      = $detail->waktu;
            $pb->tanggal    = $detail->tanggal;
            $pb->lokasi     = $detail->lokasi;
            $pb->jenis_event = $detail->jenis_event;
        }

        $siswas      = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $nomorLombas = CatatanWaktu::distinct()->pluck('nomor_lomba')->sort();

        return view('admin.prestasi.personal-best', compact('personalBests', 'siswas', 'nomorLombas'));
    }

    // ── CATATAN WAKTU LATIHAN ─────────────────────────────────────
    public function catatanWaktuLatihan(Request $request)
    {
        $siswaId  = $request->get('siswa_id');
        $tanggal  = $request->get('tanggal');
        $kelas    = $request->get('kelas');
        $jenis    = $request->get('jenis');

        $query = CatatanWaktuLatihan::with('siswa');
        if ($siswaId) $query->where('siswa_id', $siswaId);
        if ($tanggal) $query->whereDate('tanggal', $tanggal);
        if ($kelas)   $query->where('kelas', $kelas);
        if ($jenis)   $query->where('jenis_latihan', $jenis);

        $latihans  = $query->latest('tanggal')->get();
        $siswas    = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $kelasList = Siswa::where('status', 'aktif')->distinct()->pluck('kelas')->sort();

        return view('admin.prestasi.catatan-waktu-latihan', compact('latihans', 'siswas', 'kelasList'));
    }

    public function storeCatatanWaktuLatihan(Request $request)
    {
        $request->validate([
            'siswa_id'      => 'required|exists:siswas,id',
            'tanggal'       => 'required|date',
            'jenis_latihan' => 'required|string',
        ]);

        $siswa = Siswa::find($request->siswa_id);

        CatatanWaktuLatihan::create([
            'siswa_id'      => $request->siswa_id,
            'tanggal'       => $request->tanggal,
            'jenis_latihan' => $request->jenis_latihan,
            'nomor_lomba'   => $request->nomor_lomba,
            'set_jarak'     => $request->set_jarak,
            'waktu'         => $request->waktu,
            'waktu_detik'   => $request->waktu ? CatatanWaktu::waktuKeDetik($request->waktu) : null,
            'kelas'         => $siswa->kelas,
            'catatan'       => $request->catatan,
        ]);

        return back()->with('success', 'Catatan latihan berhasil ditambahkan.');
    }

    public function updateCatatanWaktuLatihan(Request $request, CatatanWaktuLatihan $catatanWaktuLatihan)
    {
        $request->validate([
            'siswa_id'      => 'required|exists:siswas,id',
            'tanggal'       => 'required|date',
            'jenis_latihan' => 'required|string',
        ]);

        $catatanWaktuLatihan->update($request->only([
            'siswa_id', 'tanggal', 'jenis_latihan', 'nomor_lomba', 'set_jarak', 'waktu', 'catatan',
        ]));

        return back()->with('success', 'Catatan latihan berhasil diperbarui.');
    }

    public function destroyCatatanWaktuLatihan(CatatanWaktuLatihan $catatanWaktuLatihan)
    {
        $catatanWaktuLatihan->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    // ── PROGRESS REPORT ───────────────────────────────────────────
    /**
     * Alur Progress Report:
     * 1. Pilih siswa / kelas
     * 2. Sistem otomatis hitung dari data catatan waktu:
     *    - Jumlah catatan waktu (aktifitas)
     *    - Personal best per nomor lomba
     *    - Tren waktu (apakah membaik?)
     *    - Perbandingan catatan latihan vs kejuaraan
     * 3. Tampilkan grafik tren dan ringkasan per siswa
     */
    public function progressReport(Request $request)
    {
        $siswaId = $request->get('siswa_id');
        $kelas   = $request->get('kelas');
        $periode = $request->get('periode', '3_bulan');

        // Tentukan rentang tanggal
        $tanggalMulai = match($periode) {
            '1_bulan'  => now()->subMonth(),
            '3_bulan'  => now()->subMonths(3),
            '6_bulan'  => now()->subMonths(6),
            'tahun_ini'=> now()->startOfYear(),
            default    => now()->subMonths(3),
        };

        $siswaQuery = Siswa::where('status', 'aktif');
        if ($siswaId) $siswaQuery->where('id', $siswaId);
        if ($kelas)   $siswaQuery->where('kelas', $kelas);
        $siswas = $siswaQuery->orderBy('nama')->get();

        // Hitung progress per siswa
        $progressData = $siswas->map(function ($siswa) use ($tanggalMulai) {
            // Total catatan waktu resmi
            $totalCatatan = CatatanWaktu::where('siswa_id', $siswa->id)
                ->where('tanggal', '>=', $tanggalMulai)->count();

            // Total latihan
            $totalLatihan = CatatanWaktuLatihan::where('siswa_id', $siswa->id)
                ->where('tanggal', '>=', $tanggalMulai)->count();

            // Personal best terbaru
            $personalBests = CatatanWaktu::where('siswa_id', $siswa->id)
                ->select('nomor_lomba', 'jenis_kolam', DB::raw('MIN(waktu_detik) as best_time'), DB::raw('MIN(waktu) as best_waktu'))
                ->groupBy('nomor_lomba', 'jenis_kolam')
                ->orderBy('nomor_lomba')
                ->get();

            // Tren: bandingkan waktu terbaru vs 3 bulan lalu per nomor lomba
            $tren = CatatanWaktu::where('siswa_id', $siswa->id)
                ->where('tanggal', '>=', $tanggalMulai)
                ->select('nomor_lomba', 'waktu_detik', 'tanggal')
                ->orderBy('tanggal')
                ->get()
                ->groupBy('nomor_lomba')
                ->map(function ($records) {
                    if ($records->count() < 2) return null;
                    $first = $records->first()->waktu_detik;
                    $last  = $records->last()->waktu_detik;
                    $diff  = $first - $last; // positif = membaik
                    return [
                        'awal'    => $records->first()->waktu,
                        'akhir'   => $records->last()->waktu,
                        'selisih' => $diff,
                        'membaik' => $diff > 0,
                    ];
                })->filter();

            return [
                'siswa'         => $siswa,
                'total_catatan' => $totalCatatan,
                'total_latihan' => $totalLatihan,
                'personal_bests'=> $personalBests,
                'tren'          => $tren,
                'aktif'         => ($totalCatatan + $totalLatihan) > 0,
            ];
        });

        $allSiswas   = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $kelasList   = Siswa::where('status', 'aktif')->distinct()->pluck('kelas')->sort();
        $nomorLombas = CatatanWaktu::distinct()->pluck('nomor_lomba')->sort();

        return view('admin.prestasi.progress-report', compact(
            'progressData', 'allSiswas', 'kelasList', 'nomorLombas', 'periode', 'siswaId', 'kelas'
        ));
    }

    public function nomorNonstandar()
    {
        return view('admin.prestasi.nomor-nonstandar');
    }
}
