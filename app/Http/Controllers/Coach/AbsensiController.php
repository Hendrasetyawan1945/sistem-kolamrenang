<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $coach = auth()->user()->coach;
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        
        $tanggal = $request->get('tanggal', Carbon::now()->format('Y-m-d'));
        $kelasId = $request->get('kelas_id');
        
        $query = Absensi::with(['siswa'])
                        ->whereDate('tanggal', $tanggal);
        
        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $siswaIds = Siswa::where('kelas', $kelas->nama_kelas)->pluck('id');
            $query->whereIn('siswa_id', $siswaIds);
        } else {
            $namaKelas = $kelasSaya->pluck('nama_kelas');
            $siswaIds = Siswa::whereIn('kelas', $namaKelas)->pluck('id');
            $query->whereIn('siswa_id', $siswaIds);
        }
        
        $absensiList = $query->get();
        
        return view('coach.absensi.index', compact('absensiList', 'kelasSaya', 'tanggal', 'kelasId'));
    }

    public function create(Request $request)
    {
        $coach = auth()->user()->coach;
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        
        $tanggal = $request->get('tanggal', Carbon::now()->format('Y-m-d'));
        $kelasId = $request->get('kelas_id');
        
        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $siswaList = Siswa::where('kelas', $kelas->nama_kelas)->get();
        } else {
            $namaKelas = $kelasSaya->pluck('nama_kelas');
            $siswaList = Siswa::whereIn('kelas', $namaKelas)->get();
        }
        
        return view('coach.absensi.create', compact('siswaList', 'kelasSaya', 'tanggal', 'kelasId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'in:hadir,sakit,izin,alpha'
        ]);

        $coach = auth()->user()->coach;
        $namaKelas = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        
        foreach ($request->absensi as $siswaId => $status) {
            $siswa = Siswa::find($siswaId);
            if (!$siswa || !$namaKelas->contains($siswa->kelas)) continue;
            
            Absensi::updateOrCreate([
                'siswa_id' => $siswaId,
                'tanggal' => $request->tanggal
            ], [
                'status' => $status,
                'keterangan' => $request->keterangan[$siswaId] ?? null
            ]);
        }

        return redirect()->route('coach.absensi.index')
                        ->with('success', 'Absensi berhasil disimpan');
    }
}