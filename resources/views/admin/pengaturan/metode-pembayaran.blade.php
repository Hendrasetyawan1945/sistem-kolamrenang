@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-credit-card"></i>
    </div>
    <h1 class="club-title">Metode Pembayaran</h1>
</div>

<!-- Tombol Aksi -->
<div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Metode Baru
    </button>
</div>

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Daftar Metode Pembayaran</h4>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama Metode</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Jenis</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nomor Rekening/ID</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Atas Nama</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse([
                    ['nama' => 'Transfer Bank BCA', 'jenis' => 'Bank Transfer', 'nomor' => '1234567890', 'atas_nama' => 'Youth Swimming Club', 'status' => 'aktif'],
                    ['nama' => 'Transfer Bank Mandiri', 'jenis' => 'Bank Transfer', 'nomor' => '0987654321', 'atas_nama' => 'Youth Swimming Club', 'status' => 'aktif'],
                    ['nama' => 'GoPay', 'jenis' => 'E-Wallet', 'nomor' => '081234567890', 'atas_nama' => 'Admin Club', 'status' => 'aktif'],
                    ['nama' => 'OVO', 'jenis' => 'E-Wallet', 'nomor' => '081234567890', 'atas_nama' => 'Admin Club', 'status' => 'nonaktif'],
                    ['nama' => 'Cash/Tunai', 'jenis' => 'Cash', 'nomor' => '-', 'atas_nama' => '-', 'status' => 'aktif']
                ] as $index => $metode)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: 600;">{{ $metode['nama'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-secondary">{{ $metode['jenis'] }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $metode['nomor'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $metode['atas_nama'] }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $metode['status'] == 'aktif' ? 'status-active' : 'status-pending' }}">
                                {{ ucfirst($metode['status']) }}
                            </span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($metode['status'] == 'aktif')
                                    <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #ff9800; color: white;">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                @else
                                    <button class="btn" style="padding: 5px 8px; font-size: 11px; background: #4caf50; color: white;">
                                        <i class="fas fa-play"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #666;">
                            <div style="text-align: center;">
                                <i class="fas fa-credit-card" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                <h4 style="color: #666; margin-bottom: 10px;">Belum Ada Metode Pembayaran</h4>
                                <p>Belum ada metode pembayaran yang dikonfigurasi.</p>
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