@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-circle"></i></div>
    <h1 class="club-title">Profil Admin</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;max-width:900px;margin:0 auto;">

    {{-- Edit Profil --}}
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;background:linear-gradient(135deg,#d32f2f,#b71c1c);">
            <h3 style="margin:0;font-size:15px;font-weight:600;color:white;"><i class="fas fa-user-edit"></i> Edit Profil</h3>
        </div>

        @if(session('success'))
        <div style="margin:16px 22px 0;padding:10px 14px;background:#d4edda;color:#155724;border-radius:7px;font-size:13px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.profil.update') }}" style="padding:22px;display:grid;gap:16px;">
            @csrf

            {{-- Avatar --}}
            <div style="text-align:center;padding:10px 0;">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,#d32f2f,#b71c1c);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:32px;color:white;font-weight:700;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="font-size:16px;font-weight:700;color:#333;">{{ $user->name }}</div>
                <div style="font-size:12px;color:#999;">Administrator</div>
            </div>

            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Lengkap <span style="color:red">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    style="width:100%;padding:9px 11px;border:1px solid {{ $errors->has('name')?'#f44336':'#ddd' }};border-radius:6px;font-size:13px;box-sizing:border-box;">
                @error('name')<div style="color:#f44336;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Email <span style="color:red">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    style="width:100%;padding:9px 11px;border:1px solid {{ $errors->has('email')?'#f44336':'#ddd' }};border-radius:6px;font-size:13px;box-sizing:border-box;">
                @error('email')<div style="color:#f44336;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            <div style="background:#f8f9fa;border-radius:7px;padding:12px;font-size:12px;color:#666;">
                <i class="fas fa-info-circle" style="color:#2196f3;"></i>
                Akun dibuat: {{ $user->created_at->format('d M Y') }}
            </div>

            <button type="submit" style="background:#d32f2f;color:white;border:none;padding:11px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;background:linear-gradient(135deg,#1565c0,#0d47a1);">
            <h3 style="margin:0;font-size:15px;font-weight:600;color:white;"><i class="fas fa-lock"></i> Ganti Password</h3>
        </div>

        @if(session('success_password'))
        <div style="margin:16px 22px 0;padding:10px 14px;background:#d4edda;color:#155724;border-radius:7px;font-size:13px;">
            <i class="fas fa-check-circle"></i> {{ session('success_password') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.profil.password') }}" style="padding:22px;display:grid;gap:16px;">
            @csrf

            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Password Lama <span style="color:red">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="current_password" id="oldPass" required
                        style="width:100%;padding:9px 40px 9px 11px;border:1px solid {{ $errors->has('current_password')?'#f44336':'#ddd' }};border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <button type="button" onclick="togglePass('oldPass',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#999;font-size:14px;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('current_password')<div style="color:#f44336;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Password Baru <span style="color:red">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password" id="newPass" required minlength="6"
                        style="width:100%;padding:9px 40px 9px 11px;border:1px solid {{ $errors->has('password')?'#f44336':'#ddd' }};border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <button type="button" onclick="togglePass('newPass',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#999;font-size:14px;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')<div style="color:#f44336;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Konfirmasi Password Baru <span style="color:red">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password_confirmation" id="confPass" required
                        style="width:100%;padding:9px 40px 9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <button type="button" onclick="togglePass('confPass',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#999;font-size:14px;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="background:#e3f2fd;border-radius:7px;padding:12px;font-size:12px;color:#1565c0;">
                <i class="fas fa-shield-alt"></i>
                Password minimal 6 karakter. Gunakan kombinasi huruf dan angka untuk keamanan lebih baik.
            </div>

            <button type="submit" style="background:#1565c0;color:white;border:none;padding:11px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                <i class="fas fa-key"></i> Ganti Password
            </button>
        </form>
    </div>

</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endsection
