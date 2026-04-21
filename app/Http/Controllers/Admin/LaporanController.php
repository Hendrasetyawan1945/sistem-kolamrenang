<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Models\TemplateRapor;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\PendapatanLain;
use App\Models\KejuaraanPembayaran;
use App\Models\AngsuranCicilan;
use App\Models\JerseyOrder;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function rekapKeuangan(Request $request)
    {
        $bulan    = $request->get('bulan', date('m'));
        $tahun    = $request->get('tahun', date('Y'));
        $jenis    = $request->get('jenis');     // pemasukan / pengeluaran / ''
        $kategori = $request->get('kategori'); // filter kategori

        // ── KUMPULKAN SEMUA TRANSAKSI ─────────────────────────────
        $semua = collect();

        // 1. Iuran Rutin
        Pembayaran::with('siswa')
            ->where('jenis_pembayaran', 'iuran_rutin')
            ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal_bayar,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Iuran Rutin',
                'deskripsi' => ($p->siswa->nama ?? '-') . ' — Bln ' . $p->bulan . '/' . $p->tahun,
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => 'Siswa: ' . ($p->siswa->kelas ?? ''),
            ]));

        // 1b. Biaya Pendaftaran
        Pembayaran::with('siswa')
            ->where('jenis_pembayaran', 'biaya_pendaftaran')
            ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal_bayar,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Biaya Pendaftaran',
                'deskripsi' => 'Pendaftaran: ' . ($p->siswa->nama ?? '-') . ' — ' . ($p->siswa->kelas ?? ''),
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => '',
            ]));

        Pembayaran::with('siswa')
            ->where('jenis_pembayaran', 'iuran_insidentil')
            ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal_bayar,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Iuran Insidentil',
                'deskripsi' => ($p->siswa->nama ?? '-') . ($p->keterangan ? ' — ' . $p->keterangan : ''),
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => '',
            ]));

        // 3. Iuran Kejuaraan
        KejuaraanPembayaran::with(['siswa','kejuaraan'])
            ->where('status', 'lunas')
            ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal_bayar,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Iuran Kejuaraan',
                'deskripsi' => ($p->siswa->nama ?? '-') . ' — ' . ($p->kejuaraan->nama_kejuaraan ?? '-'),
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => '',
            ]));

        // 4. Angsuran
        AngsuranCicilan::with('angsuran.siswa')
            ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal_bayar,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Angsuran',
                'deskripsi' => ($p->angsuran->siswa->nama ?? '-') . ' — Cicilan ke-' . $p->cicilan_ke,
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => $p->angsuran->jenis_tagihan ?? '',
            ]));

        // 5. Penjualan Jersey — gunakan status_bayar=lunas (bukan status=selesai)
        JerseyOrder::with(['siswa','jerseySize'])
            ->where('status_bayar', 'lunas')
            ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal_bayar,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Penjualan Jersey',
                'deskripsi' => ($p->siswa->nama ?? '-') . ' — ' . ($p->jerseySize->nama_size ?? '') . ($p->nama_punggung ? ' (' . $p->nama_punggung . ')' : ''),
                'jumlah'    => (float)$p->harga,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => '',
            ]));

        // 6. Pendapatan Lain
        PendapatanLain::where('status', 'diterima')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal,
                'jenis'     => 'pemasukan',
                'kategori'  => 'Pendapatan Lain',
                'deskripsi' => $p->deskripsi . ($p->sumber ? ' (' . $p->sumber . ')' : ''),
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => $p->kategori_label,
            ]));

        // 7. Pengeluaran
        Pengeluaran::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->get()->each(fn($p) => $semua->push([
                'tanggal'   => $p->tanggal,
                'jenis'     => 'pengeluaran',
                'kategori'  => 'Pengeluaran — ' . ucfirst($p->kategori),
                'deskripsi' => $p->keterangan,
                'jumlah'    => (float)$p->jumlah,
                'metode'    => $p->metode_pembayaran ?? '-',
                'ref'       => '',
            ]));

        // ── FILTER ────────────────────────────────────────────────
        $filtered = $semua;
        if ($jenis)    $filtered = $filtered->where('jenis', $jenis);
        if ($kategori) $filtered = $filtered->filter(fn($t) => str_contains($t['kategori'], $kategori));
        $filtered = $filtered->sortBy('tanggal')->values();

        // Running saldo
        $saldo = 0;
        $filtered = $filtered->map(function ($t) use (&$saldo) {
            $saldo += $t['jenis'] === 'pemasukan' ? $t['jumlah'] : -$t['jumlah'];
            $t['saldo'] = $saldo;
            return $t;
        });

        // ── SUMMARY ───────────────────────────────────────────────
        $totalPemasukan   = $semua->where('jenis','pemasukan')->sum('jumlah');
        $totalPengeluaran = $semua->where('jenis','pengeluaran')->sum('jumlah');
        $saldoBersih      = $totalPemasukan - $totalPengeluaran;

        $breakdownPemasukan = collect([
            ['label' => 'Iuran Rutin',       'jumlah' => $semua->where('kategori','Iuran Rutin')->sum('jumlah'),       'icon' => 'fa-calendar-check',       'color' => '#4caf50'],
            ['label' => 'Biaya Pendaftaran', 'jumlah' => $semua->where('kategori','Biaya Pendaftaran')->sum('jumlah'), 'icon' => 'fa-user-plus',            'color' => '#8bc34a'],
            ['label' => 'Iuran Insidentil',  'jumlah' => $semua->where('kategori','Iuran Insidentil')->sum('jumlah'),  'icon' => 'fa-exclamation-triangle', 'color' => '#ff9800'],
            ['label' => 'Iuran Kejuaraan',   'jumlah' => $semua->where('kategori','Iuran Kejuaraan')->sum('jumlah'),   'icon' => 'fa-trophy',               'color' => '#ffc107'],
            ['label' => 'Angsuran',          'jumlah' => $semua->where('kategori','Angsuran')->sum('jumlah'),          'icon' => 'fa-credit-card',          'color' => '#2196f3'],
            ['label' => 'Penjualan Jersey',  'jumlah' => $semua->where('kategori','Penjualan Jersey')->sum('jumlah'),  'icon' => 'fa-tshirt',               'color' => '#9c27b0'],
            ['label' => 'Pendapatan Lain',   'jumlah' => $semua->where('kategori','Pendapatan Lain')->sum('jumlah'),   'icon' => 'fa-plus-circle',          'color' => '#00bcd4'],
        ])->sortByDesc('jumlah');

        $pengeluaranPerKategori = Pengeluaran::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->selectRaw('kategori, SUM(jumlah) as total, COUNT(*) as count')
            ->groupBy('kategori')->orderByDesc('total')->get();

        // Tren 6 bulan
        $tren = collect();
        for ($i = 5; $i >= 0; $i--) {
            $m = date('m', strtotime("-{$i} months", mktime(0,0,0,$bulan,1,$tahun)));
            $y = date('Y', strtotime("-{$i} months", mktime(0,0,0,$bulan,1,$tahun)));
            $masuk  = Pembayaran::whereMonth('tanggal_bayar',$m)->whereYear('tanggal_bayar',$y)->sum('jumlah')
                    + KejuaraanPembayaran::where('status','lunas')->whereMonth('tanggal_bayar',$m)->whereYear('tanggal_bayar',$y)->sum('jumlah')
                    + PendapatanLain::where('status','diterima')->whereMonth('tanggal',$m)->whereYear('tanggal',$y)->sum('jumlah')
                    + JerseyOrder::where('status_bayar','lunas')->whereMonth('tanggal_bayar',$m)->whereYear('tanggal_bayar',$y)->sum('harga')
                    + AngsuranCicilan::whereMonth('tanggal_bayar',$m)->whereYear('tanggal_bayar',$y)->sum('jumlah');
            $keluar = Pengeluaran::whereMonth('tanggal',$m)->whereYear('tanggal',$y)->sum('jumlah');
            $tren->push(['bulan' => date('M Y', mktime(0,0,0,$m,1,$y)), 'masuk' => $masuk, 'keluar' => $keluar]);
        }

        // Daftar kategori unik untuk filter dropdown
        $kategoriList = $semua->pluck('kategori')->unique()->sort()->values();

        $namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];

        // ── MONITORING IURAN RUTIN ────────────────────────────────
        $siswaAktif = Siswa::where('status', 'aktif')->orderBy('kelas')->orderBy('nama')->get();
        $sudahBayarIds = Pembayaran::where('jenis_pembayaran', 'iuran_rutin')
            ->where('bulan', (int)$bulan)->where('tahun', (int)$tahun)
            ->pluck('siswa_id')->toArray();

        $sudahBayar  = $siswaAktif->whereIn('id', $sudahBayarIds);
        $belumBayar  = $siswaAktif->whereNotIn('id', $sudahBayarIds);

        $totalSudahBayar  = $sudahBayar->count();
        $totalBelumBayar  = $belumBayar->count();
        $totalSiswaAktif  = $siswaAktif->count();

        // Nominal iuran per siswa (ambil dari pembayaran yang ada, atau 0)
        $nominalIuran = Pembayaran::where('jenis_pembayaran', 'iuran_rutin')
            ->where('bulan', (int)$bulan)->where('tahun', (int)$tahun)
            ->avg('jumlah') ?? 0;

        // ── MONITORING PENDAFTARAN ────────────────────────────────
        $calonSiswa = Siswa::where('status', 'calon')->orderBy('created_at', 'desc')->get();
        $siswaBaru  = Siswa::where('status', 'aktif')
            ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)
            ->orderBy('nama')->get();

        return view('admin.laporan.rekap-keuangan', compact(
            'bulan','tahun','namaBulan','jenis','kategori',
            'filtered','semua','kategoriList',
            'totalPemasukan','totalPengeluaran','saldoBersih',
            'breakdownPemasukan','pengeluaranPerKategori','tren',
            'siswaAktif','sudahBayar','belumBayar',
            'totalSudahBayar','totalBelumBayar','totalSiswaAktif','nominalIuran',
            'calonSiswa','siswaBaru'
        ));
    }

    public function rekapTransaksi(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $jenis = $request->get('jenis');

        $transaksis = collect();

        if (!$jenis || $jenis === 'pemasukan') {
            // 1. Iuran Rutin
            Pembayaran::with('siswa')
                ->where('jenis_pembayaran', 'iuran_rutin')
                ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal_bayar,
                    'jenis'    => 'pemasukan',
                    'kategori' => 'Iuran Rutin',
                    'deskripsi'=> ($p->siswa->nama ?? '-') . ' Bln ' . $p->bulan . '/' . $p->tahun,
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));

            // 2. Biaya Pendaftaran
            Pembayaran::with('siswa')
                ->where('jenis_pembayaran', 'biaya_pendaftaran')
                ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal_bayar,
                    'jenis'    => 'pemasukan',
                    'kategori' => 'Biaya Pendaftaran',
                    'deskripsi'=> 'Pendaftaran: ' . ($p->siswa->nama ?? '-'),
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));

            // 3. Iuran Insidentil
            Pembayaran::with('siswa')
                ->where('jenis_pembayaran', 'iuran_insidentil')
                ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal_bayar,
                    'jenis'    => 'pemasukan',
                    'kategori' => 'Iuran Insidentil',
                    'deskripsi'=> ($p->siswa->nama ?? '-') . ($p->keterangan ? ' — ' . $p->keterangan : ''),
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));

            // 4. Iuran Kejuaraan
            KejuaraanPembayaran::with(['siswa','kejuaraan'])
                ->where('status', 'lunas')
                ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal_bayar,
                    'jenis'    => 'pemasukan',
                    'kategori' => 'Iuran Kejuaraan',
                    'deskripsi'=> ($p->siswa->nama ?? '-') . ' — ' . ($p->kejuaraan->nama_kejuaraan ?? '-'),
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));

            // 5. Angsuran
            AngsuranCicilan::with('angsuran.siswa')
                ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal_bayar,
                    'jenis'    => 'pemasukan',
                    'kategori' => 'Angsuran',
                    'deskripsi'=> ($p->angsuran->siswa->nama ?? '-') . ' — Cicilan ke-' . $p->cicilan_ke,
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));

            // 6. Penjualan Jersey (status_bayar=lunas)
            JerseyOrder::with(['siswa','jerseySize'])
                ->where('status_bayar', 'lunas')
                ->whereMonth('tanggal_bayar', $bulan)->whereYear('tanggal_bayar', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal_bayar,
                    'jenis'    => 'pemasukan',
                    'kategori' => 'Penjualan Jersey',
                    'deskripsi'=> ($p->siswa->nama ?? '-') . ' — ' . ($p->jerseySize->nama_size ?? '') . ($p->nama_punggung ? ' (' . $p->nama_punggung . ')' : ''),
                    'jumlah'   => (float)$p->harga,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));

            // 7. Pendapatan Lain
            PendapatanLain::where('status', 'diterima')
                ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal,
                    'jenis'    => 'pemasukan',
                    'kategori' => $p->kategori_label,
                    'deskripsi'=> $p->deskripsi . ($p->sumber ? ' (' . $p->sumber . ')' : ''),
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));
        }

        if (!$jenis || $jenis === 'pengeluaran') {
            Pengeluaran::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                ->get()->each(fn($p) => $transaksis->push([
                    'tanggal'  => $p->tanggal,
                    'jenis'    => 'pengeluaran',
                    'kategori' => ucfirst($p->kategori ?? 'Operasional'),
                    'deskripsi'=> $p->keterangan ?? '-',
                    'jumlah'   => (float)$p->jumlah,
                    'metode'   => $p->metode_pembayaran ?? '-',
                ]));
        }

        $transaksis = $transaksis->sortBy('tanggal')->values();

        $saldo = 0;
        $transaksis = $transaksis->map(function ($t) use (&$saldo) {
            $saldo += $t['jenis'] === 'pemasukan' ? $t['jumlah'] : -$t['jumlah'];
            $t['saldo'] = $saldo;
            return $t;
        });

        $totalPemasukan   = $transaksis->where('jenis','pemasukan')->sum('jumlah');
        $totalPengeluaran = $transaksis->where('jenis','pengeluaran')->sum('jumlah');
        $saldoBersih      = $totalPemasukan - $totalPengeluaran;

        $perKategori = $transaksis->groupBy('kategori')->map(fn($g) => [
            'jenis' => $g->first()['jenis'],
            'total' => $g->sum('jumlah'),
            'count' => $g->count(),
        ])->sortByDesc('total');

        return view('admin.laporan.rekap-transaksi', compact(
            'transaksis','totalPemasukan','totalPengeluaran','saldoBersih',
            'perKategori','bulan','tahun','jenis'
        ));
    }

    public function rekapPembayaranIuran()
    {
        $bulan = request()->get('bulan', date('m'));
        $tahun = request()->get('tahun', date('Y'));
        $kelas = request()->get('kelas');

        $query = Siswa::where('status', 'aktif');
        
        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $siswas = $query->orderBy('nama')->get();

        // Load pembayaran untuk bulan/tahun ini
        $siswas->load(['pembayarans' => function ($q) use ($bulan, $tahun) {
            $q->where('jenis_pembayaran', 'iuran_rutin')
              ->where('bulan', $bulan)
              ->where('tahun', $tahun);
        }]);

        // Hitung statistik
        $totalSiswa = $siswas->count();
        $sudahBayar = $siswas->filter(function($siswa) {
            return $siswa->pembayarans->isNotEmpty();
        })->count();
        $belumBayar = $totalSiswa - $sudahBayar;
        
        $totalPendapatan = $siswas->sum(function($siswa) {
            return $siswa->pembayarans->sum('jumlah');
        });

        $kelasList = Siswa::where('status', 'aktif')
            ->distinct()
            ->pluck('kelas')
            ->sort();

        return view('admin.laporan.rekap-pembayaran-iuran', compact(
            'siswas', 
            'bulan', 
            'tahun', 
            'kelasList',
            'totalSiswa',
            'sudahBayar',
            'belumBayar',
            'totalPendapatan'
        ));
    }

    public function rekapJumlahSiswa(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Statistik per status
        $totalAktif    = Siswa::where('status', 'aktif')->count();
        $totalCalon    = Siswa::where('status', 'calon')->count();
        $totalCuti     = Siswa::where('status', 'cuti')->count();
        $totalNonaktif = Siswa::where('status', 'nonaktif')->count();
        $totalSemua    = $totalAktif + $totalCalon + $totalCuti + $totalNonaktif;

        // Breakdown per kelas (siswa aktif)
        $perKelas = Siswa::where('status', 'aktif')
            ->selectRaw('kelas, count(*) as total')
            ->groupBy('kelas')
            ->orderBy('total', 'desc')
            ->get();

        // Siswa baru per bulan di tahun ini
        $siswaBaru = [];
        for ($m = 1; $m <= 12; $m++) {
            $siswaBaru[$m] = Siswa::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Siswa nonaktif per bulan di tahun ini
        $siswaKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $siswaKeluar[$m] = Siswa::where('status', 'nonaktif')
                ->whereYear('updated_at', $tahun)
                ->whereMonth('updated_at', $m)
                ->count();
        }

        return view('admin.laporan.rekap-jumlah-siswa', compact(
            'totalAktif', 'totalCalon', 'totalCuti', 'totalNonaktif', 'totalSemua',
            'perKelas', 'siswaBaru', 'siswaKeluar', 'tahun'
        ));
    }

    public function rapor(Request $request)
    {
        $bulan     = $request->get('bulan', date('m'));
        $tahun     = $request->get('tahun', date('Y'));
        $kelas     = $request->get('kelas');
        $statusRapor = $request->get('status'); // draft, selesai, belum_dibuat

        $query = Siswa::where('status', 'aktif');
        if ($kelas) $query->where('kelas', $kelas);
        $siswas = $query->orderBy('kelas')->orderBy('nama')->get();

        // Load rapor bulan ini
        $siswas->load(['rapors' => fn($q) => $q->where('bulan', (int)$bulan)->where('tahun', (int)$tahun)]);

        // Filter by status rapor
        if ($statusRapor === 'selesai') {
            $siswas = $siswas->filter(fn($s) => $s->rapors->where('status','selesai')->isNotEmpty());
        } elseif ($statusRapor === 'draft') {
            $siswas = $siswas->filter(fn($s) => $s->rapors->where('status','draft')->isNotEmpty());
        } elseif ($statusRapor === 'belum_dibuat') {
            $siswas = $siswas->filter(fn($s) => $s->rapors->isEmpty());
        }

        $kelasList   = Siswa::where('status','aktif')->distinct()->pluck('kelas')->sort();
        $templates   = TemplateRapor::where('aktif', true)->get();

        $stats = [
            'total'       => $siswas->count(),
            'selesai'     => $siswas->filter(fn($s) => $s->rapors->where('status','selesai')->isNotEmpty())->count(),
            'draft'       => $siswas->filter(fn($s) => $s->rapors->where('status','draft')->isNotEmpty())->count(),
            'belum'       => $siswas->filter(fn($s) => $s->rapors->isEmpty())->count(),
        ];

        $namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];

        return view('admin.rapor.index', compact(
            'siswas','bulan','tahun','kelasList','templates','stats','namaBulan','statusRapor','kelas'
        ));
    }

    public function raporSiswa(Request $request, Siswa $siswa)
    {
        $bulan  = $request->get('bulan', date('m'));
        $tahun  = $request->get('tahun', date('Y'));

        $rapor = Rapor::where('siswa_id', $siswa->id)
            ->where('bulan', (int)$bulan)->where('tahun', (int)$tahun)
            ->with('templateRapor')->first();

        $templates   = TemplateRapor::where('aktif', true)->get();
        $personalBests = \App\Models\CatatanWaktu::where('siswa_id', $siswa->id)
            ->select('nomor_lomba','jenis_kolam', \DB::raw('MIN(waktu_detik) as best_time'), \DB::raw('MIN(waktu) as best_waktu'))
            ->groupBy('nomor_lomba','jenis_kolam')->orderBy('nomor_lomba')->get();

        // Kehadiran bulan ini dari absensi
        $totalPertemuan = \App\Models\Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count();
        $hadir = \App\Models\Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->where('status', 'hadir')->count();

        $namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];

        return view('admin.rapor.siswa', compact(
            'siswa','rapor','templates','personalBests','bulan','tahun','namaBulan','totalPertemuan','hadir'
        ));
    }

    public function storeRapor(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'bulan'    => 'required|integer',
            'tahun'    => 'required|integer',
        ]);

        $komponen = [];
        if ($request->komponen_nama) {
            foreach ($request->komponen_nama as $i => $nama) {
                $komponen[] = [
                    'nama'       => $nama,
                    'nilai'      => $request->komponen_nilai[$i] ?? 0,
                    'keterangan' => $request->komponen_keterangan[$i] ?? '',
                ];
            }
        }

        Rapor::updateOrCreate(
            ['siswa_id' => $request->siswa_id, 'bulan' => $request->bulan, 'tahun' => $request->tahun],
            [
                'template_rapor_id' => $request->template_rapor_id,
                'periode'           => date('F Y', mktime(0,0,0,$request->bulan,1,$request->tahun)),
                'nilai'             => $komponen,
                'kehadiran'         => $request->kehadiran ?? 0,
                'total_pertemuan'   => $request->total_pertemuan ?? 0,
                'catatan_coach'     => $request->catatan_coach,
                'catatan_umum'      => $request->catatan_umum,
                'status'            => $request->status ?? 'draft',
            ]
        );

        return back()->with('success', 'Rapor berhasil disimpan.');
    }

    public function updateRapor(Request $request, Rapor $rapor)
    {
        $komponen = [];
        if ($request->komponen_nama) {
            foreach ($request->komponen_nama as $i => $nama) {
                $komponen[] = [
                    'nama'       => $nama,
                    'nilai'      => $request->komponen_nilai[$i] ?? 0,
                    'keterangan' => $request->komponen_keterangan[$i] ?? '',
                ];
            }
        }

        $rapor->update([
            'template_rapor_id' => $request->template_rapor_id,
            'nilai'             => $komponen,
            'kehadiran'         => $request->kehadiran ?? 0,
            'total_pertemuan'   => $request->total_pertemuan ?? 0,
            'catatan_coach'     => $request->catatan_coach,
            'catatan_umum'      => $request->catatan_umum,
            'status'            => $request->status ?? 'draft',
        ]);

        return back()->with('success', 'Rapor berhasil diperbarui.');
    }

    public function destroyRapor(Rapor $rapor)
    {
        $rapor->delete();
        return back()->with('success', 'Rapor berhasil dihapus.');
    }

    public function templateRapor()
    {
        $templates = TemplateRapor::withCount('rapors')->get();
        return view('admin.rapor.template-rapor', compact('templates'));
    }

    public function storeTemplateRapor(Request $request)
    {
        $request->validate([
            'nama_template' => 'required|string|max:255',
            'kelas' => 'required|string',
            'komponen' => 'required|array|min:1',
            'komponen.*.nama' => 'required|string',
            'komponen.*.bobot' => 'required|integer|min:1|max:100',
        ]);

        TemplateRapor::create([
            'nama_template' => $request->nama_template,
            'kelas' => $request->kelas,
            'deskripsi' => $request->deskripsi,
            'komponen' => $request->komponen,
            'template_catatan' => $request->template_catatan,
            'aktif' => true,
        ]);

        return back()->with('success', 'Template rapor berhasil disimpan.');
    }

    public function destroyTemplateRapor(TemplateRapor $templateRapor)
    {
        $templateRapor->delete();
        return back()->with('success', 'Template berhasil dihapus.');
    }

}
