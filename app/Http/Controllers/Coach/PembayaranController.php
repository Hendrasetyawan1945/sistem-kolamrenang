<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        // Get coach's classes
        $coach = auth()->user()->coach;
        $kelasCoach = $coach->kelas->pluck('nama_kelas');
        
        // Get students in coach's classes
        $query = Siswa::where('status', 'aktif')
            ->whereIn('kelas', $kelasCoach);
            
        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $siswas = $query->orderBy('nama')->get();

        // Load pembayaran dengan filter status
        $siswas->load(['pembayarans' => function ($q) use ($tahun, $status) {
            $q->where('jenis_pembayaran', 'iuran_rutin')
              ->where('tahun', $tahun);
              
            if ($status !== 'all') {
                $q->where('status', $status);
            }
        }]);

        // Get pending count for notification
        $pendingCount = Pembayaran::whereHas('siswa', function($q) use ($kelasCoach) {
                $q->whereIn('kelas', $kelasCoach);
            })
            ->where('status', 'pending')
            ->where('input_by', auth()->id())
            ->count();

        return view('coach.pembayaran.index', compact('siswas', 'tahun', 'pendingCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_pembayaran' => 'required|string',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Verify siswa is in coach's class
        $coach = auth()->user()->coach;
        $siswa = Siswa::findOrFail($request->siswa_id);
        $kelasCoach = $coach->kelas->pluck('nama_kelas');
        
        if (!$kelasCoach->contains($siswa->kelas)) {
            return back()->withErrors(['siswa_id' => 'Siswa tidak ada di kelas Anda.']);
        }

        // Create or update pembayaran
        $pembayaran = Pembayaran::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'jenis_pembayaran' => $request->jenis_pembayaran,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
            ],
            [
                'jumlah' => $request->jumlah,
                'tanggal_bayar' => $request->tanggal_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
                'input_by' => auth()->id(),
                'approved_by' => null,
                'approved_at' => null,
                'rejection_reason' => null,
                'bukti_bayar' => null,
            ]
        );

        return back()->with('success', 'Pembayaran berhasil disubmit dan menunggu approval admin.');
    }

    public function show(Pembayaran $pembayaran)
    {
        // Verify coach can access this payment
        $coach = auth()->user()->coach;
        $kelasCoach = $coach->kelas->pluck('nama_kelas');
        
        if (!$kelasCoach->contains($pembayaran->siswa->kelas)) {
            abort(403, 'Unauthorized');
        }

        $pembayaran->load(['siswa', 'inputBy', 'approvedBy']);
        
        return view('coach.pembayaran.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        // Only allow edit if pending or rejected
        if (!in_array($pembayaran->status, ['pending', 'rejected'])) {
            return back()->withErrors(['error' => 'Pembayaran yang sudah diapprove tidak bisa diedit.']);
        }

        // Verify coach can access this payment
        $coach = auth()->user()->coach;
        $kelasCoach = $coach->kelas->pluck('nama_kelas');
        
        if (!$kelasCoach->contains($pembayaran->siswa->kelas)) {
            abort(403, 'Unauthorized');
        }

        return view('coach.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        // Only allow update if pending or rejected
        if (!in_array($pembayaran->status, ['pending', 'rejected'])) {
            return back()->withErrors(['error' => 'Pembayaran yang sudah diapprove tidak bisa diedit.']);
        }

        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $updateData = [
            'jumlah' => $request->jumlah,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
            'status' => 'pending', // Reset to pending after edit
            'rejection_reason' => null, // Clear rejection reason
        ];

        $pembayaran->update($updateData);

        return redirect()->route('coach.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diupdate dan menunggu approval admin.');
    }
}