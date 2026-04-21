<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\JerseyController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\AbsensiController;

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/cari-siswa', [DashboardController::class, 'cariSiswa'])->name('cari-siswa');
    Route::get('/belum-bayar', [DashboardController::class, 'belumBayar'])->name('belum-bayar');
    Route::get('/daftar-baru', [DashboardController::class, 'daftarBaru'])->name('daftar-baru');
    Route::post('/daftar-baru', [DashboardController::class, 'storeDaftarBaru'])->name('daftar-baru.store');
    Route::get('/pendapatan', [DashboardController::class, 'pendapatan'])->name('pendapatan');
    Route::get('/pengeluaran', [DashboardController::class, 'pengeluaran'])->name('pengeluaran');
    Route::post('/pengeluaran', [DashboardController::class, 'storePengeluaran'])->name('pengeluaran.store');
    Route::delete('/pengeluaran/{pengeluaran}', [DashboardController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');

    // Absensi Routes
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');

    // Keuangan Routes
    Route::get('/iuran-rutin', [KeuanganController::class, 'iuranRutin'])->name('iuran-rutin');
    Route::post('/pembayaran', [KeuanganController::class, 'storePembayaran'])->name('pembayaran.store');
    Route::get('/paket-kuota', [KeuanganController::class, 'paketKuota'])->name('paket-kuota');
    Route::post('/paket-kuota', [KeuanganController::class, 'storePaketKuota'])->name('paket-kuota.store');
    Route::delete('/paket-kuota/{paketKuota}', [KeuanganController::class, 'destroyPaketKuota'])->name('paket-kuota.destroy');
    Route::get('/iuran-insidentil', [KeuanganController::class, 'iuranInsidentil'])->name('iuran-insidentil');
    Route::post('/iuran-insidentil', [KeuanganController::class, 'storeInsidentil'])->name('iuran-insidentil.store');
    Route::delete('/iuran-insidentil/{pembayaran}', [KeuanganController::class, 'destroyInsidentil'])->name('iuran-insidentil.destroy');
    Route::get('/iuran-kejuaraan', [KeuanganController::class, 'iuranKejuaraan'])->name('iuran-kejuaraan');
    Route::post('/iuran-kejuaraan', [KeuanganController::class, 'storeKejuaraan'])->name('iuran-kejuaraan.store');
    Route::patch('/iuran-kejuaraan/{id}/status', [KeuanganController::class, 'updateStatusKejuaraan'])->name('iuran-kejuaraan.update-status');
    Route::get('/iuran-kejuaraan/{kejuaraan}/peserta', [KeuanganController::class, 'pesertaKejuaraan'])->name('iuran-kejuaraan.peserta');
    Route::post('/iuran-kejuaraan/{kejuaraan}/peserta', [KeuanganController::class, 'tambahPeserta'])->name('iuran-kejuaraan.peserta.store');
    Route::patch('/kejuaraan-pembayaran/{pembayaran}/bayar', [KeuanganController::class, 'bayarKejuaraan'])->name('kejuaraan-pembayaran.bayar');
    Route::delete('/kejuaraan-pembayaran/{pembayaran}', [KeuanganController::class, 'hapusPeserta'])->name('kejuaraan-pembayaran.destroy');
    Route::get('/angsuran', [KeuanganController::class, 'angsuran'])->name('angsuran');
    Route::post('/angsuran', [KeuanganController::class, 'storeAngsuran'])->name('angsuran.store');
    Route::post('/angsuran/cicilan', [KeuanganController::class, 'storeCicilan'])->name('angsuran.cicilan.store');
    Route::delete('/angsuran/{angsuran}', [KeuanganController::class, 'destroyAngsuran'])->name('angsuran.destroy');
    Route::get('/pendapatan-lain', [KeuanganController::class, 'pendapatanLain'])->name('pendapatan-lain');
    Route::post('/pendapatan-lain', [KeuanganController::class, 'storePendapatanLain'])->name('pendapatan-lain.store');
    Route::put('/pendapatan-lain/{pendapatanLain}', [KeuanganController::class, 'updatePendapatanLain'])->name('pendapatan-lain.update');
    Route::delete('/pendapatan-lain/{pendapatanLain}', [KeuanganController::class, 'destroyPendapatanLain'])->name('pendapatan-lain.destroy');

    // Pengeluaran Routes (pindah ke KeuanganController)
    Route::get('/keuangan/pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('keuangan.pengeluaran');
    Route::post('/keuangan/pengeluaran', [KeuanganController::class, 'storePengeluaran'])->name('keuangan.pengeluaran.store');
    Route::put('/keuangan/pengeluaran/{pengeluaran}', [KeuanganController::class, 'updatePengeluaran'])->name('keuangan.pengeluaran.update');
    Route::delete('/keuangan/pengeluaran/{pengeluaran}', [KeuanganController::class, 'destroyPengeluaran'])->name('keuangan.pengeluaran.destroy');

    // Pendapatan Jersey
    Route::get('/keuangan/pendapatan-jersey', [KeuanganController::class, 'pendapatanJersey'])->name('keuangan.pendapatan-jersey');

    // Rekap Keuangan (lengkap)
    Route::get('/rekap-keuangan', [LaporanController::class, 'rekapKeuangan'])->name('rekap-keuangan');
    Route::get('/calon-siswa', [SiswaController::class, 'calonSiswa'])->name('calon-siswa');
    Route::post('/siswa/{siswa}/aktivasi', [SiswaController::class, 'aktivasi'])->name('siswa.aktivasi');
    Route::get('/siswa-aktif', [SiswaController::class, 'siswaAktif'])->name('siswa-aktif');
    Route::get('/siswa-cuti', [SiswaController::class, 'siswaCuti'])->name('siswa-cuti');
    Route::get('/siswa-nonaktif', [SiswaController::class, 'siswaNonaktif'])->name('siswa-nonaktif');
    Route::get('/kakak-beradik', [SiswaController::class, 'kakakBeradik'])->name('kakak-beradik');
    Route::get('/siswa-ulang-tahun', [SiswaController::class, 'siswaUlangTahun'])->name('siswa-ulang-tahun');
    Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::patch('/siswa/{siswa}/status', [SiswaController::class, 'updateStatus'])->name('siswa.update-status');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    // Prestasi Routes
    Route::get('/catatan-waktu', [PrestasiController::class, 'catatanWaktu'])->name('catatan-waktu');
    Route::post('/catatan-waktu', [PrestasiController::class, 'storeCatatanWaktu'])->name('catatan-waktu.store');
    Route::put('/catatan-waktu/{catatanWaktu}', [PrestasiController::class, 'updateCatatanWaktu'])->name('catatan-waktu.update');
    Route::delete('/catatan-waktu/{catatanWaktu}', [PrestasiController::class, 'destroyCatatanWaktu'])->name('catatan-waktu.destroy');
    Route::get('/personal-best', [PrestasiController::class, 'personalBest'])->name('personal-best');
    Route::get('/catatan-waktu-latihan', [PrestasiController::class, 'catatanWaktuLatihan'])->name('catatan-waktu-latihan');
    Route::post('/catatan-waktu-latihan', [PrestasiController::class, 'storeCatatanWaktuLatihan'])->name('catatan-waktu-latihan.store');
    Route::put('/catatan-waktu-latihan/{catatanWaktuLatihan}', [PrestasiController::class, 'updateCatatanWaktuLatihan'])->name('catatan-waktu-latihan.update');
    Route::delete('/catatan-waktu-latihan/{catatanWaktuLatihan}', [PrestasiController::class, 'destroyCatatanWaktuLatihan'])->name('catatan-waktu-latihan.destroy');
    Route::get('/progress-report', [PrestasiController::class, 'progressReport'])->name('progress-report');
    Route::get('/nomor-nonstandar', [PrestasiController::class, 'nomorNonstandar'])->name('nomor-nonstandar');

    // Rapor Routes (pindah dari Laporan ke Prestasi)
    Route::get('/rapor', [LaporanController::class, 'rapor'])->name('rapor');
    Route::get('/rapor/{siswa}', [LaporanController::class, 'raporSiswa'])->name('rapor.siswa');
    Route::post('/rapor', [LaporanController::class, 'storeRapor'])->name('rapor.store');
    Route::put('/rapor/{rapor}', [LaporanController::class, 'updateRapor'])->name('rapor.update');
    Route::delete('/rapor/{rapor}', [LaporanController::class, 'destroyRapor'])->name('rapor.destroy');
    Route::get('/template-rapor', [LaporanController::class, 'templateRapor'])->name('template-rapor');
    Route::post('/template-rapor', [LaporanController::class, 'storeTemplateRapor'])->name('template-rapor.store');
    Route::delete('/template-rapor/{templateRapor}', [LaporanController::class, 'destroyTemplateRapor'])->name('template-rapor.destroy');

    // Jersey Routes
    Route::get('/jersey-map', [JerseyController::class, 'jerseyMap'])->name('jersey-map');
    Route::post('/jersey-map', [JerseyController::class, 'storeJerseyMap'])->name('jersey-map.store');
    Route::get('/size-chart', [JerseyController::class, 'sizeChart'])->name('size-chart');
    Route::post('/size-chart', [JerseyController::class, 'storeSizeChart'])->name('size-chart.store');
    Route::patch('/size-chart/{jerseySize}', [JerseyController::class, 'updateSizeChart'])->name('size-chart.update');
    Route::delete('/size-chart/{jerseySize}', [JerseyController::class, 'destroySizeChart'])->name('size-chart.destroy');
    Route::get('/pemesanan', [JerseyController::class, 'pemesanan'])->name('pemesanan');
    Route::post('/pemesanan', [JerseyController::class, 'storePemesanan'])->name('pemesanan.store');
    Route::patch('/pemesanan/{jerseyOrder}/status', [JerseyController::class, 'updateStatusPemesanan'])->name('pemesanan.update-status');
    Route::patch('/pemesanan/{jerseyOrder}/bayar', [JerseyController::class, 'bayarJersey'])->name('pemesanan.bayar');
    Route::delete('/pemesanan/{jerseyOrder}', [JerseyController::class, 'destroyPemesanan'])->name('pemesanan.destroy');
    Route::get('/master-ukuran', [JerseyController::class, 'masterUkuran'])->name('master-ukuran');

    // Laporan Routes
    Route::get('/rekap-transaksi', [LaporanController::class, 'rekapTransaksi'])->name('rekap-transaksi');
    Route::get('/rekap-pembayaran-iuran', [LaporanController::class, 'rekapPembayaranIuran'])->name('rekap-pembayaran-iuran');
    Route::get('/rekap-jumlah-siswa', [LaporanController::class, 'rekapJumlahSiswa'])->name('rekap-jumlah-siswa');

    // Pengaturan Routes
    Route::get('/kelas', [PengaturanController::class, 'kelas'])->name('kelas');
    Route::post('/kelas', [PengaturanController::class, 'storeKelas'])->name('kelas.store');
    Route::put('/kelas/{kela}', [PengaturanController::class, 'updateKelas'])->name('kelas.update');
    Route::delete('/kelas/{kela}', [PengaturanController::class, 'destroyKelas'])->name('kelas.destroy');
    Route::get('/coach', [PengaturanController::class, 'coach'])->name('coach');
    Route::post('/coach', [PengaturanController::class, 'storeCoach'])->name('coach.store');
    Route::put('/coach/{coach}', [PengaturanController::class, 'updateCoach'])->name('coach.update');
    Route::delete('/coach/{coach}', [PengaturanController::class, 'destroyCoach'])->name('coach.destroy');
    Route::get('/kolam', [PengaturanController::class, 'kolam'])->name('kolam');
    Route::post('/kolam', [PengaturanController::class, 'storeKolam'])->name('kolam.store');
    Route::put('/kolam/{kolam}', [PengaturanController::class, 'updateKolam'])->name('kolam.update');
    Route::delete('/kolam/{kolam}', [PengaturanController::class, 'destroyKolam'])->name('kolam.destroy');
    Route::get('/metode-pembayaran', [PengaturanController::class, 'metodePembayaran'])->name('metode-pembayaran');
    Route::get('/umum', [PengaturanController::class, 'umum'])->name('umum');

    // Profil Admin
    Route::get('/profil', [PengaturanController::class, 'profil'])->name('profil');
    Route::post('/profil', [PengaturanController::class, 'updateProfil'])->name('profil.update');
    Route::post('/profil/password', [PengaturanController::class, 'updatePassword'])->name('profil.password');
});
