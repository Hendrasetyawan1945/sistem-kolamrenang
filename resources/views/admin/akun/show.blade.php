@extends('layouts.admin')

@section('title', 'Detail Akun')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Akun - {{ $user->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.akun.edit-password', $user) }}" class="btn btn-warning">
            <i class="fas fa-key"></i> Edit Password
        </a>
        <a href="{{ route('admin.akun.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Akun
        </a>
        <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%">Nama</td>
                        <td>: {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>: {{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>: 
                            <span class="badge bg-{{ $user->role == 'siswa' ? 'primary' : 'success' }}">
                                {{ $user->role == 'siswa' ? 'Siswa' : ($user->role == 'coach' ? 'Guru' : ucfirst($user->role)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Dibuat</td>
                        <td>: {{ $user->created_at->format('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Terakhir Update</td>
                        <td>: {{ $user->updated_at->format('d F Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        @if($user->role === 'siswa' && $user->siswa)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Siswa</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%">Nama Lengkap</td>
                        <td>: {{ $user->siswa->nama }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>: {{ $user->siswa->tanggal_lahir->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Umur</td>
                        <td>: {{ $user->siswa->tanggal_lahir->age }} tahun</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>: {{ $user->siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>: {{ $user->siswa->kelas->nama ?? 'Belum ada kelas' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: 
                            <span class="badge bg-{{ $user->siswa->status == 'aktif' ? 'success' : ($user->siswa->status == 'cuti' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user->siswa->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @elseif($user->role === 'coach' && $user->coach)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Guru</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%">Nama Lengkap</td>
                        <td>: {{ $user->coach->nama }}</td>
                    </tr>
                    <tr>
                        <td>Spesialisasi</td>
                        <td>: {{ $user->coach->spesialisasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pengalaman</td>
                        <td>: {{ $user->coach->pengalaman ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $user->coach->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: 
                            <span class="badge bg-{{ $user->coach->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->coach->status ?? 'aktif') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.akun.edit-password', $user) }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-key mb-2"></i><br>
                            Edit Password
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.akun.reset-password', $user) }}" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-outline-info w-100" 
                                    onclick="return confirm('Reset password ke default (123456)?')">
                                <i class="fas fa-undo mb-2"></i><br>
                                Reset Password
                            </button>
                        </form>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.akun.edit', $user) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-edit mb-2"></i><br>
                            Edit Akun
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.akun.destroy', $user) }}" class="w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Yakin hapus akun ini? Tindakan tidak dapat dibatalkan!')">
                                <i class="fas fa-trash mb-2"></i><br>
                                Hapus Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@endsection