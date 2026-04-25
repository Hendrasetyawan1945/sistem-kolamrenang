<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Angsuran;
use App\Models\AngsuranCicilan;
use App\Models\KejuaraanPembayaran;
use App\Models\JerseyOrder;
use App\Models\PendapatanLain;
use App\Models\Pengeluaran;
use App\Models\Kelas;
use App\Models\Coach;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $today    = date('Y-m-d');

        // ── STATS ─────────────────────────────────────────────────
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();
        $totalCalonSiswa = Siswa::where('status', 'calon')->count();

        // ── STATISTIK AKUN ─────────────────────────────────────────
        $siswaAktifDenganAkun = Siswa::where('status', 'aktif')->whereHas('user')->count();
        $siswaAktifTanpaAkun = $totalSiswaAktif - $siswaAktifDenganAkun;
        
        // Menggunakan helper untuk validasi email yang kompatibel dengan SQLite
        $siswaEmailValid = EmailHelper::countValidEmails(
            Siswa::where('status', 'aktif')->whereNotNull('email')->where('email', '!=', '')
        );

        // ── TOTAL PENDAPATAN (semua sumber, sama persis dengan Rekap Keuangan) ──
        $totalPendapatan =
            Pembayaran::whereMonth('tanggal_bayar', $bulanIni)->whereYear('tanggal_bayar', $tahunIni)->sum('jumlah')
            + AngsuranCicilan::whereMonth('tanggal_bayar', $bulanIni)->whereYear('tanggal_bayar', $tahunIni)->sum('jumlah')
            + KejuaraanPembayaran::where('status','lunas')->whereMonth('tanggal_bayar', $bulanIni)->whereYear('tanggal_bayar', $tahunIni)->sum('jumlah')
            + JerseyOrder::where('status_bayar','lunas')->whereMonth('tanggal_bayar', $bulanIni)->whereYear('tanggal_bayar', $tahunIni)->sum('harga')
            + PendapatanLain::where('status','diterima')->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('jumlah');

        $siswaBelumBayar = Siswa::where('status', 'aktif')
            ->whereDoesntHave('pembayarans', fn($q) => $q->where('jenis_pembayaran','iuran_rutin')
                ->where('bulan', $bulanIni)->where('tahun', $tahunIni))->count();

        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunBulanLalu = date('Y', strtotime('-1 month'));
        $totalBulanLalu =
            Pembayaran::whereMonth('tanggal_bayar', $bulanLalu)->whereYear('tanggal_bayar', $tahunBulanLalu)->sum('jumlah')
            + AngsuranCicilan::whereMonth('tanggal_bayar', $bulanLalu)->whereYear('tanggal_bayar', $tahunBulanLalu)->sum('jumlah')
            + KejuaraanPembayaran::where('status','lunas')->whereMonth('tanggal_bayar', $bulanLalu)->whereYear('tanggal_bayar', $tahunBulanLalu)->sum('jumlah')
            + JerseyOrder::where('status_bayar','lunas')->whereMonth('tanggal_bayar', $bulanLalu)->whereYear('tanggal_bayar', $tahunBulanLalu)->sum('harga')
            + PendapatanLain::where('status','diterima')->whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunBulanLalu)->sum('jumlah');

        $perubahanPendapatan = $totalBulanLalu > 0
            ? (($totalPendapatan - $totalBulanLalu) / $totalBulanLalu * 100) : 0;

        $siswaBaru = Siswa::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();

        // ── PEMBAYARAN HARI INI (semua sumber) ────────────────────
        $bayarHariIni = Pembayaran::whereDate('tanggal_bayar', $today)
            ->selectRaw('jenis_pembayaran, SUM(jumlah) as total')
            ->groupBy('jenis_pembayaran')->get()->keyBy('jenis_pembayaran');

        $iuranRutinHariIni      = $bayarHariIni->get('iuran_rutin')?->total ?? 0;
        $iuranInsidentilHariIni = $bayarHariIni->get('iuran_insidentil')?->total ?? 0;

        $jerseyHariIni     = JerseyOrder::where('status_bayar','lunas')->whereDate('tanggal_bayar', $today)->sum('harga');
        $kejuaraanHariIni  = KejuaraanPembayaran::where('status','lunas')->whereDate('tanggal_bayar', $today)->sum('jumlah');
        $angsuranHariIni   = AngsuranCicilan::whereDate('tanggal_bayar', $today)->sum('jumlah');
        $lainHariIni       = PendapatanLain::where('status','diterima')->whereDate('tanggal', $today)->sum('jumlah');

        $totalBayarHariIni = $iuranRutinHariIni + $iuranInsidentilHariIni
            + $jerseyHariIni + $kejuaraanHariIni + $angsuranHariIni + $lainHariIni;

        // ── ULANG TAHUN HARI INI ──────────────────────────────────
        $ultahHariIni = Siswa::where('status', 'aktif')
            ->whereMonth('tanggal_lahir', date('m'))
            ->whereDay('tanggal_lahir', date('d'))
            ->get();

        // ── KELAS HARI INI (dari jadwal kelas) ────────────────────
        $hariIni = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
                    'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'][date('l')];
        $kelasHariIni = Kelas::with('coach')->where('aktif', true)->get()
            ->filter(fn($k) => !empty($k->jadwal) && collect($k->jadwal)->contains('hari', $hariIni))
            ->map(fn($k) => [
                'kelas'   => $k,
                'jadwal'  => collect($k->jadwal)->firstWhere('hari', $hariIni),
                'jumlah_siswa' => Siswa::where('status','aktif')->where('kelas',$k->nama_kelas)->count(),
            ])->sortBy('jadwal.jam_mulai')->values();

        // ── SISWA SUDAH BAYAR BULAN INI ───────────────────────────
        $sudahBayarIds = Pembayaran::where('jenis_pembayaran','iuran_rutin')
            ->where('bulan',(int)$bulanIni)->where('tahun',(int)$tahunIni)->pluck('siswa_id');
        $totalSudahBayar = $sudahBayarIds->count();

        // ── KELAS DENGAN BELUM BAYAR TERBANYAK ───────────────────
        $belumBayarPerKelas = Siswa::where('status','aktif')
            ->whereNotIn('id', $sudahBayarIds)
            ->selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')->orderByDesc('total')->first();

        return view('admin.dashboard', compact(
            'totalSiswaAktif','totalCalonSiswa','totalPendapatan','siswaBelumBayar',
            'perubahanPendapatan','siswaBaru','bulanIni','tahunIni',
            'iuranRutinHariIni','iuranInsidentilHariIni','totalBayarHariIni',
            'ultahHariIni','kelasHariIni','totalSudahBayar','belumBayarPerKelas',
            'siswaAktifDenganAkun','siswaAktifTanpaAkun','siswaEmailValid'
        ));
    }

    public function cariSiswa(Request $request)
    {
        // Logic untuk cari siswa
        return view('admin.cari-siswa');
    }

    public function belumBayar(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $kelas = $request->get('kelas');

        $query = Siswa::where('status', 'aktif')
            ->whereDoesntHave('pembayarans', function($q) use ($bulan, $tahun) {
                $q->where('jenis_pembayaran', 'iuran_rutin')
                  ->where('bulan', $bulan)
                  ->where('tahun', $tahun);
            });
        
        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $siswas = $query->orderBy('nama')->get();

        $kelasList = Siswa::where('status', 'aktif')
            ->distinct()
            ->pluck('kelas')
            ->sort();

        return view('admin.belum-bayar', compact('siswas', 'bulan', 'tahun', 'kelasList'));
    }

    public function daftarBaru()
    {
        $kelasList = Kelas::where('aktif', true)->with('coach')->orderBy('nama_kelas')->get();
        return view('admin.daftar-baru', compact('kelasList'));
    }

    public function storeDaftarBaru(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas'         => 'required|string',
            'alamat'        => 'required|string',
            'nama_ortu'     => 'required|string|max:255',
            'telepon'       => 'required|string|max:20',
            'email'         => 'nullable|email|max:255',
            'paket'         => 'required|string',
        ]);

        Siswa::create([
            'nama'          => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas'         => $request->kelas,
            'alamat'        => $request->alamat,
            'nama_ortu'     => $request->nama_ortu,
            'telepon'       => $request->telepon,
            'email'         => $request->email,
            'paket'         => $request->paket,
            'catatan'       => $request->catatan,
            'status'        => 'calon',
        ]);

        return redirect()->route('admin.calon-siswa')->with('success', 'Data siswa baru berhasil disimpan!');
    }

    public function pendapatan()
    {
        $bulan = request()->get('bulan', date('m'));
        $tahun = request()->get('tahun', date('Y'));

        // Ambil semua pembayaran
        $pembayarans = Pembayaran::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->with('siswa')
            ->latest('tanggal_bayar')
            ->get();

        // Ambil cicilan angsuran
        $cicilans = AngsuranCicilan::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->with('angsuran.siswa')
            ->latest('tanggal_bayar')
            ->get();

        $totalPembayaran = $pembayarans->sum('jumlah');
        $totalCicilan = $cicilans->sum('jumlah');
        $totalPendapatan = $totalPembayaran + $totalCicilan;

        return view('admin.pendapatan', compact('pembayarans', 'cicilans', 'bulan', 'tahun', 'totalPembayaran', 'totalCicilan', 'totalPendapatan'));
    }

    public function pengeluaran()
    {
        $bulan = request()->get('bulan', date('m'));
        $tahun = request()->get('tahun', date('Y'));

        $pengeluarans = Pengeluaran::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->latest('tanggal')
            ->get();

        $totalPengeluaran = $pengeluarans->sum('jumlah');

        return view('admin.pengeluaran', compact('pengeluarans', 'bulan', 'tahun', 'totalPengeluaran'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        Pengeluaran::create($request->all());

        return back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function destroyPengeluaran(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
