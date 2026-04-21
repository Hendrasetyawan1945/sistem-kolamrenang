@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-medal"></i>
    </div>
    <h1 class="club-title">Nomor Non-Standar</h1>
</div>

<!-- Tombol Aksi -->
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Nomor Baru
    </button>
    <button class="btn" style="background: #4caf50; color: white;">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</div>

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Daftar Nomor Non-Standar</h4>
            <div>
                <input type="text" placeholder="Cari nomor..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama Nomor</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Jarak</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Gaya</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Kategori</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([
                    ['nama' => '25m Freestyle', 'jarak' => '25m', 'gaya' => 'Freestyle', 'kategori' => 'Pemula', 'status' => 'aktif'],
                    ['nama' => '75m Individual Medley', 'jarak' => '75m', 'gaya' => 'IM', 'kategori' => 'Menengah', 'status' => 'aktif'],
                    ['nama' => '200m Relay Freestyle', 'jarak' => '200m', 'gaya' => 'Freestyle Relay', 'kategori' => 'Tim', 'status' => 'aktif']
                ] as $index => $nomor)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: 600;">{{ $nomor['nama'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $nomor['jarak'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $nomor['gaya'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-secondary">{{ $nomor['kategori'] }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $nomor['status'] == 'aktif' ? 'status-active' : 'status-pending' }}">
                                {{ ucfirst($nomor['status']) }}
                            </span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
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
                        <td colspan="7" style="padding: 40px; text-align: center; color: #666;">
                            <div style="text-align: center;">
                                <i class="fas fa-medal" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                <h4 style="color: #666; margin-bottom: 10px;">Belum Ada Nomor Non-Standar</h4>
                                <p>Belum ada nomor non-standar yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
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