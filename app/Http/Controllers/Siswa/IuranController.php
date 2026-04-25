<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Angsuran;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        // Ambil pembayaran siswa
        $pembayaranList = Pembayaran::where('siswa_id', $siswa->id)
                                   ->orderBy('tahun', 'desc')
                                   ->orderBy('bulan', 'desc')
                                   ->paginate(12);

        // Ambil angsuran siswa
        $angsuranList = Angsuran::where('siswa_id', $siswa->id)
                               ->with('cicilans')
                               ->orderBy('tanggal_tagihan', 'desc')
                               ->get();

        return view('siswa.iuran.index', compact('pembayaranList', 'angsuranList'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $siswa = auth()->user()->siswa;
        
        // Pastikan pembayaran ini milik siswa yang login
        if ($pembayaran->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }
        
        // Ambil angsuran terkait siswa (bukan pembayaran spesifik)
        $angsuranList = Angsuran::where('siswa_id', $siswa->id)
                               ->with('cicilans')
                               ->orderBy('tanggal_tagihan', 'desc')
                               ->get();
        
        return view('siswa.iuran.show', compact('pembayaran', 'angsuranList'));
    }
}