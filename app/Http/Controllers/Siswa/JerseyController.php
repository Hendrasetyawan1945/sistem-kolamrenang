<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JerseyOrder;
use Illuminate\Http\Request;

class JerseyController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan');
        }
        
        $jerseyOrders = JerseyOrder::where('siswa_id', $siswa->id)
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('siswa.jersey.index', compact('jerseyOrders'));
    }

    public function show(JerseyOrder $jerseyOrder)
    {
        $siswa = auth()->user()->siswa;
        
        // Pastikan pesanan ini milik siswa yang login
        if ($jerseyOrder->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        return view('siswa.jersey.show', compact('jerseyOrder'));
    }
}