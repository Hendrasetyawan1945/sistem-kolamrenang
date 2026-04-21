@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-cogs"></i>
    </div>
    <h1 class="club-title">Master Ukuran Jersey</h1>
</div>

<!-- Tombol Aksi -->
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Ukuran Baru
    </button>
    <button class="btn" style="background: #4caf50; color: white;">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</div>

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Master Data Ukuran Jersey</h4>
            <div>
                <input type="text" placeholder="Cari ukuran..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Ukuran</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Lebar Dada (cm)</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Panjang Badan (cm)</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Panjang Lengan (cm)</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Usia (Tahun)</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Berat (kg)</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([
                    ['ukuran' => 'XS', 'lebar_dada' => '40-42', 'panjang_badan' => '50-52', 'panjang_lengan' => '15-17', 'usia' => '6-8', 'berat' => '20-25', 'status' => 'aktif'],
                    ['ukuran' => 'S', 'lebar_dada' => '44-46', 'panjang_badan' => '54-56', 'panjang_lengan' => '18-20', 'usia' => '9-11', 'berat' => '26-35', 'status' => 'aktif'],
                    ['ukuran' => 'M', 'lebar_dada' => '48-50', 'panjang_badan' => '58-60', 'panjang_lengan' => '21-23', 'usia' => '12-14', 'berat' => '36-45', 'status' => 'aktif'],
                    ['ukuran' => 'L', 'lebar_dada' => '52-54', 'panjang_badan' => '62-64', 'panjang_lengan' => '24-26', 'usia' => '15-17', 'berat' => '46-55', 'status' => 'aktif'],
                    ['ukuran' => 'XL', 'lebar_dada' => '56-58', 'panjang_badan' => '66-68', 'panjang_lengan' => '27-29', 'usia' => '18+', 'berat' => '56-70', 'status' => 'aktif']
                ] as $ukuran)
                    <tr>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee; font-weight: 600; font-size: 16px; background: #f9f9f9;">{{ $ukuran['ukuran'] }}</td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">{{ $ukuran['lebar_dada'] }}</td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">{{ $ukuran['panjang_badan'] }}</td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">{{ $ukuran['panjang_lengan'] }}</td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">{{ $ukuran['usia'] }}</td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">{{ $ukuran['berat'] }}</td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $ukuran['status'] == 'aktif' ? 'status-active' : 'status-pending' }}">
                                {{ ucfirst($ukuran['status']) }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #f44336; color: white;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #666;">
                            <div style="text-align: center;">
                                <i class="fas fa-cogs" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                <h4 style="color: #666; margin-bottom: 10px;">Belum Ada Data Ukuran</h4>
                                <p>Belum ada master data ukuran jersey.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Form Tambah/Edit Ukuran -->
<div class="dashboard-card" style="margin-top: 30px;">
    <h3 class="card-title">Tambah/Edit Ukuran Jersey</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kode Ukuran</label>
                <input type="text" name="ukuran" class="form-input" placeholder="XS, S, M, L, XL" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Lebar Dada (cm)</label>
                <input type="text" name="lebar_dada" class="form-input" placeholder="40-42" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Panjang Badan (cm)</label>
                <input type="text" name="panjang_badan" class="form-input" placeholder="50-52" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Panjang Lengan (cm)</label>
                <input type="text" name="panjang_lengan" class="form-input" placeholder="15-17" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Rentang Usia</label>
                <input type="text" name="usia" class="form-input" placeholder="6-8" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Rentang Berat (kg)</label>
                <input type="text" name="berat" class="form-input" placeholder="20-25" required>
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Catatan</label>
            <textarea name="catatan" class="form-input" rows="2" placeholder="Catatan tambahan untuk ukuran ini"></textarea>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="button" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Ukuran
            </button>
        </div>
    </form>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<style>
    .form-input {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #d32f2f;
    }
    
    textarea.form-input {
        resize: vertical;
        min-height: 60px;
    }
</style>
@endsection