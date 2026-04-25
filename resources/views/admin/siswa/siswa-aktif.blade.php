@extends('layouts.admin')

@section('title', 'Siswa Aktif')

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
    }
    
    .stats-info {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    
    .stat-item {
        font-size: 0.8rem;
        color: #6c757d;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    
    .stat-item:hover {
        background: #f8f9fa;
    }
    
    .stat-item.active {
        background: #e3f2fd;
        border-color: #2196f3;
        color: #1976d2;
    }
    
    .stat-item.static {
        cursor: default;
        padding: 0;
    }
    
    .stat-item.static:hover {
        background: transparent;
    }
    
    .stat-number {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .stat-item.active .stat-number {
        color: #1976d2;
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
    
    .btn-success-compact {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
    }
    
    .btn-success-compact:hover {
        background: #1e7e34;
        border-color: #1e7e34;
        color: white;
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
        background: linear-gradient(135deg, #007bff, #0056b3);
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
        max-width: 160px;
        display: inline-block;
    }
    
    /* Password styling */
    .password-display {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .password-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.7rem;
        padding: 3px 6px;
        border-radius: 4px;
        background: #343a40;
        color: white;
        border: none;
        min-width: 60px;
        text-align: center;
    }
    
    .copy-btn {
        width: 20px;
        height: 20px;
        font-size: 0.6rem;
        padding: 0;
        border: 1px solid #dee2e6;
        background: white;
        border-radius: 3px;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .copy-btn:hover {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .copy-btn.success {
        background: #28a745;
        color: white;
        border-color: #28a745;
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
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .badge-status-success {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-status-warning {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-status-danger {
        background: #f8d7da;
        color: #721c24;
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
    
    .btn-icon.btn-success:hover {
        background: #28a745;
        border-color: #28a745;
    }
    
    .btn-icon.btn-info:hover {
        background: #17a2b8;
        border-color: #17a2b8;
    }
    
    .btn-icon.btn-warning:hover {
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    
    /* Inline Action Buttons */
    .btn-warning-inline:hover {
        background: #ffc107 !important;
        color: #212529 !important;
        border-color: #ffc107 !important;
    }
    
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
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-users me-2"></i>Siswa Aktif
        </h1>
        <p class="page-subtitle">Kelola data siswa aktif dan akun login mereka</p>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stats-info">
            <div class="stat-item active" data-filter="all" onclick="filterSiswa('all')">
                Total: <span class="stat-number">{{ $totalSiswa }}</span>
            </div>
            <div class="stat-item" data-filter="punya-akun" onclick="filterSiswa('punya-akun')">
                Punya Akun: <span class="stat-number">{{ $sudahPunyaAkun }}</span>
            </div>
            <div class="stat-item" data-filter="belum-akun" onclick="filterSiswa('belum-akun')">
                Belum Akun: <span class="stat-number">{{ $belumPunyaAkun }}</span>
            </div>
            <div class="stat-item static">
                Email Valid: <span class="stat-number">{{ $emailValid }}</span>
            </div>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.akun.index') }}" class="btn-compact btn-primary-compact">
                <i class="fas fa-user-cog me-1"></i>Kelola Akun
            </a>
        </div>
    </div>

    @if($totalSiswa > 0)
    <div style="background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 6px; padding: 12px; margin-bottom: 20px; font-size: 0.85rem;">
        <i class="fas fa-info-circle text-info me-2"></i>
        <strong>Info:</strong> Menampilkan {{ $totalSiswa }} siswa aktif. 
        Jika ada siswa yang tidak muncul di daftar ini tapi ada di catatan waktu, 
        kemungkinan status mereka adalah "cuti" atau "nonaktif". 
        Periksa menu <a href="{{ route('admin.siswa-cuti') }}" class="text-decoration-none">Siswa Cuti</a> 
        atau <a href="{{ route('admin.siswa-nonaktif') }}" class="text-decoration-none">Siswa Nonaktif</a>.
    </div>
    @endif

    <!-- Simple Table -->
    <div class="table-container">
        @if($siswas->count() > 0)
            <table class="table-clean">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th width="180">Siswa</th>
                        <th width="160">Email</th>
                        <th width="100">Password</th>
                        <th width="60">Kelas</th>
                        <th width="90">Status Akun</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $index => $siswa)
                    <tr class="siswa-row" 
                        data-has-akun="{{ $siswa->user ? 'true' : 'false' }}"
                        data-status-akun="{{ $siswa->user ? 'ada-akun' : ($siswa->email && filter_var($siswa->email, FILTER_VALIDATE_EMAIL) ? 'siap-dibuat' : 'email-invalid') }}">
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
                                @if(!filter_var($siswa->email, FILTER_VALIDATE_EMAIL))
                                    <span style="color: #dc3545; margin-left: 5px;">✗</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->user && $siswa->user->current_password)
                                <div class="password-display">
                                    <span class="password-badge">
                                        {{ $siswa->user->current_password }}
                                    </span>
                                    <button type="button" class="copy-btn" 
                                            onclick="copyToClipboard('{{ $siswa->user->current_password }}')" 
                                            title="Copy password">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->kelas)
                                <span class="badge-simple badge-kelas">
                                    @if(is_object($siswa->kelas) && isset($siswa->kelas->nama_kelas))
                                        {{ $siswa->kelas->nama_kelas }}
                                    @else
                                        {{ is_string($siswa->kelas) ? $siswa->kelas : 'N/A' }}
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->user)
                                <span class="badge-simple badge-status-success">✓ Ada Akun</span>
                            @else
                                @if($siswa->email && filter_var($siswa->email, FILTER_VALIDATE_EMAIL))
                                    <span class="badge-simple badge-status-warning">⏳ Siap Dibuat</span>
                                @else
                                    <span class="badge-simple badge-status-danger">✗ Email Invalid</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <!-- Edit -->
                                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Account -->
                                @if($siswa->user)
                                    <a href="{{ route('admin.akun.show', $siswa->user) }}" class="btn-icon btn-info" title="Akun">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <!-- Edit Password -->
                                    <a href="{{ route('admin.akun.edit-password', $siswa->user) }}" class="btn-icon btn-warning" title="Edit Password">
                                        <i class="fas fa-key"></i>
                                    </a>
                                @else
                                    @if($siswa->email && filter_var($siswa->email, FILTER_VALIDATE_EMAIL))
                                        <form method="POST" action="{{ route('admin.siswa.generate-akun', $siswa) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-success" title="Buat Akun"
                                                    onclick="return confirm('Buat akun untuk {{ $siswa->nama }}?')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                <!-- More Actions - Inline Horizontal -->
                                <form method="POST" action="{{ route('admin.siswa.update-status', $siswa) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cuti">
                                    <button type="submit" class="btn-icon btn-warning-inline" 
                                            onclick="return confirm('Ubah status {{ $siswa->nama }} menjadi CUTI?')"
                                            title="Cuti">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                                
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
                <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                <h5>Belum Ada Siswa Aktif</h5>
                <p>Belum ada siswa dengan status aktif.</p>
                <a href="{{ route('admin.calon-siswa') }}" class="btn-compact btn-primary-compact">
                    <i class="fas fa-user-plus me-1"></i>Lihat Calon Siswa
                </a>
            </div>
        @endif
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

<!-- Modal Buat Akun Massal -->
<!-- REMOVED: Section Buat Akun Massal telah dihapus sesuai permintaan -->

<script>
// Filter siswa berdasarkan status akun
function filterSiswa(filter) {
    // Update active state pada tabs
    document.querySelectorAll('.stat-item[data-filter]').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.stat-item[data-filter="${filter}"]`).classList.add('active');
    
    // Filter rows
    const rows = document.querySelectorAll('.siswa-row');
    rows.forEach(row => {
        const hasAkun = row.getAttribute('data-has-akun');
        const statusAkun = row.getAttribute('data-status-akun');
        
        let shouldShow = false;
        
        if (filter === 'all') {
            shouldShow = true;
        } else if (filter === 'punya-akun') {
            shouldShow = hasAkun === 'true';
        } else if (filter === 'belum-akun') {
            shouldShow = hasAkun === 'false' && statusAkun === 'siap-dibuat';
        }
        
        row.style.display = shouldShow ? '' : 'none';
    });
}

// Copy password function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show temporary success message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.add('success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('success');
        }, 1500);
    }).catch(function(err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        // Show success feedback
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.add('success');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('success');
        }, 1500);
    });
}
</script>
@endsection