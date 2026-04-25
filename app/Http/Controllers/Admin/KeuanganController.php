<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\PaketKuota;
use App\Models\Angsuran;
use App\Models\AngsuranCicilan;
use App\Models\Kejuaraan;
use App\Models\KejuaraanPembayaran;
use App\Models\PendapatanLain;
use App\Models\Pengeluaran;
use App\Models\JerseyOrder;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function iuranRutin(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $kelas = $request->get('kelas');
        $search = $request->get('search');

        $query = Siswa::where('status', 'aktif');
        
        if ($kelas) {
            $query->where('kelas', $kelas);
        }
        
        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $siswas = $query->orderBy('nama')->get();

        // Load pembayaran untuk tahun ini (hanya yang approved)
        $siswas->load(['pembayarans' => function ($q) use ($tahun) {
            $q->where('jenis_pembayaran', 'iuran_rutin')
              ->where('tahun', $tahun)
              ->where('status', 'approved'); // Only show approved payments
        }]);

        $kelasList = Siswa::where('status', 'aktif')
            ->distinct()
            ->pluck('kelas')
            ->sort();

        return view('admin.keuangan.iuran-rutin', compact('siswas', 'tahun', 'kelasList'));
    }

    public function storePembayaran(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_pembayaran' => 'required|string',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
        ]);

        Pembayaran::updateOrCreate(
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
                'status' => 'approved', // Admin input is auto-approved
                'input_by' => auth()->id(),
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]
        );

        return back()->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function paketKuota()
    {
        $pakets = PaketKuota::where('aktif', true)->get();
        return view('admin.keuangan.paket-kuota', compact('pakets'));
    }

    public function storePaketKuota(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'jumlah_pertemuan' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        PaketKuota::create($request->all());

        return back()->with('success', 'Paket kuota berhasil ditambahkan.');
    }

    public function destroyPaketKuota(PaketKuota $paketKuota)
    {
        $paketKuota->delete();
        return back()->with('success', 'Paket berhasil dihapus.');
    }

    public function iuranInsidentil()
    {
        $siswas = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $insidentils = Pembayaran::where('jenis_pembayaran', 'iuran_insidentil')
            ->with('siswa')
            ->latest('tanggal_bayar')
            ->get();
        
        return view('admin.keuangan.iuran-insidentil', compact('siswas', 'insidentils'));
    }

    public function storeInsidentil(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_iuran' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
        ]);

        Pembayaran::create([
            'siswa_id' => $request->siswa_id,
            'jenis_pembayaran' => 'iuran_insidentil',
            'tahun' => date('Y', strtotime($request->tanggal_bayar)),
            'bulan' => date('n', strtotime($request->tanggal_bayar)),
            'jumlah' => $request->jumlah,
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan' => $request->jenis_iuran . ($request->keterangan ? ' - ' . $request->keterangan : ''),
        ]);

        return back()->with('success', 'Iuran insidentil berhasil disimpan.');
    }

    public function destroyInsidentil(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function iuranKejuaraan()
    {
        $kejuaraaans = Kejuaraan::withCount([
            'pembayarans',
            'pembayarans as lunas_count' => fn($q) => $q->where('status', 'lunas'),
            'pembayarans as belum_bayar_count' => fn($q) => $q->where('status', 'belum_bayar'),
        ])->latest()->get();

        $availableCategories = Siswa::where('status', 'aktif')
            ->distinct()->pluck('kelas')->sort()->values();

        return view('admin.keuangan.iuran-kejuaraan', compact('kejuaraaans', 'availableCategories'));
    }

    public function storeKejuaraan(Request $request)
    {
        $request->validate([
            'nama_kejuaraan'    => 'required|string|max:255',
            'penyelenggara'     => 'required|string|max:255',
            'tanggal_kejuaraan' => 'required|date',
            'lokasi'            => 'required|string|max:255',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'batas_pendaftaran' => 'required|date',
            'deskripsi'         => 'required|string',
            'kategori'          => 'required|array|min:1',
        ]);

        Kejuaraan::create($request->all());

        return back()->with('success', 'Kejuaraan berhasil ditambahkan.');
    }

    public function updateStatusKejuaraan(Request $request, $id)
    {
        $request->validate([
            'status'     => 'required|in:akan_datang,pendaftaran,pendaftaran_tutup,berlangsung,selesai,dibatalkan',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $kejuaraan = Kejuaraan::findOrFail($id);
        $kejuaraan->update(['status' => $request->status, 'keterangan' => $request->keterangan]);

        return back()->with('success', "Status kejuaraan berhasil diubah menjadi: {$kejuaraan->status_label}");
    }

    public function pesertaKejuaraan(Request $request, Kejuaraan $kejuaraan)
    {
        $filter = $request->get('filter', 'semua'); // semua, lunas, belum_bayar

        // Siswa yang sudah terdaftar sebagai peserta
        $pesertaQuery = KejuaraanPembayaran::where('kejuaraan_id', $kejuaraan->id)
            ->with('siswa');

        if ($filter === 'lunas') {
            $pesertaQuery->where('status', 'lunas');
        } elseif ($filter === 'belum_bayar') {
            $pesertaQuery->where('status', 'belum_bayar');
        }

        $peserta = $pesertaQuery->get()->sortBy('siswa.nama');

        // Siswa aktif yang belum terdaftar (untuk tambah peserta)
        $terdaftarIds = KejuaraanPembayaran::where('kejuaraan_id', $kejuaraan->id)->pluck('siswa_id');
        $siswaBelumDaftar = Siswa::where('status', 'aktif')
            ->whereNotIn('id', $terdaftarIds)
            ->orderBy('nama')->get();

        $stats = [
            'total'       => KejuaraanPembayaran::where('kejuaraan_id', $kejuaraan->id)->count(),
            'lunas'       => KejuaraanPembayaran::where('kejuaraan_id', $kejuaraan->id)->where('status', 'lunas')->count(),
            'belum_bayar' => KejuaraanPembayaran::where('kejuaraan_id', $kejuaraan->id)->where('status', 'belum_bayar')->count(),
            'total_terkumpul' => KejuaraanPembayaran::where('kejuaraan_id', $kejuaraan->id)->where('status', 'lunas')->sum('jumlah'),
        ];

        return view('admin.keuangan.peserta-kejuaraan', compact('kejuaraan', 'peserta', 'siswaBelumDaftar', 'stats', 'filter'));
    }

    public function tambahPeserta(Request $request, Kejuaraan $kejuaraan)
    {
        $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswas,id',
        ]);

        $added = 0;
        foreach ($request->siswa_ids as $siswaId) {
            KejuaraanPembayaran::firstOrCreate(
                ['kejuaraan_id' => $kejuaraan->id, 'siswa_id' => $siswaId],
                ['jumlah' => $kejuaraan->biaya_pendaftaran, 'status' => 'belum_bayar']
            );
            $added++;
        }

        return back()->with('success', "{$added} siswa berhasil ditambahkan sebagai peserta.");
    }

    public function bayarKejuaraan(Request $request, KejuaraanPembayaran $pembayaran)
    {
        $request->validate([
            'tanggal_bayar'      => 'required|date',
            'metode_pembayaran'  => 'required|string',
            'keterangan'         => 'nullable|string',
        ]);

        $pembayaran->update([
            'status'            => 'lunas',
            'tanggal_bayar'     => $request->tanggal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan'        => $request->keterangan,
        ]);

        // KejuaraanPembayaran dengan status=lunas dan tanggal_bayar sudah cukup
        // untuk masuk ke rekap keuangan via LaporanController::rekapKeuangan (sumber #3)

        return back()->with('success', "Pembayaran {$pembayaran->siswa->nama} berhasil dicatat.");
    }

    public function hapusPeserta(KejuaraanPembayaran $pembayaran)
    {
        $nama = $pembayaran->siswa->nama;
        $pembayaran->delete();
        return back()->with('success', "{$nama} berhasil dihapus dari daftar peserta.");
    }

    public function angsuran()
    {
        $siswas = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $angsurans = Angsuran::with(['siswa', 'cicilans'])
            ->latest()
            ->get();
        
        return view('admin.keuangan.angsuran', compact('siswas', 'angsurans'));
    }

    public function storeAngsuran(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_tagihan' => 'required|string',
            'total_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
        ]);

        Angsuran::create([
            'siswa_id' => $request->siswa_id,
            'jenis_tagihan' => $request->jenis_tagihan,
            'total_tagihan' => $request->total_tagihan,
            'total_dibayar' => 0,
            'sisa_tagihan' => $request->total_tagihan,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'keterangan' => $request->keterangan,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Angsuran berhasil ditambahkan.');
    }

    public function storeCicilan(Request $request)
    {
        $request->validate([
            'angsuran_id' => 'required|exists:angsurans,id',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
        ]);

        $angsuran = Angsuran::findOrFail($request->angsuran_id);

        // Cek jika pembayaran melebihi sisa
        if ($request->jumlah > $angsuran->sisa_tagihan) {
            return back()->withErrors(['jumlah' => 'Jumlah pembayaran melebihi sisa tagihan.']);
        }

        // Simpan cicilan
        AngsuranCicilan::create([
            'angsuran_id' => $angsuran->id,
            'cicilan_ke' => $angsuran->jumlah_cicilan + 1,
            'jumlah' => $request->jumlah,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
        ]);

        // Update angsuran
        $angsuran->total_dibayar += $request->jumlah;
        $angsuran->sisa_tagihan -= $request->jumlah;
        $angsuran->jumlah_cicilan += 1;
        
        if ($angsuran->sisa_tagihan <= 0) {
            $angsuran->status = 'lunas';
        }
        
        $angsuran->save();

        return back()->with('success', 'Cicilan berhasil dibayar.');
    }

    public function destroyAngsuran(Angsuran $angsuran)
    {
        $angsuran->delete();
        return back()->with('success', 'Data angsuran berhasil dihapus.');
    }

    public function pendapatanLain(Request $request)
    {
        $bulan    = $request->get('bulan');
        $tahun    = $request->get('tahun', date('Y'));
        $kategori = $request->get('kategori');
        $status   = $request->get('status');

        $query = PendapatanLain::query();
        if ($bulan)    $query->whereMonth('tanggal', $bulan);
        if ($tahun)    $query->whereYear('tanggal', $tahun);
        if ($kategori) $query->where('kategori', $kategori);
        if ($status)   $query->where('status', $status);

        $pendapatans = $query->orderBy('tanggal', 'desc')->get();

        $stats = [
            'bulan_ini'   => PendapatanLain::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->where('status','diterima')->sum('jumlah'),
            'tahun_ini'   => PendapatanLain::whereYear('tanggal', date('Y'))->where('status','diterima')->sum('jumlah'),
            'pending'     => PendapatanLain::where('status', 'pending')->count(),
            'total_semua' => PendapatanLain::where('status','diterima')->sum('jumlah'),
        ];

        return view('admin.keuangan.pendapatan-lain', compact('pendapatans', 'stats'));
    }

    public function storePendapatanLain(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'kategori' => 'required|string',
            'deskripsi'=> 'required|string|max:255',
            'jumlah'   => 'required|numeric|min:1',
            'status'   => 'required|in:diterima,pending',
        ]);

        PendapatanLain::create($request->all());
        return back()->with('success', 'Pendapatan berhasil ditambahkan.');
    }

    public function updatePendapatanLain(Request $request, PendapatanLain $pendapatanLain)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'kategori' => 'required|string',
            'deskripsi'=> 'required|string|max:255',
            'jumlah'   => 'required|numeric|min:1',
            'status'   => 'required|in:diterima,pending',
        ]);

        $pendapatanLain->update($request->all());
        return back()->with('success', 'Pendapatan berhasil diperbarui.');
    }

    public function destroyPendapatanLain(PendapatanLain $pendapatanLain)
    {
        $pendapatanLain->delete();
        return back()->with('success', 'Pendapatan berhasil dihapus.');
    }

    // ── PENDAPATAN JERSEY ─────────────────────────────────────────
    public function pendapatanJersey(Request $request)
    {
        $bulan  = $request->get('bulan', date('m'));
        $tahun  = $request->get('tahun', date('Y'));
        $status = $request->get('status');

        $query = JerseyOrder::with(['siswa', 'jerseySize'])
            ->whereMonth('tanggal_pesan', $bulan)
            ->whereYear('tanggal_pesan', $tahun);

        if ($status) $query->where('status', $status);

        $orders = $query->orderBy('tanggal_pesan', 'desc')->get();

        $stats = [
            'total_order'      => JerseyOrder::whereMonth('tanggal_pesan', $bulan)->whereYear('tanggal_pesan', $tahun)->count(),
            'total_lunas'      => JerseyOrder::whereMonth('tanggal_pesan', $bulan)->whereYear('tanggal_pesan', $tahun)->where('status_bayar', 'lunas')->count(),
            'total_pendapatan' => JerseyOrder::whereMonth('tanggal_pesan', $bulan)->whereYear('tanggal_pesan', $tahun)->where('status_bayar', 'lunas')->sum('harga'),
            'total_pending'    => JerseyOrder::whereMonth('tanggal_pesan', $bulan)->whereYear('tanggal_pesan', $tahun)->where('status_bayar', 'belum_bayar')->count(),
        ];

        return view('admin.keuangan.pendapatan-jersey', compact('orders', 'stats', 'bulan', 'tahun'));
    }

    // ── PENGELUARAN ───────────────────────────────────────────────
    public function pengeluaran(Request $request)
    {
        $bulan    = $request->get('bulan', date('m'));
        $tahun    = $request->get('tahun', date('Y'));
        $kategori = $request->get('kategori');

        $query = Pengeluaran::query()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($kategori) $query->where('kategori', $kategori);

        $pengeluarans = $query->orderBy('tanggal', 'desc')->get();

        $stats = [
            'bulan_ini'   => Pengeluaran::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->sum('jumlah'),
            'tahun_ini'   => Pengeluaran::whereYear('tanggal', date('Y'))->sum('jumlah'),
            'terbesar'    => Pengeluaran::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->max('jumlah') ?? 0,
            'total_filter' => $pengeluarans->sum('jumlah'),
        ];

        $kategoriList = ['operasional', 'gaji', 'peralatan', 'maintenance', 'event', 'administrasi', 'lainnya'];

        return view('admin.keuangan.pengeluaran', compact('pengeluarans', 'stats', 'bulan', 'tahun', 'kategoriList'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'kategori'  => 'required|string',
            'keterangan'=> 'required|string|max:255',
            'jumlah'    => 'required|numeric|min:1',
        ]);

        Pengeluaran::create($request->all());
        return back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'kategori'  => 'required|string',
            'keterangan'=> 'required|string|max:255',
            'jumlah'    => 'required|numeric|min:1',
        ]);

        $pengeluaran->update($request->all());
        return back()->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroyPengeluaran(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
