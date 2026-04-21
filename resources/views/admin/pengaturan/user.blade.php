@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-users-cog"></i>
    </div>
    <h1 class="club-title">Manajemen User</h1>
</div>

<!-- Tombol Aksi -->
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah User Baru
    </button>
    <button class="btn" style="background: #4caf50; color: white;">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</div>

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Daftar User Sistem</h4>
            <div>
                <input type="text" placeholder="Cari user..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Email</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Role</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Last Login</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([
                    ['nama' => 'Admin Utama', 'email' => 'admin@youthswimming.club', 'role' => 'Super Admin', 'last_login' => '2026-04-20 16:00:00', 'status' => 'aktif'],
                    ['nama' => 'Coach Ahmad', 'email' => 'ahmad@youthswimming.club', 'role' => 'Coach', 'last_login' => '2026-04-19 14:30:00', 'status' => 'aktif'],
                    ['nama' => 'Staff Keuangan', 'email' => 'keuangan@youthswimming.club', 'role' => 'Staff', 'last_login' => '2026-04-18 09:15:00', 'status' => 'aktif'],
                    ['nama' => 'Resepsionis', 'email' => 'resepsionis@youthswimming.club', 'role' => 'Staff', 'last_login' => null, 'status' => 'nonaktif']
                ] as $index => $user)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: 600;">{{ $user['nama'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $user['email'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ 
                                $user['role'] == 'Super Admin' ? 'status-danger' : 
                                ($user['role'] == 'Coach' ? 'status-active' : 'status-secondary') 
                            }}">
                                {{ $user['role'] }}
                            </span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            {{ $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Belum pernah login' }}
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $user['status'] == 'aktif' ? 'status-active' : 'status-pending' }}">
                                {{ ucfirst($user['status']) }}
                            </span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-key"></i>
                                </button>
                                @if($user['status'] == 'aktif' && $user['role'] != 'Super Admin')
                                    <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #ff9800; color: white;">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @elseif($user['status'] == 'nonaktif')
                                    <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #4caf50; color: white;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #666;">
                            <div style="text-align: center;">
                                <i class="fas fa-users-cog" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                <h4 style="color: #666; margin-bottom: 10px;">Belum Ada User</h4>
                                <p>Belum ada user yang terdaftar dalam sistem.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<style>
    .status-secondary {
        background: #6c757d;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-danger {
        background: #f44336;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
</style>
@endsection