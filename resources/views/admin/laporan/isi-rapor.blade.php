@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-file-alt"></i>
    </div>
    <h1 class="club-title">Isi Rapor Siswa</h1>
</div>

<!-- Filter dan Pencarian -->
<div class="dashboard-card" style="margin-bottom: 30px;">
    <h3 class="card-title">Filter Rapor Siswa</h3>
    
    <form action="{{ route('admin.isi-rapor') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Periode</label>
            <select name="periode" class="form-select">
                <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="semester" {{ request('periode') == 'semester' ? 'selected' : '' }}>Semester</option>
                <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Bulan</label>
            <select name="bulan" class="form-select">
                <option value="">Semua Bulan</option>
                <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Tahun</label>
            <select name="tahun" class="form-select">
                <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kelas</label>
            <select name="kelas" class="form-select">
                <option value="">Semua Kelas</option>
                <option value="KU-12" {{ request('kelas') == 'KU-12' ? 'selected' : '' }}>KU-12</option>
                <option value="KU-10" {{ request('kelas') == 'KU-10' ? 'selected' : '' }}>KU-10</option>
                <option value="ENGLISH CLASS V" {{ request('kelas') == 'ENGLISH CLASS V' ? 'selected' : '' }}>ENGLISH CLASS V</option>
            </select>
        </div>
        
        <div style="display: flex; align-items: end;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Tombol Aksi -->
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Rapor Baru
    </button>
    <button class="btn btn-secondary">
        <i class="fas fa-copy"></i> Salin dari Template
    </button>
    <button class="btn" style="background: #4caf50; color: white;">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
    <button class="btn" style="background: #f44336; color: white;">
        <i class="fas fa-file-pdf"></i> Export PDF
    </button>
</div>

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Daftar Rapor Siswa - April 2026</h4>
            <div>
                <input type="text" placeholder="Cari siswa..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama Siswa</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Kelas</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Periode</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Progress Teknik</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Kehadiran</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status Rapor</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([] as $index => $rapor)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $rapor['nama'] ?? '' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $rapor['kelas'] ?? '' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $rapor['periode'] ?? '' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 60px; height: 8px; background: #e0e0e0; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $rapor['progress'] ?? 0 }}%; height: 100%; background: #4caf50;"></div>
                                </div>
                                <span style="font-size: 12px;">{{ $rapor['progress'] ?? 0 }}%</span>
                            </div>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $rapor['kehadiran'] ?? '0/0' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $rapor['status'] == 'selesai' ? 'status-active' : ($rapor['status'] == 'draft' ? 'status-warning' : 'status-pending') }}">
                                {{ ucfirst($rapor['status'] ?? 'belum_dibuat') }}
                            </span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #4caf50; color: white;">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #666;">
                            <div style="text-align: center;">
                                <i class="fas fa-file-alt" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                <h4 style="color: #666; margin-bottom: 10px;">Belum Ada Rapor</h4>
                                <p>Belum ada rapor siswa yang dibuat untuk periode ini.</p>
                                <button class="btn btn-primary" style="margin-top: 15px;">
                                    <i class="fas fa-plus"></i> Buat Rapor Pertama
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Form Input Rapor -->
<div class="dashboard-card" style="margin-top: 30px;">
    <h3 class="card-title">Input Rapor Cepat</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Pilih Siswa</label>
                <select name="siswa_id" class="form-select" required>
                    <option value="">Pilih siswa</option>
                    <option value="1">Kayden Liam Teman Kinsey</option>
                    <option value="2">Michelle G. Declan</option>
                    <option value="3">ANGELO Js</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Periode</label>
                <select name="periode" class="form-select" required>
                    <option value="april_2026">April 2026</option>
                    <option value="maret_2026">Maret 2026</option>
                    <option value="februari_2026">Februari 2026</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Template</label>
                <select name="template" class="form-select">
                    <option value="">Kosong</option>
                    <option value="pemula">Template Pemula</option>
                    <option value="menengah">Template Menengah</option>
                    <option value="lanjut">Template Lanjut</option>
                </select>
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Catatan Progress</label>
            <textarea name="catatan" class="form-input" rows="3" placeholder="Masukkan catatan progress siswa..."></textarea>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="button" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Rapor
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
    .form-select, .form-input {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: #d32f2f;
    }
    
    .status-warning {
        background: #ff9800;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    
    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }
</style>
@endsection