@extends('layouts.admin')

@section('title', 'Buat Akun Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Buat Akun Baru</h2>
    <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.akun.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" id="roleSelect">
                            <option value="">Pilih Role</option>
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}>Guru</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="siswaSelect" style="display: none;">
                        <label class="form-label">Pilih Siswa</label>
                        <select name="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror">
                            <option value="">Pilih Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }} - {{ $siswa->kelas->nama ?? 'Belum ada kelas' }}
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($siswaList->count() == 0)
                            <small class="text-muted">Semua siswa sudah memiliki akun</small>
                        @endif
                    </div>
                    
                    <div class="mb-3" id="coachSelect" style="display: none;">
                        <label class="form-label">Pilih Guru</label>
                        <select name="coach_id" class="form-select @error('coach_id') is-invalid @enderror">
                            <option value="">Pilih Guru</option>
                            @foreach($coachList as $coach)
                                <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
                                    {{ $coach->nama }} - {{ $coach->spesialisasi ?? 'Guru' }}
                                </option>
                            @endforeach
                        </select>
                        @error('coach_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($coachList->count() == 0)
                            <small class="text-muted">Semua guru sudah memiliki akun</small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Buat Akun
                        </button>
                        <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Informasi</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-info-circle text-info"></i> Pilih role terlebih dahulu</li>
                    <li><i class="fas fa-user text-primary"></i> Nama akan otomatis diambil dari data siswa/coach</li>
                    <li><i class="fas fa-lock text-warning"></i> Password minimal 6 karakter</li>
                    <li><i class="fas fa-envelope text-success"></i> Email harus unik</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const role = this.value;
    const siswaSelect = document.getElementById('siswaSelect');
    const coachSelect = document.getElementById('coachSelect');
    
    if (role === 'siswa') {
        siswaSelect.style.display = 'block';
        coachSelect.style.display = 'none';
    } else if (role === 'coach') {
        siswaSelect.style.display = 'none';
        coachSelect.style.display = 'block';
    } else {
        siswaSelect.style.display = 'none';
        coachSelect.style.display = 'none';
    }
});

// Trigger on page load if old value exists
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    if (roleSelect.value) {
        roleSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection