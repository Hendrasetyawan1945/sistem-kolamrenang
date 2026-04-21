<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\JerseySize;
use App\Models\JerseyOrder;
use App\Models\PendapatanLain;
use Illuminate\Http\Request;

class JerseyController extends Controller
{
    // ── SIZE CHART ────────────────────────────────────────────────
    public function sizeChart()
    {
        $sizes = JerseySize::orderBy('id')->get();
        return view('admin.jersey.size-chart', compact('sizes'));
    }

    public function storeSizeChart(Request $request)
    {
        $request->validate(['nama_size' => 'required|string|max:10']);
        JerseySize::create($request->all());
        return back()->with('success', 'Size chart berhasil ditambahkan.');
    }

    public function updateSizeChart(Request $request, JerseySize $jerseySize)
    {
        $request->validate(['stok' => 'required|integer|min:0']);
        $jerseySize->update(['stok' => $request->stok]);
        return back()->with('success', 'Stok berhasil diupdate.');
    }

    public function destroySizeChart(JerseySize $jerseySize)
    {
        $jerseySize->delete();
        return back()->with('success', 'Size berhasil dihapus.');
    }

    // ── PEMESANAN ─────────────────────────────────────────────────
    public function pemesanan(Request $request)
    {
        $status     = $request->get('status');
        $statusBayar = $request->get('status_bayar');

        $query = JerseyOrder::with(['siswa', 'jerseySize'])->latest();
        if ($status)      $query->where('status', $status);
        if ($statusBayar) $query->where('status_bayar', $statusBayar);

        $orders = $query->get();
        $siswas = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $sizes  = JerseySize::where('aktif', true)->orderBy('id')->get();

        $stats = [
            'total'       => JerseyOrder::count(),
            'belum_bayar' => JerseyOrder::where('status_bayar', 'belum_bayar')->whereIn('status', ['dipesan','diproses','selesai'])->count(),
            'lunas'       => JerseyOrder::where('status_bayar', 'lunas')->count(),
            'total_pendapatan' => JerseyOrder::where('status_bayar', 'lunas')->sum('harga'),
        ];

        return view('admin.jersey.pemesanan', compact('orders', 'siswas', 'sizes', 'stats'));
    }

    public function storePemesanan(Request $request)
    {
        $request->validate([
            'siswa_id'       => 'required|exists:siswas,id',
            'jersey_size_id' => 'required|exists:jersey_sizes,id',
            'tanggal_pesan'  => 'required|date',
            'harga'          => 'nullable|numeric|min:0',
        ]);

        JerseyOrder::create($request->all());
        return back()->with('success', 'Pesanan jersey berhasil ditambahkan.');
    }

    public function updateStatusPemesanan(Request $request, JerseyOrder $jerseyOrder)
    {
        $request->validate([
            'status' => 'required|in:dipesan,diproses,selesai,diambil',
        ]);

        $jerseyOrder->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diupdate.');
    }

    /**
     * Konfirmasi pembayaran jersey → update status_bayar=lunas
     * Rekap keuangan membaca langsung dari jersey_orders (status_bayar=lunas)
     * TIDAK double-entry ke pendapatan_lain
     */
    public function bayarJersey(Request $request, JerseyOrder $jerseyOrder)
    {
        $request->validate([
            'tanggal_bayar'     => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);

        $jerseyOrder->update([
            'status_bayar'      => 'lunas',
            'tanggal_bayar'     => $request->tanggal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status'            => 'diambil',
        ]);

        return back()->with('success', "Pembayaran jersey {$jerseyOrder->siswa->nama} berhasil dicatat. Otomatis masuk ke Rekap Keuangan.");
    }

    public function destroyPemesanan(JerseyOrder $jerseyOrder)
    {
        $jerseyOrder->delete();
        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    // ── MASTER UKURAN ─────────────────────────────────────────────
    public function masterUkuran()
    {
        $sizes = JerseySize::orderBy('id')->get();
        return view('admin.jersey.master-ukuran', compact('sizes'));
    }

    // ── JERSEY MAP ────────────────────────────────────────────────
    public function jerseyMap()
    {
        return view('admin.jersey.jersey-map');
    }

    public function storeJerseyMap(Request $request)
    {
        return back()->with('success', 'Pengaturan jersey berhasil disimpan!');
    }
}
