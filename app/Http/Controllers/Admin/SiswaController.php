<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function calonSiswa()
    {
        $siswas = Siswa::where('status', 'calon')->orderBy('nama')->get();

        // Ambil harga kelas untuk setiap calon siswa
        $kelasHarga = Kelas::where('aktif', true)->pluck('harga', 'nama_kelas');

        return view('admin.siswa.calon-siswa', compact('siswas', 'kelasHarga'));
    }

    /**
     * Aktivasi calon siswa → siswa aktif + catat pembayaran pendaftaran
     */
    public function aktivasi(Request $request, Siswa $siswa)
    {
        $request->validate([
            'bayar_pendaftaran' => 'required|in:ya,tidak',
            'jumlah_pendaftaran'=> 'required_if:bayar_pendaftaran,ya|nullable|numeric|min:0',
            'tanggal_bayar'     => 'required_if:bayar_pendaftaran,ya|nullable|date',
            'metode_pembayaran' => 'nullable|string',
        ]);

        // Aktifkan siswa
        $siswa->update(['status' => 'aktif']);

        // Catat pembayaran pendaftaran jika ada
        if ($request->bayar_pendaftaran === 'ya' && $request->jumlah_pendaftaran > 0) {
            Pembayaran::create([
                'siswa_id'          => $siswa->id,
                'jenis_pembayaran'  => 'biaya_pendaftaran',
                'tahun'             => date('Y', strtotime($request->tanggal_bayar)),
                'bulan'             => date('n', strtotime($request->tanggal_bayar)),
                'jumlah'            => $request->jumlah_pendaftaran,
                'tanggal_bayar'     => $request->tanggal_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan'        => 'Biaya pendaftaran siswa baru',
            ]);
        }

        return back()->with('success', "{$siswa->nama} berhasil diaktifkan" . ($request->bayar_pendaftaran === 'ya' ? ' dan pembayaran pendaftaran dicatat.' : '.'));
    }

    public function siswaAktif()
    {
        $siswas = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.siswa.siswa-aktif', compact('siswas'));
    }

    public function siswaCuti()
    {
        $siswas = Siswa::where('status', 'cuti')->orderBy('nama')->get();
        return view('admin.siswa.siswa-cuti', compact('siswas'));
    }

    public function siswaNonaktif()
    {
        $siswas = Siswa::where('status', 'nonaktif')->orderBy('nama')->get();
        return view('admin.siswa.siswa-nonaktif', compact('siswas'));
    }

    public function kakakBeradik()
    {
        return view('admin.siswa.kakak-beradik');
    }

    public function siswaUlangTahun()
    {
        return view('admin.siswa.siswa-ulang-tahun');
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
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
            'status'        => 'required|in:calon,aktif,cuti,nonaktif',
        ]);

        $siswa->update($request->only([
            'nama', 'tanggal_lahir', 'jenis_kelamin', 'kelas',
            'alamat', 'nama_ortu', 'telepon', 'email', 'paket', 'catatan', 'status',
        ]));

        // Redirect ke halaman sesuai status terbaru
        $routeMap = [
            'calon'    => 'admin.calon-siswa',
            'aktif'    => 'admin.siswa-aktif',
            'cuti'     => 'admin.siswa-cuti',
            'nonaktif' => 'admin.siswa-nonaktif',
        ];

        return redirect()->route($routeMap[$siswa->status])->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Siswa $siswa)
    {
        $request->validate(['status' => 'required|in:calon,aktif,cuti,nonaktif']);
        $siswa->update(['status' => $request->status]);
        return back()->with('success', 'Status siswa berhasil diubah.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return back()->with('success', 'Data siswa berhasil dihapus.');
    }
}
