<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Rapor;
use App\Models\TemplateRapor;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        $bulan = $request->get('bulan');
        
        $query = Rapor::where('siswa_id', $siswa->id);
        
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        
        $raporList = $query->orderBy('bulan', 'desc')->paginate(12);
        
        return view('siswa.rapor.index', compact('raporList', 'bulan'));
    }

    public function show(Rapor $rapor)
    {
        $siswa = auth()->user()->siswa;
        
        // Pastikan rapor ini milik siswa yang login
        if ($rapor->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke rapor ini');
        }
        
        $templateRapor = TemplateRapor::first();
        
        return view('siswa.rapor.show', compact('rapor', 'templateRapor'));
    }
}