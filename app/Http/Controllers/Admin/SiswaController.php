<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Kelas;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function calonSiswa()
    {
        $siswas = Siswa::with('user')->where('status', 'calon')->orderBy('nama')->get();

        // Ambil harga kelas untuk setiap calon siswa
        $kelasHarga = Kelas::where('aktif', true)->pluck('harga', 'nama_kelas');

        return view('admin.siswa.calon-siswa', compact('siswas', 'kelasHarga'));
    }

    /**
     * Aktivasi calon siswa → siswa aktif + catat pembayaran pendaftaran + buat akun otomatis
     */
    public function aktivasi(Request $request, Siswa $siswa)
    {
        try {
            $request->validate([
                'bayar_pendaftaran' => 'required|in:ya,tidak',
                'jumlah_pendaftaran'=> 'required_if:bayar_pendaftaran,ya|nullable|numeric|min:0',
                'tanggal_bayar'     => 'required_if:bayar_pendaftaran,ya|nullable|date',
                'metode_pembayaran' => 'nullable|string',
                'buat_akun'        => 'nullable|in:ya,tidak',
                'email_akun'       => [
                    'required_if:buat_akun,ya',
                    'nullable',
                    'email',
                    \Illuminate\Validation\Rule::unique('users', 'email')->ignore($siswa->user ? $siswa->user->id : null)
                ],
                'password_type'    => 'required_if:buat_akun,ya|nullable|in:default,tanggal_lahir,custom',
                'custom_password'  => 'required_if:password_type,custom|nullable|min:6',
            ]);

            // Log sebelum update
            \Log::info("Aktivasi siswa {$siswa->nama} - Status sebelum: {$siswa->status}");

            // Aktifkan siswa
            $updated = $siswa->update(['status' => 'aktif']);
            
            // Log setelah update
            \Log::info("Aktivasi siswa {$siswa->nama} - Update result: " . ($updated ? 'success' : 'failed'));
            \Log::info("Aktivasi siswa {$siswa->nama} - Status setelah: {$siswa->fresh()->status}");

            $messages = ["{$siswa->nama} berhasil diaktifkan"];

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
                $messages[] = 'pembayaran pendaftaran dicatat';
            }

            // Buat akun otomatis jika diminta
            if ($request->buat_akun === 'ya' && $request->email_akun) {
                // Cek apakah siswa sudah punya akun
                if (!$siswa->user) {
                    // Generate password berdasarkan type
                    $password = $this->generatePassword($siswa, $request->password_type, $request->custom_password);
                    
                    try {
                        \App\Models\User::create([
                            'name' => $siswa->nama,
                            'email' => $request->email_akun,
                            'password' => \Illuminate\Support\Facades\Hash::make($password),
                            'role' => 'siswa',
                            'siswa_id' => $siswa->id,
                        ]);
                        
                        // Update email siswa jika berbeda
                        if ($siswa->email !== $request->email_akun) {
                            $siswa->update(['email' => $request->email_akun]);
                        }
                        
                        $messages[] = "akun login berhasil dibuat (Email: {$request->email_akun}, Password: {$password})";
                    } catch (\Exception $e) {
                        $messages[] = "gagal membuat akun: " . $e->getMessage();
                        \Log::error("Gagal membuat akun untuk {$siswa->nama}: " . $e->getMessage());
                    }
                } else {
                    $messages[] = "siswa sudah memiliki akun";
                }
            }

            return back()->with('success', implode(', ', $messages) . '.');
            
        } catch (\Exception $e) {
            \Log::error("Error aktivasi siswa {$siswa->nama}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat aktivasi: ' . $e->getMessage());
        }
    }

    /**
     * Generate password berdasarkan type
     */
    private function generatePassword($siswa, $type, $customPassword = null)
    {
        switch ($type) {
            case 'tanggal_lahir':
                return $siswa->tanggal_lahir->format('dmY'); // contoh: 15081995
            case 'custom':
                return $customPassword;
            default:
                return '123456'; // default password
        }
    }

    public function siswaAktif()
    {
        // Load siswa dengan relasi user, kelas bisa gagal jika data tidak cocok
        $siswas = Siswa::with(['user'])
                       ->where('status', 'aktif')
                       ->orderBy('nama')
                       ->get();
        
        // Coba load relasi kelas secara manual untuk yang bisa
        $siswas->load('kelas');
        
        // Hitung statistik
        $totalSiswa = $siswas->count();
        $sudahPunyaAkun = $siswas->whereNotNull('user')->count();
        $belumPunyaAkun = $totalSiswa - $sudahPunyaAkun;
        
        // Filter email valid menggunakan helper
        $emailValid = EmailHelper::filterValidEmails($siswas)->count();
        
        return view('admin.siswa.siswa-aktif', compact('siswas', 'totalSiswa', 'sudahPunyaAkun', 'belumPunyaAkun', 'emailValid'));
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

    /**
     * Buat akun otomatis untuk siswa aktif yang belum punya akun
     */
    public function buatAkunOtomatis(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
            'password_type' => 'required|in:default,tanggal_lahir',
        ]);

        $created = 0;
        $errors = [];

        foreach ($request->siswa_ids as $siswaId) {
            $siswa = Siswa::find($siswaId);
            
            // Skip jika sudah punya akun
            if ($siswa->user) {
                $errors[] = "Siswa {$siswa->nama} sudah memiliki akun";
                continue;
            }

            // Skip jika tidak ada email atau email tidak valid
            if (!$siswa->email || !filter_var($siswa->email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email siswa {$siswa->nama} tidak valid atau kosong";
                continue;
            }

            // Skip jika email sudah digunakan
            if (\App\Models\User::where('email', $siswa->email)->exists()) {
                $errors[] = "Email {$siswa->email} sudah digunakan";
                continue;
            }

            // Generate password
            $password = $this->generatePassword($siswa, $request->password_type);

            try {
                \App\Models\User::create([
                    'name' => $siswa->nama,
                    'email' => $siswa->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'role' => 'siswa',
                    'siswa_id' => $siswa->id,
                ]);
                $created++;
            } catch (\Exception $e) {
                $errors[] = "Gagal membuat akun untuk {$siswa->nama}: " . $e->getMessage();
            }
        }

        $message = "Berhasil membuat {$created} akun siswa.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
            if (count($errors) > 3) {
                $message .= " dan " . (count($errors) - 3) . " error lainnya.";
            }
        }

        return back()->with('success', $message);
    }

    /**
     * Generate akun untuk siswa individual
     */
    public function generateAkun(Siswa $siswa)
    {
        // Cek apakah siswa sudah punya akun
        if ($siswa->user) {
            return back()->with('error', 'Siswa sudah memiliki akun.');
        }

        // Cek email valid
        if (!$siswa->email || !filter_var($siswa->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Email siswa tidak valid.');
        }

        // Cek email sudah digunakan
        if (\App\Models\User::where('email', $siswa->email)->exists()) {
            return back()->with('error', 'Email sudah digunakan oleh user lain.');
        }

        try {
            $password = '123456'; // Default password
            
            \App\Models\User::create([
                'name' => $siswa->nama,
                'email' => $siswa->email,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'role' => 'siswa',
                'siswa_id' => $siswa->id,
            ]);

            return back()->with('success', "Akun berhasil dibuat untuk {$siswa->nama}. Email: {$siswa->email}, Password: {$password}");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }
}
