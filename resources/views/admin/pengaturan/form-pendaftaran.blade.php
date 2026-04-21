@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-file-alt"></i>
    </div>
    <h1 class="club-title">Form Pendaftaran</h1>
</div>

<!-- Pengaturan Form -->
<div class="dashboard-card" style="margin-bottom: 30px;">
    <h3 class="card-title">Konfigurasi Form Pendaftaran</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status Form</label>
                <select name="status_form" class="form-select">
                    <option value="aktif">Aktif - Menerima Pendaftaran</option>
                    <option value="nonaktif">Nonaktif - Tutup Pendaftaran</option>
                    <option value="terbatas">Terbatas - Kuota Terbatas</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kuota Maksimal</label>
                <input type="number" name="kuota_max" class="form-input" value="50" min="1">
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Pesan Pembuka</label>
            <textarea name="pesan_pembuka" class="form-input" rows="3">Selamat datang di Youth Swimming Club! Silakan isi form pendaftaran berikut dengan lengkap.</textarea>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Syarat & Ketentuan</label>
            <textarea name="syarat_ketentuan" class="form-input" rows="5">1. Calon siswa harus dalam kondisi sehat
2. Melampirkan surat keterangan sehat dari dokter
3. Membayar biaya pendaftaran
4. Mengikuti tes kemampuan dasar</textarea>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<!-- Field Form -->
<div class="dashboard-card">
    <h3 class="card-title">Field Form Pendaftaran</h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Field Name</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Label</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Type</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Required</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([
                    ['field' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true, 'status' => 'aktif'],
                    ['field' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true, 'status' => 'aktif'],
                    ['field' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'required' => true, 'status' => 'aktif'],
                    ['field' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => true, 'status' => 'aktif'],
                    ['field' => 'nama_ortu', 'label' => 'Nama Orang Tua', 'type' => 'text', 'required' => true, 'status' => 'aktif'],
                    ['field' => 'telepon', 'label' => 'No. Telepon', 'type' => 'tel', 'required' => true, 'status' => 'aktif'],
                    ['field' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => false, 'status' => 'aktif'],
                    ['field' => 'pengalaman_renang', 'label' => 'Pengalaman Renang', 'type' => 'select', 'required' => false, 'status' => 'nonaktif']
                ] as $field)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-family: monospace;">{{ $field['field'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: 600;">{{ $field['label'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-secondary">{{ $field['type'] }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            @if($field['required'])
                                <span style="color: #f44336; font-weight: 600;">Ya</span>
                            @else
                                <span style="color: #666;">Tidak</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $field['status'] == 'aktif' ? 'status-active' : 'status-pending' }}">
                                {{ ucfirst($field['status']) }}
                            </span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($field['status'] == 'aktif')
                                    <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #ff9800; color: white;">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                @else
                                    <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #4caf50; color: white;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<style>
    .form-input, .form-select {
        width: 100%;
        padding: 8px 12px;
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
    
    .status-secondary {
        background: #6c757d;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
</style>
@endsection