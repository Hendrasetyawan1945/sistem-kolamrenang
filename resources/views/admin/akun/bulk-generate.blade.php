@extends('layouts.admin')

@section('title', 'Generate Akun Massal')

@section('content')
<style>
    .page-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .page-subtitle {
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border-left: 5px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .stat-card.primary { border-left-color: #007bff; }
    .stat-card.success { border-left-color: #28a745; }
    .stat-card.warning { border-left-color: #ffc107; }
    .stat-card.info { border-left-color: #17a2b8; }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
        margin: 0;
        position: relative;
        z-index: 2;
    }
    
    .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2rem;
        opacity: 0.2;
        z-index: 1;
    }
    
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .password-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .password-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .radio-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .radio-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .radio-card:hover {
        border-color: #007bff;
        box-shadow: 0 5px 15px rgba(0,123,255,0.1);
    }
    
    .radio-card.active {
        border-color: #007bff;
        background: #f8f9ff;
        box-shadow: 0 5px 15px rgba(0,123,255,0.15);
    }
    
    .radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .radio-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }
    
    .radio-desc {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 0;
    }
    
    .select-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem 2rem;
    }
    
    .select-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f3f4;
    }
    
    .select-title {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .select-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-select {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid #dee2e6;
        background: white;
        color: #495057;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-select:hover {
        background: #f8f9fa;
        border-color: #007bff;
        color: #007bff;
    }
    
    .student-table {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .student-table thead th {
        background: linear-gradient(135deg, #495057, #6c757d);
        color: white;
        font-weight: 600;
        padding: 1rem;
        border: none;
        font-size: 0.9rem;
    }
    
    .student-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }
    
    .student-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .student-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .student-table tbody tr.selected {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
    }
    
    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .student-info {
        display: flex;
        align-items: center;
    }
    
    .student-details h6 {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .student-meta {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .badge-custom {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .checkbox-custom {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid #dee2e6;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .checkbox-custom:checked {
        background: #007bff;
        border-color: #007bff;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 0 0 20px 20px;
    }
    
    .btn-action {
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary-action {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        box-shadow: 0 5px 15px rgba(0,123,255,0.3);
    }
    
    .btn-primary-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,123,255,0.4);
    }
    
    .btn-secondary-action {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary-action:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    
    .back-button {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(220,53,69,0.3);
    }
    
    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220,53,69,0.4);
        color: white;
        text-decoration: none;
    }
    
    .info-panel {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .radio-group {
            grid-template-columns: 1fr;
        }
        
        .select-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Generate Akun Massal dari Data Siswa</h1>
                <p class="page-subtitle">Pilih siswa yang akan dibuatkan akun login</p>
            </div>
            <a href="{{ route('admin.akun.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-number">{{ $siswaList->count() }}</div>
            <p class="stat-label">Siswa yang Belum Memiliki Akun</p>
            <i class="fas fa-users stat-icon"></i>
        </div>
        
        <div class="stat-card success">
            <div class="stat-number">{{ $siswaList->where('status', 'aktif')->count() }}</div>
            <p class="stat-label">Siswa Aktif</p>
            <i class="fas fa-user-check stat-icon"></i>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-number">{{ $siswaList->filter(function($s) { return $s->email && filter_var($s->email, FILTER_VALIDATE_EMAIL); })->count() }}</div>
            <p class="stat-label">Email Valid</p>
            <i class="fas fa-envelope-check stat-icon"></i>
        </div>
        
        <div class="stat-card info">
            <div class="stat-number">0</div>
            <p class="stat-label">Terpilih</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>

    @if($siswaList->count() > 0)
        <form method="POST" action="{{ route('admin.akun.bulk-store') }}" id="bulkForm">
            @csrf
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="main-card">
                        <!-- Password Selection -->
                        <div class="password-section">
                            <h5 class="password-title">
                                <i class="fas fa-key text-primary"></i>
                                Jenis Password
                            </h5>
                            <div class="radio-group">
                                <label class="radio-card active" for="password_default">
                                    <input type="radio" name="password_type" id="password_default" value="default" checked>
                                    <div class="radio-label">Default (123456)</div>
                                    <p class="radio-desc">Password standar yang mudah diingat</p>
                                </label>
                                
                                <label class="radio-card" for="password_birth">
                                    <input type="radio" name="password_type" id="password_birth" value="tanggal_lahir">
                                    <div class="radio-label">Tanggal Lahir (ddmmyyyy)</div>
                                    <p class="radio-desc">Menggunakan tanggal lahir siswa</p>
                                </label>
                                
                                <label class="radio-card" for="password_custom">
                                    <input type="radio" name="password_type" id="password_custom" value="custom">
                                    <div class="radio-label">Custom</div>
                                    <p class="radio-desc">Password yang Anda tentukan</p>
                                </label>
                            </div>
                            
                            <div id="customPasswordDiv" style="display: none; margin-top: 1rem;">
                                <input type="password" name="custom_password" class="form-control" 
                                       placeholder="Masukkan password custom (minimal 6 karakter)"
                                       style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                        </div>

                        <!-- Student Selection -->
                        <div class="select-section">
                            <div class="select-header">
                                <h5 class="select-title">
                                    <i class="fas fa-list-check text-success"></i>
                                    Pilih Semua (<span id="selectedCount">0</span> siswa)
                                </h5>
                                <div class="select-actions">
                                    <button type="button" class="btn-select" id="selectAllBtn">
                                        <i class="fas fa-check-double"></i> Pilih Semua
                                    </button>
                                    <button type="button" class="btn-select" id="clearAllBtn">
                                        <i class="fas fa-times"></i> Batal Pilih
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table student-table">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllTable" class="checkbox-custom">
                                            </th>
                                            <th>Nama Siswa</th>
                                            <th>Email</th>
                                            <th>Kelas</th>
                                            <th>Status</th>
                                            <th>Tanggal Lahir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswaList as $siswa)
                                        <tr class="student-row" data-siswa-id="{{ $siswa->id }}">
                                            <td>
                                                <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" 
                                                       class="checkbox-custom siswa-checkbox">
                                            </td>
                                            <td>
                                                <div class="student-info">
                                                    <div class="student-avatar">
                                                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                                    </div>
                                                    <div class="student-details">
                                                        <h6>{{ $siswa->nama }}</h6>
                                                        <div class="student-meta">
                                                            <i class="fas fa-{{ $siswa->jenis_kelamin == 'L' ? 'mars text-primary' : 'venus text-danger' }}"></i>
                                                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} • 
                                                            {{ $siswa->tanggal_lahir->age }} tahun
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $siswa->email }}</div>
                                                @if(!filter_var($siswa->email, FILTER_VALIDATE_EMAIL))
                                                    <span class="badge bg-danger badge-custom">Email tidak valid</span>
                                                @else
                                                    <span class="badge bg-success badge-custom">Email valid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($siswa->kelas)
                                                    <span class="badge bg-primary badge-custom">
                                                        {{ is_object($siswa->kelas) ? $siswa->kelas->nama_kelas : $siswa->kelas }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : 'warning' }} badge-custom">
                                                    {{ ucfirst($siswa->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $siswa->tanggal_lahir->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="btn-action btn-primary-action" id="generateBtn" disabled>
                                <i class="fas fa-users"></i>
                                Generate Akun Terpilih
                            </button>
                            <button type="button" class="btn-action btn-secondary-action" onclick="clearSelection()">
                                <i class="fas fa-times"></i>
                                Batal Pilih
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="info-panel">
                        <div class="info-card" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 20px 20px 0 0; padding: 1.5rem;">
                            <h6 style="color: #1976d2; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-info-circle"></i>
                                Cara Kerja
                            </h6>
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Pilih siswa yang akan dibuatkan akun</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Pilih jenis password yang diinginkan</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Sistem akan menggunakan email siswa sebagai username</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Akun akan dibuat dengan role "siswa"</li>
                            </ul>
                        </div>
                        
                        <div class="info-card" style="background: linear-gradient(135deg, #fff3e0, #ffcc02); padding: 1.5rem; border-bottom: 1px solid #f1f3f4;">
                            <h6 style="color: #f57c00; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                                Persyaratan
                            </h6>
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Siswa harus memiliki email yang valid</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Email belum digunakan oleh user lain</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;">Status siswa aktif (recommended)</li>
                            </ul>
                        </div>
                        
                        <div class="info-card" style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 0 0 20px 20px; padding: 1.5rem;">
                            <h6 style="color: #388e3c; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-key"></i>
                                Jenis Password
                            </h6>
                            <ul style="margin: 0; padding-left: 1.25rem;">
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;"><strong>Default:</strong> 123456 (mudah diingat)</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;"><strong>Tanggal Lahir:</strong> ddmmyyyy (personal)</li>
                                <li style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6c757d;"><strong>Custom:</strong> Password yang Anda tentukan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="main-card">
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h4>Tidak Ada Siswa</h4>
                <p class="text-muted mb-4">
                    Semua siswa aktif yang memiliki email sudah memiliki akun login,<br>
                    atau belum ada siswa dengan email yang valid.
                </p>
                <a href="{{ route('admin.siswa-aktif') }}" class="btn btn-primary">
                    <i class="fas fa-users"></i> Lihat Data Siswa
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const selectAllTable = document.getElementById('selectAllTable');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const generateBtn = document.getElementById('generateBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');
    const radioCards = document.querySelectorAll('.radio-card');
    const customPasswordDiv = document.getElementById('customPasswordDiv');
    
    // Update selected count and button state
    function updateSelection() {
        const checkedBoxes = document.querySelectorAll('.siswa-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCountSpan.textContent = count;
        generateBtn.disabled = count === 0;
        
        // Update stats card
        document.querySelector('.stat-card.info .stat-number').textContent = count;
        
        // Update select all button state
        selectAllTable.checked = count === siswaCheckboxes.length;
        selectAllTable.indeterminate = count > 0 && count < siswaCheckboxes.length;
        
        // Update row highlighting
        siswaCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.classList.add('selected');
            } else {
                row.classList.remove('selected');
            }
        });
    }
    
    // Select all functionality
    selectAllTable.addEventListener('change', function() {
        siswaCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelection();
    });
    
    selectAllBtn.addEventListener('click', function() {
        siswaCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelection();
    });
    
    clearAllBtn.addEventListener('click', function() {
        siswaCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelection();
    });
    
    // Individual checkbox change
    siswaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });
    
    // Radio card styling
    radioCards.forEach(card => {
        card.addEventListener('click', function() {
            radioCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Show/hide custom password field
            if (radio.value === 'custom') {
                customPasswordDiv.style.display = 'block';
            } else {
                customPasswordDiv.style.display = 'none';
            }
        });
    });
    
    // Form validation
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.siswa-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 siswa untuk dibuatkan akun');
            return false;
        }
        
        const passwordType = document.querySelector('input[name="password_type"]:checked').value;
        if (passwordType === 'custom') {
            const customPassword = document.querySelector('input[name="custom_password"]').value;
            if (!customPassword || customPassword.length < 6) {
                e.preventDefault();
                alert('Password custom minimal 6 karakter');
                return false;
            }
        }
        
        // Show loading state
        generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat Akun...';
        generateBtn.disabled = true;
        
        return confirm(`Yakin ingin membuat akun untuk ${checkedBoxes.length} siswa?`);
    });
    
    // Initialize
    updateSelection();
});

function clearSelection() {
    document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.querySelector('.stat-card.info .stat-number').textContent = '0';
    document.getElementById('selectedCount').textContent = '0';
    document.getElementById('generateBtn').disabled = true;
    document.getElementById('selectAllTable').checked = false;
    document.querySelectorAll('tr.selected').forEach(row => {
        row.classList.remove('selected');
    });
}
</script>
@endpush
@endsection