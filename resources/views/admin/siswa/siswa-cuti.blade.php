@extends('layouts.admin')

@section('title', 'Siswa Cuti')

@section('content')
<style>
    .page-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header {
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Stats bar */
    .stats-bar {
        background: white;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border-left: 4px solid #ffc107;
    }
    
    .stats-info {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    
    .stat-item {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .stat-number {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.2rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-compact {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-primary-compact {
        background: #007bff;
        color: white;
        border: 1px solid #007bff;
    }
    
    .btn-primary-compact:hover {
        background: #0056b3;
        border-color: #0056b3;
        color: white;
        text-decoration: none;
    }
    
    .btn-warning-compact {
        background: #ffc107;
        color: #212529;
        border: 1px solid #ffc107;
    }
    
    .btn-warning-compact:hover {
        background: #e0a800;
        border-color: #d39e00;
        color: #212529;
        text-decoration: none;
    }
    
    /* Simple Clean Table */
    .table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .table-clean {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        font-size: 0.8rem;
        table-layout: fixed;
    }
    
    .table-clean thead {
        background: #f8f9fa;
    }
    
    .table-clean thead th {
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #495057;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 1px solid #dee2e6;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .table-clean tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }
    
    .table-clean tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .table-clean tbody td {
        padding: 12px;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Student Info Compact */
    .student-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .student-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.7rem;
        flex-shrink: 0;
    }
    
    .student-details .name {
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        font-size: 0.8rem;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .student-details .meta {
        color: #6c757d;
        font-size: 0.65rem;
        margin: 1px 0 0 0;
    }
    
    /* Email styling */
    .email-text {
        font-family: 'Segoe UI', system-ui, sans-serif;
        color: #495057;
        font-size: 0.75rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 180px;
        display: inline-block;
    }
    
    /* Simple badges */
    .badge-simple {
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 0.65rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-kelas {
        background: #fff3cd;
        color: #856404;
    }
    
    /* Action buttons compact */
    .action-group {
        display: flex;
        gap: 2px;
        align-items: center;
    }
    
    .btn-icon {
        width: 24px;
        height: 24px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.7rem;
        cursor: pointer;
    }
    
    .btn-icon:hover {
        background: #007bff;
        color: white;
        border-color: #007bff;
        text-decoration: none;
    }
    
    /* Dropdown for more actions - Horizontal */
    .dropdown-toggle::after {
        display: none;
    }
    
    .dropdown-horizontal {
        min-width: auto;
        padding: 8px;
        border-radius: 6px;
    }
    
    .dropdown-actions {
        display: flex;
        gap: 4px;
        align-items: center;
    }
    
    .btn-dropdown-action {
        width: 24px;
        height: 24px;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        transition: all 0.2s ease;
        font-size: 0.7rem;
        cursor: pointer;
    }
    
    .btn-dropdown-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-dropdown-action.btn-danger:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    
    .btn-dropdown-action.btn-delete:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-container {
            padding: 15px;
        }
        
        .table-clean {
            font-size: 0.75rem;
        }
        
        .table-clean thead th,
        .table-clean tbody td {
            padding: 10px 8px;
        }
        
        .student-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .stats-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .stats-info {
            justify-content: space-around;
        }
        
        .action-buttons {
            justify-content: center;
        }
    }
    
    /* Inline Action Buttons */
    .btn-danger-inline:hover {
        background: #dc3545 !important;
        color: white !important;
        border-color: #dc3545 !important;
    }
    
    .btn-delete-inline:hover {
        background: #dc3545 !important;
        color: white !important;
        border-color: #dc3545 !important;
    }
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-clock me-2 text-warning"></i>Siswa Cuti
        </h1>
        <p class="page-subtitle">Kelola siswa yang sedang dalam status cuti</p>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stats-info">
            <div class="stat-item">
                <i class="fas fa-pause text-warning me-2"></i>
                Total Siswa Cuti: <span class="stat-number">{{ $siswas->count() }}</span>
            </div>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.siswa-aktif') }}" class="btn-compact btn-primary-compact">
                <i class="fas fa-users me-1"></i>Siswa Aktif
            </a>
            <a href="{{ route('admin.siswa-nonaktif') }}" class="btn-compact btn-warning-compact">
                <i class="fas fa-user-times me-1"></i>Siswa Nonaktif
            </a>
        </div>
    </div>

    <!-- Simple Table -->
    <div class="table-container">
        @if($siswas->count() > 0)
            <table class="table-clean">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th width="200">Siswa</th>
                        <th width="180">Email</th>
                        <th width="60">Kelas</th>
                        <th width="80">Tanggal Cuti</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $index => $siswa)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <div class="student-details">
                                    <div class="name">{{ $siswa->nama }}</div>
                                    <div class="meta">{{ $siswa->jenis_kelamin == 'L' ? 'L' : 'P' }} • {{ $siswa->tanggal_lahir->age }}th</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($siswa->email)
                                <span class="email-text">{{ $siswa->email }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->kelas)
                                <span class="badge-simple badge-kelas">{{ $siswa->kelas }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $siswa->updated_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <div class="action-group">
                                <!-- Edit -->
                                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Activate -->
                                <form method="POST" action="{{ route('admin.siswa.update-status', $siswa) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="aktif">
                                    <button type="submit" class="btn-icon btn-success" title="Aktifkan"
                                            onclick="return confirm('Aktifkan kembali {{ $siswa->nama }}?')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                                
                                <!-- More Actions - Inline Horizontal -->
                                <form method="POST" action="{{ route('admin.siswa.update-status', $siswa) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="nonaktif">
                                    <button type="submit" class="btn-icon btn-danger-inline" 
                                            onclick="return confirm('Ubah status {{ $siswa->nama }} menjadi NONAKTIF?')"
                                            title="Nonaktif">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete-inline" 
                                            onclick="return confirm('HAPUS data {{ $siswa->nama }}?')"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 3rem; color: #6c757d;">
                <i class="fas fa-user-clock" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                <h5>Tidak Ada Siswa Cuti</h5>
                <p>Belum ada siswa dengan status cuti.</p>
                <a href="{{ route('admin.siswa-aktif') }}" class="btn-compact btn-primary-compact">
                    <i class="fas fa-users me-1"></i>Lihat Siswa Aktif
                </a>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div style="margin-top: 20px;">
        <a href="{{ route('admin.dashboard') }}" class="btn-compact btn-primary-compact">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" style="border-radius: 8px; border: none;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert" style="border-radius: 8px; border: none;">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@endsection