<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Coach;
use App\Models\Kolam;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    // ── KELAS ─────────────────────────────────────────────────────
    public function kelas()
    {
        $kelasList = Kelas::with('coach')->orderBy('nama_kelas')->get();

        $siswaPerKelas = Siswa::where('status', 'aktif')
            ->selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        $coaches = Coach::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.pengaturan.kelas', compact('kelasList', 'siswaPerKelas', 'coaches'));
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas,nama_kelas',
            'level'      => 'required|in:pemula,menengah,lanjut,prestasi',
            'kapasitas'  => 'required|integer|min:1',
            'harga'      => 'required|numeric|min:0',
            'coach_id'   => 'nullable|exists:coaches,id',
        ]);

        $data = $request->except('jadwal_hari');
        // Build jadwal array
        $jadwal = [];
        if ($request->jadwal_hari) {
            foreach ($request->jadwal_hari as $i => $hari) {
                if ($hari) {
                    $jadwal[] = [
                        'hari'       => $hari,
                        'jam_mulai'  => $request->jadwal_jam_mulai[$i] ?? '07:00',
                        'jam_selesai'=> $request->jadwal_jam_selesai[$i] ?? '09:00',
                    ];
                }
            }
        }
        $data['jadwal'] = !empty($jadwal) ? $jadwal : null;

        Kelas::create($data);
        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function updateKelas(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas,nama_kelas,' . $kela->id,
            'level'      => 'required|in:pemula,menengah,lanjut,prestasi',
            'kapasitas'  => 'required|integer|min:1',
            'harga'      => 'required|numeric|min:0',
            'coach_id'   => 'nullable|exists:coaches,id',
        ]);

        $data = $request->except('jadwal_hari');
        $jadwal = [];
        if ($request->jadwal_hari) {
            foreach ($request->jadwal_hari as $i => $hari) {
                if ($hari) {
                    $jadwal[] = [
                        'hari'       => $hari,
                        'jam_mulai'  => $request->jadwal_jam_mulai[$i] ?? '07:00',
                        'jam_selesai'=> $request->jadwal_jam_selesai[$i] ?? '09:00',
                    ];
                }
            }
        }
        $data['jadwal'] = !empty($jadwal) ? $jadwal : null;

        $kela->update($data);
        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroyKelas(Kelas $kela)
    {
        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    // ── COACH ─────────────────────────────────────────────────────
    public function coach()
    {
        $coaches = Coach::orderBy('nama')->get();
        return view('admin.pengaturan.coach', compact('coaches'));
    }

    public function storeCoach(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'status' => 'required|in:aktif,cuti,nonaktif',
            'custom_password' => 'nullable|string|min:6',
            'password_option' => 'required|in:keep,manual',
        ]);

        try {
            \DB::beginTransaction();

            // Generate atau gunakan password custom
            $password = ($request->password_option === 'manual' && $request->custom_password) 
                ? $request->custom_password 
                : \Str::random(8);
            
            // Buat akun User terlebih dahulu
            $user = \App\Models\User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => \Hash::make($password),
                'role' => 'coach'
            ]);

            // Buat data Coach dengan password info
            $coachData = $request->except(['custom_password', 'password_option']);
            $coachData['current_password'] = $password; // Store plain password for display
            $coachData['password_updated_at'] = now();
            
            $coach = Coach::create($coachData);
            
            // Link user dengan coach
            $user->update(['coach_id' => $coach->id]);

            \DB::commit();

            // Pesan sukses dengan kredensial
            $passwordType = ($request->password_option === 'manual') ? 'manual' : 'otomatis';
            $message = "Coach berhasil ditambahkan! Kredensial login: Email: {$request->email} | Password ({$passwordType}): {$password}";
            
            return back()->with('success', $message);

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Gagal membuat coach: ' . $e->getMessage()]);
        }
    }

    public function updateCoach(Request $request, Coach $coach)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($coach->user->id ?? 'NULL'),
            'status' => 'required|in:aktif,cuti,nonaktif',
            'custom_password' => 'nullable|string|min:6',
            'password_option' => 'required|in:keep,manual',
        ]);

        try {
            \DB::beginTransaction();

            // Update data coach
            $coachData = $request->except(['custom_password', 'password_option']);
            $coach->update($coachData);

            // Update atau buat akun user jika belum ada
            if ($coach->user) {
                // Update user yang sudah ada
                $coach->user->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                ]);

                // Update password jika dipilih manual dan ada custom password
                if ($request->password_option === 'manual' && $request->custom_password) {
                    $coach->user->update(['password' => \Hash::make($request->custom_password)]);
                    $coach->update([
                        'current_password' => $request->custom_password,
                        'password_updated_at' => now()
                    ]);
                    
                    $message = "Data coach berhasil diperbarui dan password diubah menjadi: {$request->custom_password}";
                    \DB::commit();
                    return back()->with('success', $message);
                } elseif ($request->password_option === 'keep' && !$coach->current_password) {
                    // Jika pilih keep tapi belum ada password, generate baru
                    $password = \Str::random(8);
                    $coach->user->update(['password' => \Hash::make($password)]);
                    $coach->update([
                        'current_password' => $password,
                        'password_updated_at' => now()
                    ]);
                    
                    $message = "Data coach berhasil diperbarui dan password baru dibuat: {$password}";
                    \DB::commit();
                    return back()->with('success', $message);
                }
            } else {
                // Buat user baru jika belum ada
                $password = ($request->password_option === 'manual' && $request->custom_password) 
                    ? $request->custom_password 
                    : \Str::random(8);
                    
                $user = \App\Models\User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => \Hash::make($password),
                    'role' => 'coach',
                    'coach_id' => $coach->id
                ]);

                // Update coach dengan password info
                $coach->update([
                    'current_password' => $password,
                    'password_updated_at' => now()
                ]);

                $message = "Data coach berhasil diperbarui dan akun login dibuat! Password: {$password}";
                \DB::commit();
                return back()->with('success', $message);
            }

            \DB::commit();
            return back()->with('success', 'Data coach berhasil diperbarui.');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Gagal update coach: ' . $e->getMessage()]);
        }
    }

    public function destroyCoach(Coach $coach)
    {
        try {
            \DB::beginTransaction();
            
            // Hapus user terkait jika ada
            if ($coach->user) {
                $coach->user->delete();
            }
            
            // Hapus coach
            $coach->delete();
            
            \DB::commit();
            return back()->with('success', 'Coach berhasil dihapus.');
            
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Gagal hapus coach: ' . $e->getMessage()]);
        }
    }

    /**
     * Reset password coach
     */
    public function resetCoachPassword(Coach $coach)
    {
        if (!$coach->user) {
            return back()->withErrors(['error' => 'Coach belum memiliki akun login.']);
        }

        try {
            $newPassword = \Str::random(8);
            $coach->user->update(['password' => \Hash::make($newPassword)]);
            
            // Update coach password info
            $coach->update([
                'current_password' => $newPassword,
                'password_updated_at' => now()
            ]);
            
            $message = "Password coach {$coach->nama} berhasil direset! Password baru: {$newPassword}";
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal reset password: ' . $e->getMessage()]);
        }
    }

    // ── LAINNYA ───────────────────────────────────────────────────
    public function metodePembayaran() { return view('admin.pengaturan.metode-pembayaran'); }
    public function formPendaftaran()  { return view('admin.pengaturan.form-pendaftaran'); }
    public function umum()             { return view('admin.pengaturan.umum'); }

    // ── PROFIL ADMIN ──────────────────────────────────────────────
    public function profil()
    {
        return view('admin.pengaturan.profil', ['user' => auth()->user()]);
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        auth()->user()->update([
            'password' => \Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Password berhasil diubah.');
    }

    // ── KOLAM ─────────────────────────────────────────────────────
    public function kolam()
    {
        $kolams = Kolam::orderBy('nama')->get();
        return view('admin.pengaturan.kolam', compact('kolams'));
    }

    public function storeKolam(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'ukuran' => 'required|in:25m,50m',
        ]);
        Kolam::create($request->all());
        return back()->with('success', 'Kolam berhasil ditambahkan.');
    }

    public function updateKolam(Request $request, Kolam $kolam)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'ukuran' => 'required|in:25m,50m',
        ]);
        $kolam->update($request->all());
        return back()->with('success', 'Kolam berhasil diperbarui.');
    }

    public function destroyKolam(Kolam $kolam)
    {
        $kolam->delete();
        return back()->with('success', 'Kolam berhasil dihapus.');
    }
}
