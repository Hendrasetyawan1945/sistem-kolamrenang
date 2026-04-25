<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $coach = auth()->user()->coach;
        
        // Hanya siswa di kelas yang dipegang coach ini
        $kelasSaya = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        $siswaList = Siswa::whereIn('kelas', $kelasSaya)->paginate(20);

        return view('coach.siswa.index', compact('siswaList'));
    }

    public function show(Siswa $siswa)
    {
        $coach = auth()->user()->coach;
        
        $kelasSaya = Kelas::where('coach_id', $coach->id)->pluck('nama_kelas');
        
        if (!$kelasSaya->contains($siswa->kelas)) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini');
        }

        return view('coach.siswa.show', compact('siswa'));
    }
}