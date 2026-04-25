@extends('layouts.siswa')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Profil Saya</h2>
    <div class="text-muted">{{ date('d F Y') }}</div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('siswa.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Email digunakan untuk login</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('siswa.profile.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" 
                               class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Siswa</h5>
                <small class="text-muted">Data ini tidak dapat diubah, hubungi admin jika ada kesalahan</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%">Nama Lengkap</td>
                                <td>: {{ $siswa->nama }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Lahir</td>
                                <td>: {{ $siswa->tanggal_lahir->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td>Umur</td>
                                <td>: {{ $siswa->tanggal_lahir->age }} tahun</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: 
                                    <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : ($siswa->status == 'cuti' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($siswa->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%">Kelas</td>
                                <td>: {{ $siswa->kelas->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Guru</td>
                                <td>: {{ $siswa->kelas->coach->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ $siswa->alamat }}</td>
                            </tr>
                            <tr>
                                <td>Nama Orang Tua</td>
                                <td>: {{ $siswa->nama_ortu }}</td>
                            </tr>
                            <tr>
                                <td>Telepon</td>
                                <td>: {{ $siswa->telepon }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection