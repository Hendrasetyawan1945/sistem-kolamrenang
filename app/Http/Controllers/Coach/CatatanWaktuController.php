<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\CatatanWaktuLatihan;
use Illuminate\Http\Request;

class CatatanWaktuController extends Controller
{
    public function index(Request $request)
    {
        $coach = auth()->user()->coach;
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        
        $kelasId = $request->get('kelas_id');
        $siswaId = $request->get('siswa_id');
        
        $namaKelas = $kelasSaya->pluck('nama_kelas');
        $query = CatatanWaktuLatihan::with(['siswa']);
        
        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $siswaIds = Siswa::where('kelas', $kelas->nama_kelas)->pluck('id');
            $query->whereIn('siswa_id', $siswaIds);
        } else {
            $siswaIds = Siswa::whereIn('kelas', $namaKelas)->pluck('id');
            $query->whereIn('siswa_id', $siswaIds);
        }
        
        if ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }
        
        $catatanList = $query->orderBy('tanggal', 'desc')->paginate(20);
        $siswaList = Siswa::whereIn('kelas', $namaKelas)->get();
        
        return view('coach.catatan-waktu.index', compact('catatanList', 'kelasSaya', 'siswaList', 'kelasId', 'siswaId'));
    }

    public function create()
    {
        $coach = auth()->user()->coach;
        $namaKelas = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        $siswaList = Siswa::whereIn('kelas', $namaKelas)->get();
        $kelasSaya = Kelas::where('coach_id', $coach->id)->get();
        
        return view('coach.catatan-waktu.create', compact('siswaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jenis_latihan' => 'required|string',
            'jarak' => 'required|integer',
            'waktu' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $coach = auth()->user()->coach;
        $namaKelas = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        $siswa = Siswa::find($request->siswa_id);
        
        if (!$siswa || !$namaKelas->contains($siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini');
        }

        CatatanWaktuLatihan::create($request->all());

        return redirect()->route('coach.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil ditambahkan');
    }

    public function edit(CatatanWaktuLatihan $catatanWaktu)
    {
        $coach = auth()->user()->coach;
        $namaKelas = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        
        if (!$namaKelas->contains($catatanWaktu->siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke catatan ini');
        }
        
        $siswaList = Siswa::whereIn('kelas', $namaKelas)->get();
        
        return view('coach.catatan-waktu.edit', compact('catatanWaktu', 'siswaList'));
    }

    public function update(Request $request, CatatanWaktuLatihan $catatanWaktu)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jenis_latihan' => 'required|string',
            'jarak' => 'required|integer',
            'waktu' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $coach = auth()->user()->coach;
        $namaKelas = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        $siswa = Siswa::find($request->siswa_id);
        
        if (!$siswa || !$namaKelas->contains($siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini');
        }

        $catatanWaktu->update($request->all());

        return redirect()->route('coach.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil diperbarui');
    }
}