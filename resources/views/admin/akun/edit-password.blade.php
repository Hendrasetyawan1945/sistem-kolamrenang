@extends('layouts.admin')

@section('title', 'Ganti Password - ' . $user->name)

@section('content')
<style>
.password-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.password-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 20px;
}

.user-info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
}

.password-option {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.password-option:hover {
    border-color: #007bff;
    background-color: #f8f9ff;
}

.password-option.selected {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.password-option input[type="radio"] {
    margin-right: 12px;
}

.password-preview {
    background: #f1f3f4;
    border-radius: 6px;
    padding: 8px 12px;
    font-family: monospace;
    font-size: 14px;
    margin-top: 8px;
}

.btn-modern {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    border: none;
    transition: all 0.2s;
}

.btn-save {
    background-color: #28a745;
    color: white;
}

.btn-save:hover {
    background-color: #218838;
    color: white;
}

.btn-cancel {
    background-color: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background-color: #5a6268;
    color: white;
}
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card password-card">
                <div class="password-header">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-key fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-1">Ganti Password</h4>
                            <p class="mb-0 opacity-75">Ubah password login untuk {{ $user->name }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- User Info -->
                    <div class="user-info-card">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nama:</strong> {{ $user->name }}<br>
                                <strong>Email:</strong> {{ $user->email }}<br>
                                <strong>Role:</strong> 
                                <span class="badge bg-{{ $user->role == 'siswa' ? 'primary' : 'success' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                @if($user->role == 'siswa' && $user->siswa)
                                    <strong>Kelas:</strong> {{ $user->siswa->kelas ?? 'Belum ada kelas' }}<br>
                                    <strong>Status:</strong> {{ ucfirst($user->siswa->status) }}
                                @elseif($user->role == 'coach' && $user->coach)
                                    <strong>Spesialisasi:</strong> {{ $user->coach->spesialisasi ?? 'Pelatih' }}<br>
                                    <strong>Pengalaman:</strong> {{ $user->coach->pengalaman ?? 'N/A' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Current Password Display -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-3"></i>
                        <div>
                            <strong>Password Saat Ini:</strong> 
                            <span class="badge bg-dark ms-2" style="font-size: 14px; font-family: monospace;">
                                {{ $user->current_password ?? 'Tidak tersedia' }}
                            </span>
                            @if($user->current_password)
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" 
                                        onclick="copyToClipboard('{{ $user->current_password }}')" 
                                        title="Copy password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.akun.update-password', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <h5 class="mb-3">Pilih Jenis Password Baru:</h5>
                        
                        <!-- Password Default -->
                        <div class="password-option" onclick="selectPasswordType('default')">
                            <label class="d-flex align-items-start">
                                <input type="radio" name="password_type" value="default" id="default" checked>
                                <div class="flex-grow-1">
                                    <strong>Password Default</strong>
                                    <p class="mb-1 text-muted">Gunakan password standar yang mudah diingat</p>
                                    <div class="password-preview" id="preview-default">
                                        123456
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Password Tanggal Lahir -->
                        @if($user->role == 'siswa' && $user->siswa && $user->siswa->tanggal_lahir)
                        <div class="password-option" onclick="selectPasswordType('tanggal_lahir')">
                            <label class="d-flex align-items-start">
                                <input type="radio" name="password_type" value="tanggal_lahir" id="tanggal_lahir">
                                <div class="flex-grow-1">
                                    <strong>Password Tanggal Lahir</strong>
                                    <p class="mb-1 text-muted">Gunakan tanggal lahir siswa (format: ddmmyyyy)</p>
                                    <div class="password-preview" id="preview-tanggal_lahir">
                                        {{ $user->siswa->tanggal_lahir->format('dmY') }}
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endif

                        <!-- Password Custom -->
                        <div class="password-option" onclick="selectPasswordType('custom')">
                            <label class="d-flex align-items-start">
                                <input type="radio" name="password_type" value="custom" id="custom">
                                <div class="flex-grow-1">
                                    <strong>Password Custom</strong>
                                    <p class="mb-1 text-muted">Buat password sendiri (minimal 6 karakter)</p>
                                    <div class="mt-2" id="custom-input" style="display: none;">
                                        <input type="password" name="custom_password" class="form-control" 
                                               placeholder="Masukkan password baru..." id="custom_password_input"
                                               minlength="6">
                                        <div class="form-text">Password minimal 6 karakter</div>
                                        <div class="password-preview mt-2" id="preview-custom" style="display: none;"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('custom_password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-save btn-modern">
                                <i class="fas fa-save me-2"></i>Simpan Password Baru
                            </button>
                            <a href="{{ route('admin.akun.index') }}" class="btn btn-cancel btn-modern">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPasswordType(type) {
    // Remove selected class from all options
    document.querySelectorAll('.password-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.getElementById(type).checked = true;
    
    // Show/hide custom input
    const customInput = document.getElementById('custom-input');
    const customPasswordInput = document.getElementById('custom_password_input');
    
    if (type === 'custom') {
        customInput.style.display = 'block';
        customPasswordInput.focus();
        // Make custom password required when selected
        customPasswordInput.setAttribute('required', 'required');
    } else {
        customInput.style.display = 'none';
        customPasswordInput.removeAttribute('required');
        customPasswordInput.value = ''; // Clear custom input
        document.getElementById('preview-custom').style.display = 'none';
    }
}

// Show custom password preview
document.getElementById('custom_password_input').addEventListener('input', function() {
    const preview = document.getElementById('preview-custom');
    if (this.value.length > 0) {
        preview.textContent = this.value;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

// Copy password function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show temporary success message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 1500);
    });
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const selectedType = document.querySelector('input[name="password_type"]:checked').value;
    const customPasswordInput = document.getElementById('custom_password_input');
    
    if (selectedType === 'custom') {
        if (!customPasswordInput.value || customPasswordInput.value.length < 6) {
            e.preventDefault();
            alert('Password custom minimal 6 karakter!');
            customPasswordInput.focus();
            return false;
        }
    }
});

// Initialize first option as selected
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.password-option').classList.add('selected');
});
</script>
@endsection