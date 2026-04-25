<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showForm()
    {
        // Ambil semua kelas yang aktif, jika tidak ada kolom aktif maka ambil semua
        $kelasList = Kelas::where(function($query) {
            $query->where('aktif', true)
                  ->orWhereNull('aktif');
        })->get();
        
        // Jika masih kosong, ambil semua kelas
        if ($kelasList->isEmpty()) {
            $kelasList = Kelas::all();
        }
        
        return view('auth.register', compact('kelasList'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'alamat' => 'required|string',
            'nama_ortu' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:siswas,email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Hitung umur
        $umur = Carbon::parse($request->tanggal_lahir)->age;

        // Ambil nama kelas dari kelas_id
        $kelas = Kelas::find($request->kelas_id);
        if (!$kelas) {
            return redirect()->back()->withErrors(['kelas_id' => 'Kelas tidak ditemukan'])->withInput();
        }

        // Buat data siswa
        $siswa = Siswa::create([
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $kelas->nama_kelas, // Gunakan nama kelas, bukan kelas_id
            'alamat' => $request->alamat,
            'nama_ortu' => $request->nama_ortu,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'paket' => $kelas->level ?? 'reguler', // Set paket berdasarkan level kelas
            'status' => 'aktif', // Langsung aktif untuk pendaftaran online
            'catatan' => "Pendaftaran online pada " . now()->format('d/m/Y H:i') . ". Umur: {$umur} tahun."
        ]);

        // Langsung buat akun user untuk siswa
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'siswa_id' => $siswa->id,
        ]);

        return redirect()->route('daftar.sukses')->with([
            'siswa' => $siswa,
            'user_email' => $user->email,
            'user_name' => $user->name,
            'success' => 'Pendaftaran berhasil! Akun Anda sudah aktif dan dapat digunakan untuk login sekarang.'
        ]);
    }

    public function sukses()
    {
        if (!session('success')) {
            return redirect()->route('daftar');
        }
        
        return view('auth.register-success');
    }
}