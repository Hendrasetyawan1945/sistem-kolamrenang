@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-tie"></i></div>
    <h1 class="club-title">Daftar Coach</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#4caf50,#388e3c);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $coaches->where('status','aktif')->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Coach Aktif</div>
    </div>
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $coaches->where('status','cuti')->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Sedang Cuti</div>
    </div>
    <div style="background:linear-gradient(135deg,#607d8b,#37474f);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $coaches->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Coach</div>
    </div>
</div>

<!-- Tombol Tambah -->
<div style="margin-bottom:20px;">
    <button onclick="openModal()" style="background:#d32f2f;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
        <i class="fas fa-plus"></i> Tambah Coach Baru
    </button>
</div>

<!-- Tabel Coach -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">Daftar Coach ({{ $coaches->count() }})</h3>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Coach</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Spesialisasi</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Pengalaman</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Kontak</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Password Login</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Status</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coaches as $i => $c)
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;background:linear-gradient(135deg,#d32f2f,#b71c1c);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($c->nama,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $c->nama }}</div>
                                @if($c->email)<div style="font-size:11px;color:#999;">{{ $c->email }}</div>@endif
                                @if($c->user)
                                    <div style="font-size:10px;color:#4caf50;margin-top:2px;">
                                        <i class="fas fa-check-circle"></i> Dapat Login
                                    </div>
                                @else
                                    <div style="font-size:10px;color:#ff9800;margin-top:2px;">
                                        <i class="fas fa-exclamation-triangle"></i> Belum Bisa Login
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $c->spesialisasi ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $c->pengalaman ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $c->telepon ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($c->user && $c->current_password)
                            <div style="display:flex;align-items:center;gap:5px;">
                                <input type="password" id="pwd-{{ $c->id }}" value="{{ $c->current_password }}" 
                                       readonly style="border:none;background:transparent;width:70px;font-size:12px;color:#333;">
                                <button onclick="togglePassword({{ $c->id }})" 
                                        style="background:none;border:none;cursor:pointer;font-size:12px;color:#666;" 
                                        title="Show/Hide Password">
                                    <i class="fas fa-eye" id="eye-{{ $c->id }}"></i>
                                </button>
                                <button onclick="copyPassword({{ $c->id }})" 
                                        style="background:none;border:none;cursor:pointer;font-size:12px;color:#666;" 
                                        title="Copy Password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            @if($c->password_updated_at)
                                <div style="font-size:10px;color:#999;margin-top:2px;">
                                    Reset: {{ \Carbon\Carbon::parse($c->password_updated_at)->diffForHumans() }}
                                </div>
                            @endif
                        @elseif($c->user)
                            <div style="font-size:11px;color:#ff9800;">
                                <i class="fas fa-exclamation-triangle"></i> Password tidak tersimpan
                                <div style="font-size:10px;color:#666;margin-top:2px;">Klik reset untuk generate ulang</div>
                            </div>
                        @else
                            <span style="font-size:11px;color:#999;">Belum ada akun</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @php $sc = ['aktif'=>['#e8f5e9','#2e7d32'],'cuti'=>['#fff3e0','#e65100'],'nonaktif'=>['#f5f5f5','#757575']]; $col = $sc[$c->status] ?? ['#f5f5f5','#333']; @endphp
                        <span style="background:{{ $col[0] }};color:{{ $col[1] }};padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;">
                            {{ ucfirst($c->status) }}
                        </span>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            <button onclick="openEditModal({{ $c->id }},'{{ addslashes($c->nama) }}','{{ addslashes($c->spesialisasi ?? '') }}','{{ addslashes($c->pengalaman ?? '') }}','{{ $c->telepon ?? '' }}','{{ $c->email ?? '' }}','{{ addslashes($c->bio ?? '') }}','{{ $c->status }}')"
                                style="background:#fff3e0;color:#f57c00;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;" title="Edit Coach">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            @if($c->user)
                                <form method="POST" action="{{ route('admin.coach.reset-password', $c) }}" style="display:inline;" 
                                      onsubmit="return confirm('Reset password untuk {{ $c->nama }}?')">
                                    @csrf
                                    <button type="submit" style="background:#e3f2fd;color:#1976d2;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.coach.destroy', $c) }}" onsubmit="return confirm('Hapus coach {{ $c->nama }}?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#ffebee;color:#f44336;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;" title="Hapus Coach">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:50px;text-align:center;color:#999;">
                        <i class="fas fa-user-tie" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Belum ada coach. Klik "Tambah Coach Baru" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-plus" style="color:#d32f2f;"></i> Tambah Coach Baru</h3>
        <form method="POST" action="{{ route('admin.coach.store') }}">
            @csrf
            @include('admin.pengaturan._form-coach')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#d32f2f;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-edit" style="color:#f57c00;"></i> Edit Coach</h3>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            @include('admin.pengaturan._form-coach')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeEditModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#f57c00;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() { 
    document.getElementById('addModal').style.display = 'flex'; 
    
    // Add event listeners for radio buttons in add modal
    const addModal = document.getElementById('addModal');
    const addRadioButtons = addModal.querySelectorAll('input[name="password_option"]');
    addRadioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const manualSection = addModal.querySelector('#manual-password-section');
            const passwordInput = addModal.querySelector('input[name="custom_password"]');
            
            if (this.value === 'manual') {
                manualSection.style.display = 'block';
                passwordInput.required = true;
            } else {
                manualSection.style.display = 'none';
                passwordInput.required = false;
                passwordInput.value = '';
            }
        });
    });
}
function closeModal() { document.getElementById('addModal').style.display = 'none'; }
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

