@extends('layouts.admin')

@section('title', 'Kelola Akun')

@section('content')
<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
    margin-right: 12px;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-details h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.user-details small {
    color: #666;
    font-size: 12px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #28a745;
}

.password-display {
    font-family: monospace;
    color: #666;
    font-size: 14px;
}

.reset-info {
    font-size: 11px;
    color: #999;
}

.action-buttons {
    display: flex;
    gap: 4px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-view {
    background-color: #e3f2fd;
    color: #1976d2;
}

.btn-view:hover {
    background-color: #bbdefb;
}

.btn-edit {
    background-color: #fff3e0;
    color: #f57c00;
}

.btn-edit:hover {
    background-color: #ffe0b2;
}

.btn-password {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.btn-password:hover {
    background-color: #e1bee7;
}

.btn-delete {
    background-color: #ffebee;
    color: #d32f2f;
}

.btn-delete:hover {
    background-color: #ffcdd2;
}

.modern-table {
    border: none;
}

.modern-table thead th {
    border: none;
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    padding: 16px 12px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-table tbody td {
    border: none;
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.modern-table tbody tr:hover {
    background-color: #f8f9fa;
}

.header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.header-section h2 {
    margin: 0;
    font-weight: 600;
}

.btn-modern {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    border: none;
    transition: all 0.2s;
}

.btn-generate {
    background-color: #4caf50;
    color: white;
}

.btn-generate:hover {
    background-color: #45a049;
    color: white;
}

.btn-create {
    background-color: #2196f3;
    color: white;
}

.btn-create:hover {
    background-color: #1976d2;
    color: white;
}
</style>

<div class="header-section">
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-users-cog me-2"></i>Kelola Akun</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.akun.bulk-generate') }}" class="btn btn-generate btn-modern">
                <i class="fas fa-magic me-1"></i> Generate Massal
            </a>
            <a href="{{ route('admin.akun.create') }}" class="btn btn-create btn-modern">
                <i class="fas fa-plus me-1"></i> Buat Akun Baru
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">User</th>
                        <th style="width: 10%;">Role</th>
                        <th style="width: 15%;">Info</th>
                        <th style="width: 20%;">Password</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    @php
                        $colors = ['#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#00bcd4', '#009688', '#4caf50', '#ff9800', '#ff5722'];
                        $colorIndex = $index % count($colors);
                        $avatarColor = $colors[$colorIndex];
                        $initials = strtoupper(substr($user->name, 0, 1));
                        
                        // Get related info
                        $roleInfo = '';
                        if($user->role == 'siswa' && $user->siswa) {
                            $roleInfo = 'Kelas: ' . ($user->siswa->kelas ?? 'Belum ada kelas');
                        } elseif($user->role == 'coach' && $user->coach) {
                            $roleInfo = $user->coach->spesialisasi ?? 'Pelatih';
                        }
                        
                        // Generate default password based on role
                        $currentPassword = $user->current_password ?? ($user->role == 'siswa' ? 'siswa123' : 'coach123');
                    @endphp
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background-color: {{ $avatarColor }};">
                                    {{ $initials }}
                                </div>
                                <div class="user-details">
                                    <h6>{{ $user->name }}</h6>
                                    <small>{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'siswa' ? 'primary' : 'success' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $roleInfo }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="password-field" id="password-{{ $user->id }}" style="font-family: monospace;">
                                    ••••••••
                                </span>
                                <button class="btn btn-sm btn-outline-secondary toggle-password" 
                                        data-user-id="{{ $user->id }}" 
                                        data-password="{{ $currentPassword }}"
                                        title="Lihat/Sembunyikan Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary copy-password" 
                                        data-password="{{ $currentPassword }}"
                                        title="Copy Password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">Current: {{ $currentPassword }}</small>
                        </td>
                        <td>
                            <span class="status-badge status-active">
                                <span class="status-indicator"></span>
                                Aktif
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.akun.show', $user) }}" class="action-btn btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.akun.edit-password', $user) }}" class="action-btn btn-password" title="Ganti Password">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="{{ route('admin.akun.edit', $user) }}" class="action-btn btn-edit" title="Edit Akun">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.akun.destroy', $user) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Hapus"
                                            onclick="return confirm('Yakin hapus akun {{ $user->name }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                            <div>Belum ada akun coach/siswa</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-3 py-3">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Password visibility toggle and copy functionality
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const password = this.dataset.password;
            const passwordField = document.getElementById(`password-${userId}`);
            const icon = this.querySelector('i');
            
            if (passwordField.textContent === '••••••••') {
                passwordField.textContent = password;
                icon.className = 'fas fa-eye-slash';
                this.title = 'Sembunyikan Password';
            } else {
                passwordField.textContent = '••••••••';
                icon.className = 'fas fa-eye';
                this.title = 'Lihat Password';
            }
        });
    });
    
    // Copy password to clipboard
    document.querySelectorAll('.copy-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const password = this.dataset.password;
            navigator.clipboard.writeText(password).then(() => {
                // Show success feedback
                const originalIcon = this.querySelector('i').className;
                this.querySelector('i').className = 'fas fa-check';
                this.classList.add('btn-success');
                this.classList.remove('btn-outline-primary');
                
                setTimeout(() => {
                    this.querySelector('i').className = originalIcon;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-primary');
                }, 1000);
            });
        });
    });
    
    // Add click handlers for view buttons
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.tagName === 'BUTTON') {
                e.preventDefault();
                // Handle button click if needed
            }
        });
    });
});
</script>
@endsection