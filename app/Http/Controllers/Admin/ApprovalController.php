<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    public function pembayaran(Request $request)
    {
        $status = $request->get('status', 'pending');
        $coach = $request->get('coach');
        $search = $request->get('search');

        $query = Pembayaran::with(['siswa', 'inputBy', 'approvedBy']);

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by coach
        if ($coach) {
            $query->whereHas('inputBy', function($q) use ($coach) {
                $q->whereHas('coach', function($q2) use ($coach) {
                    $q2->where('id', $coach);
                });
            });
        }

        // Search by siswa name
        if ($search) {
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get coaches for filter
        $coaches = \App\Models\Coach::where('status', 'aktif')->orderBy('nama')->get();

        // Get counts for stats
        $stats = [
            'pending' => Pembayaran::where('status', 'pending')->count(),
            'approved' => Pembayaran::where('status', 'approved')->count(),
            'rejected' => Pembayaran::where('status', 'rejected')->count(),
        ];

        return view('admin.approval.pembayaran', compact('pembayarans', 'coaches', 'stats'));
    }

    public function approve(Request $request, Pembayaran $pembayaran)
    {
        // Validasi: Hanya admin yang bisa approve
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Hanya Admin yang bisa approve pembayaran.');
        }

        // Validasi: Hanya pembayaran pending yang bisa diapprove
        if ($pembayaran->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya pembayaran pending yang bisa diapprove.']);
        }

        // Validasi: Admin tidak bisa approve pembayaran yang dia input sendiri (jika admin juga input)
        if ($pembayaran->input_by === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa approve pembayaran yang Anda input sendiri.']);
        }

        $pembayaran->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        // Log activity untuk audit trail
        \Log::info('Pembayaran Approved', [
            'pembayaran_id' => $pembayaran->id,
            'siswa' => $pembayaran->siswa->nama,
            'jumlah' => $pembayaran->jumlah,
            'approved_by' => auth()->user()->name,
            'approved_at' => now(),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "Pembayaran {$pembayaran->siswa->nama} berhasil diapprove oleh " . auth()->user()->name . ".");
    }

    public function reject(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Validasi: Hanya admin yang bisa reject
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Hanya Admin yang bisa reject pembayaran.');
        }

        // Validasi: Hanya pembayaran pending yang bisa direject
        if ($pembayaran->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya pembayaran pending yang bisa direject.']);
        }

        $pembayaran->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Log activity untuk audit trail
        \Log::info('Pembayaran Rejected', [
            'pembayaran_id' => $pembayaran->id,
            'siswa' => $pembayaran->siswa->nama,
            'jumlah' => $pembayaran->jumlah,
            'rejected_by' => auth()->user()->name,
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "Pembayaran {$pembayaran->siswa->nama} berhasil direject oleh " . auth()->user()->name . ".");
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['siswa', 'inputBy', 'approvedBy']);
        
        return view('admin.approval.show', compact('pembayaran'));
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'pembayaran_ids' => 'required|array',
            'pembayaran_ids.*' => 'exists:pembayarans,id',
        ]);

        // Validasi: Hanya admin yang bisa bulk approve
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Hanya Admin yang bisa approve pembayaran.');
        }

        // Get pembayaran yang akan di-approve
        $pembayarans = Pembayaran::whereIn('id', $request->pembayaran_ids)
            ->where('status', 'pending')
            ->get();

        // Validasi: Tidak bisa approve pembayaran yang diinput sendiri
        $selfInputCount = $pembayarans->where('input_by', auth()->id())->count();
        if ($selfInputCount > 0) {
            return back()->withErrors(['error' => "Tidak bisa approve {$selfInputCount} pembayaran yang Anda input sendiri."]);
        }

        // Update status
        $count = Pembayaran::whereIn('id', $request->pembayaran_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

        // Log activity untuk audit trail
        \Log::info('Bulk Pembayaran Approved', [
            'count' => $count,
            'pembayaran_ids' => $request->pembayaran_ids,
            'approved_by' => auth()->user()->name,
            'approved_at' => now(),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "{$count} pembayaran berhasil diapprove oleh " . auth()->user()->name . ".");
    }
}