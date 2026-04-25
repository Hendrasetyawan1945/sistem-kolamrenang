<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Models\TemplateRapor;
use App\Models\Absensi;
use App\Models\CatatanWaktu;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    private function namaKelasSaya(): \Illuminate\Support\Collection
    {
        return Kelas::where('coach_id', auth()->user()->coach->id)->pluck('nama_kelas');
    }

    public function index(Request $request)
    {
        $coach = auth()->user()->coach;
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        $namaKelas = $kelasSaya->pluck('nama_kelas');

        $bulanInput = $request->get('bulan', date('Y-m'));
        $kelasId = $request->get('kelas_id');
        
        // Parse bulan dengan helper function yang robust
        [$bulanInt, $tahunInt, $bulan] = $this->parseBulanInput($bulanInput);

        $query = Rapor::with(['siswa'])->where('bulan', $bulanInt)->where('tahun', $tahunInt);

        if ($kelasId) {
            $kelas    = Kelas::find($kelasId);
            $siswaIds = Siswa::where('kelas', $kelas->nama_kelas)->pluck('id');
        } else {
            $siswaIds = Siswa::whereIn('kelas', $namaKelas)->pluck('id');
        }
        $query->whereIn('siswa_id', $siswaIds);

        $raporList = $query->get();
        $namaBulan = $this->getNamaBulan();

        return view('coach.rapor.index', compact('raporList', 'kelasSaya', 'bulan', 'kelasId', 'bulanInt', 'tahunInt', 'namaBulan'));
    }

    public function create(Request $request)
    {
        $coach     = auth()->user()->coach;
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        $namaKelas = $kelasSaya->pluck('nama_kelas');

        $bulanInput = $request->get('bulan', date('Y-m'));
        $kelasId = $request->get('kelas_id');
        
        // Parse bulan dengan helper function yang robust
        [$bulanInt, $tahunInt, $bulan] = $this->parseBulanInput($bulanInput);
        $namaBulan = $this->getNamaBulan();
        
        // Ambil template rapor yang aktif
        $templates = TemplateRapor::where('aktif', true)->get();

        if ($kelasId) {
            $kelas     = Kelas::find($kelasId);
            $siswaList = Siswa::where('kelas', $kelas->nama_kelas)->get();
        } else {
            $siswaList = Siswa::whereIn('kelas', $namaKelas)->get();
        }

        return view('coach.rapor.create', compact('siswaList', 'kelasSaya', 'bulan', 'kelasId', 'templates', 'bulanInt', 'tahunInt', 'namaBulan'));
    }

    public function raporSiswa(Request $request, Siswa $siswa)
    {
        // Validasi akses coach
        $namaKelas = $this->namaKelasSaya();
        if (!$namaKelas->contains($siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini');
        }

        $bulan = (int)$request->get('bulan', date('m'));
        $tahun = (int)$request->get('tahun', date('Y'));
        
        // Nama bulan untuk tampilan
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Cari rapor yang sudah ada
        $rapor = Rapor::where('siswa_id', $siswa->id)
                     ->where('bulan', $bulan)
                     ->where('tahun', $tahun)
                     ->first();

        // Ambil template rapor yang aktif
        $templates = TemplateRapor::where('aktif', true)->get();

        // Hitung kehadiran dari absensi
        $hadir = Absensi::where('siswa_id', $siswa->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun)
                        ->count();

        $totalPertemuan = Absensi::where('siswa_id', $siswa->id)
                                ->whereMonth('tanggal', $bulan)
                                ->whereYear('tanggal', $tahun)
                                ->count();

        // Ambil personal best untuk sidebar
        $personalBest = CatatanWaktu::where('siswa_id', $siswa->id)
                                   ->whereNotNull('waktu_terbaik')
                                   ->orderBy('waktu_terbaik')
                                   ->get();

        return view('coach.rapor.siswa', compact('siswa', 'rapor', 'templates', 'bulan', 'tahun', 'namaBulan', 'hadir', 'totalPertemuan', 'personalBest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'kehadiran' => 'required|integer|min:0',
            'total_pertemuan' => 'required|integer|min:0',
            'nilai' => 'nullable|array',
            'catatan_coach' => 'nullable|string',
            'catatan_umum' => 'nullable|string',
            'status' => 'required|in:draft,selesai',
        ]);

        $namaKelas = $this->namaKelasSaya();
        $siswa = Siswa::findOrFail($request->siswa_id);

        if (!$namaKelas->contains($siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini');
        }

        $periode = $this->getNamaBulan()[$request->bulan] . ' ' . $request->tahun;

        Rapor::create([
            'siswa_id' => $request->siswa_id,
            'template_rapor_id' => $request->template_rapor_id,
            'periode' => $periode,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nilai' => $request->nilai,
            'kehadiran' => $request->kehadiran,
            'total_pertemuan' => $request->total_pertemuan,
            'catatan_coach' => $request->catatan_coach,
            'catatan_umum' => $request->catatan_umum,
            'status' => $request->status,
        ]);

        return redirect()->route('coach.rapor.index', ['bulan' => $request->tahun.'-'.str_pad($request->bulan, 2, '0', STR_PAD_LEFT)])
                        ->with('success', 'Rapor berhasil disimpan');
    }

    public function show(Rapor $rapor)
    {
        $namaKelas = $this->namaKelasSaya();

        if (!$namaKelas->contains($rapor->siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke rapor ini');
        }

        $namaBulan = $this->getNamaBulan();

        return view('coach.rapor.show', compact('rapor', 'namaBulan'));
    }

    public function edit(Rapor $rapor)
    {
        $namaKelas = $this->namaKelasSaya();

        if (!$namaKelas->contains($rapor->siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke rapor ini');
        }

        // Ambil template rapor yang aktif
        $templates = TemplateRapor::where('aktif', true)->get();
        
        // Hitung kehadiran dari absensi
        $hadir = Absensi::where('siswa_id', $rapor->siswa_id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $rapor->bulan)
                        ->whereYear('tanggal', $rapor->tahun)
                        ->count();

        $totalPertemuan = Absensi::where('siswa_id', $rapor->siswa_id)
                                ->whereMonth('tanggal', $rapor->bulan)
                                ->whereYear('tanggal', $rapor->tahun)
                                ->count();

        // Ambil personal best untuk sidebar
        $personalBest = CatatanWaktu::where('siswa_id', $rapor->siswa_id)
                                   ->whereNotNull('waktu_terbaik')
                                   ->orderBy('waktu_terbaik')
                                   ->get();

        $namaBulan = $this->getNamaBulan();

        return view('coach.rapor.edit', compact('rapor', 'templates', 'namaBulan', 'hadir', 'totalPertemuan', 'personalBest'));
    }

    public function update(Request $request, Rapor $rapor)
    {
        $request->validate([
            'kehadiran' => 'required|integer|min:0',
            'total_pertemuan' => 'required|integer|min:0',
            'nilai' => 'nullable|array',
            'catatan_coach' => 'nullable|string',
            'catatan_umum' => 'nullable|string',
            'status' => 'required|in:draft,selesai',
        ]);

        $namaKelas = $this->namaKelasSaya();

        if (!$namaKelas->contains($rapor->siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke rapor ini');
        }

        $periode = $this->getNamaBulan()[$request->bulan ?? $rapor->bulan] . ' ' . ($request->tahun ?? $rapor->tahun);

        $rapor->update([
            'template_rapor_id' => $request->template_rapor_id,
            'periode' => $periode,
            'nilai' => $request->nilai,
            'kehadiran' => $request->kehadiran,
            'total_pertemuan' => $request->total_pertemuan,
            'catatan_coach' => $request->catatan_coach,
            'catatan_umum' => $request->catatan_umum,
            'status' => $request->status,
        ]);

        return redirect()->route('coach.rapor.index', ['bulan' => $rapor->tahun.'-'.str_pad($rapor->bulan, 2, '0', STR_PAD_LEFT)])
                        ->with('success', 'Rapor berhasil diperbarui');
    }

    private function getNamaBulan(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
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
