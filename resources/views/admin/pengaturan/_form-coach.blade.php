<div style="display:grid;gap:14px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Coach <span style="color:red">*</span></label>
            <input type="text" name="nama" required placeholder="Nama lengkap"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Email <span style="color:red">*</span></label>
            <input type="email" name="email" required placeholder="coach@email.com"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            <small style="font-size:11px;color:#666;margin-top:2px;display:block;">Email ini akan digunakan untuk login ke sistem</small>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Status <span style="color:red">*</span></label>
            <select name="status" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <option value="aktif">Aktif</option>
                <option value="cuti">Cuti</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">No. Telepon</label>
            <input type="text" name="telepon" placeholder="08xxxxxxxxxx"
                style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
        </div>
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Spesialisasi</label>
        <input type="text" name="spesialisasi" placeholder="Contoh: Freestyle, Backstroke"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Pengalaman</label>
        <input type="text" name="pengalaman" placeholder="Contoh: 5 tahun"
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
    </div>
    <div>
        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Bio / Catatan</label>
        <textarea name="bio" rows="2" placeholder="Latar belakang, sertifikasi, dll..."
            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;"></textarea>
    </div>
    
    <!-- Password Management Section -->
    <div style="border:1px solid #e0e0e0;border-radius:8px;padding:14px;background:#fafafa;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
            <i class="fas fa-key" style="color:#2196f3;"></i>
            <label style="font-size:13px;font-weight:600;margin:0;">Pengaturan Password</label>
        </div>
        
        <!-- Show current password if editing existing coach -->
        <div id="current-password-section" style="display:none;margin-bottom:12px;padding:10px;background:#fff3cd;border-radius:6px;border:1px solid #ffeaa7;">
            <div style="font-size:12px;font-weight:600;color:#856404;margin-bottom:5px;">
                <i class="fas fa-info-circle"></i> Password Saat Ini:
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="text" id="current-password-display" readonly 
                       style="background:transparent;border:none;font-size:13px;color:#856404;font-weight:600;flex:1;">
                <button type="button" onclick="toggleCurrentPassword()" 
                        style="background:none;border:none;cursor:pointer;color:#856404;" 
                        title="Show/Hide Current Password">
                    <i class="fas fa-eye" id="current-eye"></i>
                </button>
                <button type="button" onclick="copyCurrentPassword()" 
                        style="background:none;border:none;cursor:pointer;color:#856404;" 
                        title="Copy Current Password">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        
        <div style="margin-bottom:12px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:12px;">
                <input type="radio" name="password_option" value="keep" checked onchange="togglePasswordInput()">
                <span id="keep-password-text">Generate password otomatis (Rekomendasi)</span>
            </label>
        </div>
        
        <div style="margin-bottom:12px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:12px;">
                <input type="radio" name="password_option" value="manual" onchange="togglePasswordInput()">
                <span>Set password manual</span>
            </label>
        </div>
        
        <div id="manual-password-section" style="display:none;margin-top:10px;padding:10px;background:#f8f9fa;border-radius:6px;border:1px solid #e9ecef;">
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:5px;color:#495057;">
                <i class="fas fa-lock" style="color:#6c757d;margin-right:5px;"></i>Password Baru
            </label>
            <input type="password" name="custom_password" placeholder="Masukkan password baru (min. 6 karakter)" 
                   style="width:100%;padding:8px 10px;border:1px solid #ced4da;border-radius:6px;font-size:13px;box-sizing:border-box;">
            <small style="font-size:11px;color:#6c757d;margin-top:4px;display:block;">
                <i class="fas fa-info-circle" style="margin-right:3px;"></i>Password harus minimal 6 karakter
            </small>
        </div>
    </div>
    
    <!-- Info Auto-Generate Password -->
    <div style="background:#e3f2fd;border-radius:8px;padding:12px;border-left:4px solid #2196f3;">
        <div style="font-size:12px;color:#1976d2;font-weight:600;margin-bottom:4px;">
            <i class="fas fa-info-circle"></i> Informasi Login
        </div>
        <div style="font-size:11px;color:#1565c0;line-height:1.4;">
            • Password akan ditampilkan di dashboard admin untuk referensi<br>
            • Kredensial login akan ditampilkan setelah coach dibuat<br>
            • Password dapat direset kapan saja dari dashboard admin
        </div>
    </div>
</div>

<script>
function togglePasswordInput() {
    const manualSection = document.getElementById('manual-password-section');
    const manualRadio = document.querySelector('input[name="password_option"][value="manual"]');
    const passwordInput = document.querySelector('input[name="custom_password"]');
    
    console.log('Toggle called, manual radio checked:', manualRadio.checked); // Debug
    
    if (manualRadio && manualRadio.checked) {
        manualSection.style.display = 'block';
        passwordInput.required = true;
        console.log('Showing manual password section'); // Debug
    } else {
        manualSection.style.display = 'none';
        passwordInput.required = false;
        passwordInput.value = '';
        console.log('Hiding manual password section'); // Debug
    }
}

// Functions for current password display
function toggleCurrentPassword() {
    const input = document.getElementById('current-password-display');
    const eye = document.getElementById('current-eye');
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'fas fa-eye';
    }
}

function copyCurrentPassword() {
    const input = document.getElementById('current-password-display');
    const originalType = input.type;
    
    // Temporarily show password to copy
    input.type = 'text';
    input.select();
    input.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        showToast('Password lama berhasil disalin!', 'success');
    } catch (err) {
        showToast('Gagal menyalin password', 'error');
    }
    
    // Restore original type
    input.type = originalType;
    input.blur();
}

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
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// Initialize form when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to radio buttons
    const radioButtons = document.querySelectorAll('input[name="password_option"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', togglePasswordInput);
    });
    
    // Initial call to set correct state
    togglePasswordInput();
});
</script>