function openEditModal(id, nama, spesialisasi, pengalaman, telepon, email, bio, status) {
    const f = document.getElementById('editForm');
    f.action = `/admin/coach/${id}`;
    f.querySelector('[name="nama"]').value = nama;
    f.querySelector('[name="spesialisasi"]').value = spesialisasi;
    f.querySelector('[name="pengalaman"]').value = pengalaman;
    f.querySelector('[name="telepon"]').value = telepon;
    f.querySelector('[name="email"]').value = email;
    f.querySelector('[name="bio"]').value = bio;
    f.querySelector('[name="status"]').value = status;
    
    // Show current password section and get current password
    const currentPasswordSection = f.querySelector('#current-password-section');
    const currentPasswordDisplay = f.querySelector('#current-password-display');
    const keepPasswordText = f.querySelector('#keep-password-text');
    
    // Find current password from the table
    const passwordInput = document.getElementById(`pwd-${id}`);
    if (passwordInput && passwordInput.value && passwordInput.value !== '••••••••') {
        currentPasswordSection.style.display = 'block';
        currentPasswordDisplay.value = passwordInput.value;
        currentPasswordDisplay.type = 'password'; // Start hidden
        keepPasswordText.textContent = 'Tetap gunakan password saat ini';
        
        // Reset password options
        f.querySelector('input[name="password_option"][value="keep"]').checked = true;
        f.querySelector('#manual-password-section').style.display = 'none';
        f.querySelector('input[name="custom_password"]').required = false;
    } else {
        currentPasswordSection.style.display = 'none';
        keepPasswordText.textContent = 'Generate password otomatis (Rekomendasi)';
        
        // Set default to auto-generate for coaches without password
        f.querySelector('input[name="password_option"][value="keep"]').checked = true;
        f.querySelector('#manual-password-section').style.display = 'none';
        f.querySelector('input[name="custom_password"]').required = false;
    }
    
    // Add event listeners for radio buttons in edit modal
    const editRadioButtons = f.querySelectorAll('input[name="password_option"]');
    editRadioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const manualSection = f.querySelector('#manual-password-section');
            const passwordInput = f.querySelector('input[name="custom_password"]');
            
            if (this.value === 'manual') {
                manualSection.style.display = 'block';
                passwordInput.required = true;
            } else {
                manualSection.style.display = 'none';
                passwordInput.required = false;
                passwordInput.value = '';
            }
        });
    });
    
    document.getElementById('editModal').style.display = 'flex';
}

// Password Management Functions
function togglePassword(coachId) {
    const input = document.getElementById(`pwd-${coachId}`);
    const eye = document.getElementById(`eye-${coachId}`);
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'fas fa-eye';
    }
}

function copyPassword(coachId) {
    const input = document.getElementById(`pwd-${coachId}`);
    const originalType = input.type;
    
    // Temporarily show password to copy
    input.type = 'text';
    input.select();
    input.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        showToast('Password berhasil disalin!', 'success');
    } catch (err) {
        showToast('Gagal menyalin password', 'error');
    }
    
    // Restore original type
    input.type = originalType;
    input.blur();
}

// Toast Notification Function
function showToast(message, type = 'success') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease-out;
    `;
    toast.textContent = message;
    
    // Add CSS animation
    if (!document.querySelector('#toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

document.querySelectorAll('#addModal,#editModal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.style.display = 'none'; });
});
</script>
@endsection
