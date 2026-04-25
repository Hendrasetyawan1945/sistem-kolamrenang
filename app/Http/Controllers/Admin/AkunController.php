<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Coach;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::with(['siswa', 'coach'])
                    ->where('role', '!=', 'admin')
                    ->paginate(20);

        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        // Siswa yang belum punya akun
        $siswaList = Siswa::whereDoesntHave('user')->get();
        
        // Coach yang belum punya akun
        $coachList = Coach::whereDoesntHave('user')->get();

        return view('admin.akun.create', compact('siswaList', 'coachList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:siswa,coach',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'siswa_id' => 'required_if:role,siswa|exists:siswas,id|nullable',
            'coach_id' => 'required_if:role,coach|exists:coaches,id|nullable'
        ]);

        // Pastikan siswa/coach belum punya akun
        if ($request->role === 'siswa') {
            $existingUser = User::where('siswa_id', $request->siswa_id)->first();
            if ($existingUser) {
                return back()->withErrors(['siswa_id' => 'Siswa ini sudah memiliki akun']);
            }
            $siswa = Siswa::find($request->siswa_id);
            $name = $siswa->nama;
        } else {
            $existingUser = User::where('coach_id', $request->coach_id)->first();
            if ($existingUser) {
                return back()->withErrors(['coach_id' => 'Guru ini sudah memiliki akun']);
            }
            $coach = Coach::find($request->coach_id);
            $name = $coach->nama;
        }

        User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'current_password' => $request->password, // Simpan untuk display admin
            'role' => $request->role,
            'siswa_id' => $request->role === 'siswa' ? $request->siswa_id : null,
            'coach_id' => $request->role === 'coach' ? $request->coach_id : null,
        ]);

        return redirect()->route('admin.akun.index')
                        ->with('success', 'Akun berhasil dibuat');
    }

    public function show(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat melihat detail admin');
        }

        return view('admin.akun.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat mengedit admin');
        }

        // Siswa yang belum punya akun (kecuali yang sedang diedit)
        $siswaList = Siswa::whereDoesntHave('user')
                          ->orWhere('id', $user->siswa_id)
                          ->get();
        
        // Coach yang belum punya akun (kecuali yang sedang diedit)
        $coachList = Coach::whereDoesntHave('user')
                          ->orWhere('id', $user->coach_id)
                          ->get();

        return view('admin.akun.edit', compact('user', 'siswaList', 'coachList'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat mengedit admin');
        }

        $request->validate([
            'role' => 'required|in:siswa,coach',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'siswa_id' => 'required_if:role,siswa|exists:siswas,id|nullable',
            'coach_id' => 'required_if:role,coach|exists:coaches,id|nullable'
        ]);

        // Pastikan siswa/coach belum punya akun (kecuali user ini sendiri)
        if ($request->role === 'siswa' && $request->siswa_id != $user->siswa_id) {
            $existingUser = User::where('siswa_id', $request->siswa_id)->first();
            if ($existingUser) {
                return back()->withErrors(['siswa_id' => 'Siswa ini sudah memiliki akun']);
            }
            $siswa = Siswa::find($request->siswa_id);
            $name = $siswa->nama;
        } elseif ($request->role === 'coach' && $request->coach_id != $user->coach_id) {
            $existingUser = User::where('coach_id', $request->coach_id)->first();
            if ($existingUser) {
                return back()->withErrors(['coach_id' => 'Guru ini sudah memiliki akun']);
            }
            $coach = Coach::find($request->coach_id);
            $name = $coach->nama;
        } else {
            $name = $user->name;
        }

        $updateData = [
            'name' => $name,
            'email' => $request->email,
            'role' => $request->role,
            'siswa_id' => $request->role === 'siswa' ? $request->siswa_id : null,
            'coach_id' => $request->role === 'coach' ? $request->coach_id : null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
            $updateData['current_password'] = $request->password; // Simpan untuk display admin
        }

        $user->update($updateData);

        return redirect()->route('admin.akun.index')
                        ->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat menghapus admin');
        }

        $user->delete();

        return redirect()->route('admin.akun.index')
                        ->with('success', 'Akun berhasil dihapus');
    }

    public function resetPassword(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat reset password admin');
        }

        // Reset password ke default (bisa disesuaikan)
        $defaultPassword = '123456';
        $user->update([
            'password' => Hash::make($defaultPassword),
            'current_password' => $defaultPassword // Simpan untuk display admin
        ]);

        return redirect()->route('admin.akun.show', $user)
                        ->with('success', "Password berhasil direset ke: {$defaultPassword}");
    }

    // ✨ FITUR BARU: Bulk Generate Akun dari Data Siswa
    public function bulkGenerate()
    {
        // Siswa yang punya email tapi belum punya akun
        $siswaList = Siswa::whereDoesntHave('user')
                          ->whereNotNull('email')
                          ->where('email', '!=', '')
                          ->where('status', 'aktif')
                          ->get();
        
        // Filter email valid menggunakan helper
        $siswaList = EmailHelper::filterValidEmails($siswaList);

        return view('admin.akun.bulk-generate', compact('siswaList'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
            'password_type' => 'required|in:default,tanggal_lahir,custom',
            'custom_password' => 'required_if:password_type,custom|min:6'
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

            // Skip jika email tidak valid
            if (!EmailHelper::isValidEmail($siswa->email)) {
                $errors[] = "Email siswa {$siswa->nama} tidak valid";
                continue;
            }

            // Skip jika email sudah digunakan
            if (User::where('email', $siswa->email)->exists()) {
                $errors[] = "Email {$siswa->email} sudah digunakan";
                continue;
            }

            // Generate password
            $password = $this->generatePassword($siswa, $request->password_type, $request->custom_password);

            try {
                User::create([
                    'name' => $siswa->nama,
                    'email' => $siswa->email,
                    'password' => Hash::make($password),
                    'current_password' => $password, // Simpan untuk display admin
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
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->route('admin.akun.index')->with('success', $message);
    }

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

    // ✨ FITUR BARU: Generate akun individual dengan email siswa
    public function generateFromSiswa(Siswa $siswa)
    {
        // Validasi
        if ($siswa->user) {
            return back()->with('error', 'Siswa ini sudah memiliki akun');
        }

        if (!EmailHelper::isValidEmail($siswa->email)) {
            return back()->with('error', 'Email siswa tidak valid');
        }

        if (User::where('email', $siswa->email)->exists()) {
            return back()->with('error', 'Email sudah digunakan oleh user lain');
        }

        // Generate password default (tanggal lahir)
        $password = $siswa->tanggal_lahir->format('dmY');

        User::create([
            'name' => $siswa->nama,
            'email' => $siswa->email,
            'password' => Hash::make($password),
            'current_password' => $password, // Simpan untuk display admin
            'role' => 'siswa',
            'siswa_id' => $siswa->id,
        ]);

        return back()->with('success', "Akun berhasil dibuat untuk {$siswa->nama}. Password: {$password}");
    }

    // ✨ FITUR BARU: Admin edit password siswa
    public function editPassword(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat mengedit password admin');
        }

        return view('admin.akun.edit-password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak dapat mengedit password admin');
        }

        $request->validate([
            'password_type' => 'required|in:default,tanggal_lahir,custom',
            'custom_password' => 'required_if:password_type,custom|min:6'
        ]);

        // Generate password berdasarkan type
        if ($request->password_type === 'default') {
            $newPassword = '123456';
        } elseif ($request->password_type === 'tanggal_lahir') {
            if ($user->role === 'siswa' && $user->siswa && $user->siswa->tanggal_lahir) {
                $newPassword = $user->siswa->tanggal_lahir->format('dmY');
            } elseif ($user->role === 'coach' && $user->coach) {
                // Untuk coach, gunakan default jika tidak ada tanggal lahir
                $newPassword = '123456';
            } else {
                return back()->with('error', 'Data tanggal lahir tidak ditemukan');
            }
        } else {
            $newPassword = $request->custom_password;
        }

        // Update password
        $user->update([
            'password' => Hash::make($newPassword),
            'current_password' => $newPassword  // Simpan untuk display admin
        ]);

        return redirect()->route('admin.akun.index')
                        ->with('success', "Password untuk {$user->name} berhasil diubah menjadi: {$newPassword}");
    }
}