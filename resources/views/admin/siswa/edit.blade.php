@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-user-edit"></i>
    </div>
    <h1 class="club-title">Edit Data Siswa</h1>
</div>

<div class="dashboard-card" style="max-width: 800px; margin: 0 auto;">
    <h3 class="card-title">Form Edit Siswa</h3>

    @if($errors->any())
        <div style="margin-bottom: 20px; padding: 12px 16px; background: #f8d7da; color: #721c24; border-radius: 8px; border: 1px solid #f5c6cb;">
            <i class="fas fa-exclamation-triangle"></i>
            <ul style="margin: 8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST" style="display: grid; gap: 20px;">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Lengkap</label>
                <input type="text" name="nama" class="form-input" value="{{ old('nama', $siswa->nama) }}" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-input" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kelas</label>
                <select name="kelas" class="form-select" required>
                    <option value="pemula"    {{ old('kelas', $siswa->kelas) == 'pemula'    ? 'selected' : '' }}>Pemula</option>
                    <option value="menengah"  {{ old('kelas', $siswa->kelas) == 'menengah'  ? 'selected' : '' }}>Menengah</option>
                    <option value="lanjut"    {{ old('kelas', $siswa->kelas) == 'lanjut'    ? 'selected' : '' }}>Lanjut</option>
                    <option value="prestasi"  {{ old('kelas', $siswa->kelas) == 'prestasi'  ? 'selected' : '' }}>Prestasi</option>
                </select>
            </div>
        </div>

        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Alamat</label>
            <textarea name="alamat" class="form-input" rows="3" required>{{ old('alamat', $siswa->alamat) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Orang Tua</label>
                <input type="text" name="nama_ortu" class="form-input" value="{{ old('nama_ortu', $siswa->nama_ortu) }}" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">No. Telepon</label>
                <input type="tel" name="telepon" class="form-input" value="{{ old('telepon', $siswa->telepon) }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email', $siswa->email) }}">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Paket Kelas</label>
                <select name="paket" class="form-select" required>
                    <option value="8x"      {{ old('paket', $siswa->paket) == '8x'      ? 'selected' : '' }}>8x Pertemuan</option>
                    <option value="12x"     {{ old('paket', $siswa->paket) == '12x'     ? 'selected' : '' }}>12x Pertemuan</option>
                    <option value="bulanan" {{ old('paket', $siswa->paket) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
        </div>

        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status</label>
            <select name="status" class="form-select" required>
                <option value="calon"    {{ old('status', $siswa->status) == 'calon'    ? 'selected' : '' }}>Calon Siswa</option>
                <option value="aktif"    {{ old('status', $siswa->status) == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                <option value="cuti"     {{ old('status', $siswa->status) == 'cuti'     ? 'selected' : '' }}>Cuti</option>
                <option value="nonaktif" {{ old('status', $siswa->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Catatan Khusus</label>
            <textarea name="catatan" class="form-input" rows="2">{{ old('catatan', $siswa->catatan) }}</textarea>
        </div>

        <div class="action-buttons" style="justify-content: flex-end; margin-top: 10px;">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<style>
    .form-input, .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #d32f2f;
    }
    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }
</style>
@endsection
