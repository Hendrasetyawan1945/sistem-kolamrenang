@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">LAPORAN PENDAPATAN</h1>

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:150px 150px auto; gap:15px; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Bulan</label>
            <select name="bulan" class="form-select" onchange="this.form.submit()">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tahun</label>
            <select name="tahun" class="form-select" onchange="this.form.submit()">
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:20px;">
    <div style="background:linear-gradient(135deg, #4caf50, #45a049); color:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(76,175,80,0.3);">
        <div style="font-size:14px; opacity:0.9; margin-bottom:5px;">Total Pembayaran</div>
        <div style="font-size:28px; font-weight:700;">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</div>
        <div style="font-size:12px; opacity:0.8; margin-top:5px;">{{ $pembayarans->count() }} transaksi</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #2196f3, #1976d2); color:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(33,150,243,0.3);">
        <div style="font-size:14px; opacity:0.9; margin-bottom:5px;">Total Cicilan</div>
        <div style="font-size:28px; font-weight:700;">Rp {{ number_format($totalCicilan, 0, ',', '.') }}</div>
        <div style="font-size:12px; opacity:0.8; margin-top:5px;">{{ $cicilans->count() }} cicilan</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #ff9800, #f57c00); color:white; padding:25px; border-radius:12px; box-shadow:0 4px 15px rgba(255,152,0,0.3);">
        <div style="font-size:14px; opacity:0.9; margin-bottom:5px;">Total Pendapatan</div>
        <div style="font-size:28px; font-weight:700;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        <div style="font-size:12px; opacity:0.8; margin-top:5px;">{{ $pembayarans->count() + $cicilans->count() }} total transaksi</div>
    </div>
</div>

<!-- Tabel Pembayaran -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px;">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-money-check-alt"></i> Riwayat Pembayaran
        </h3>
    </div>

    @if($pembayarans->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Tidak ada data pembayaran
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">Tanggal</th>
                        <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:left;">Jenis Pembayaran</th>
                        <th style="padding:12px 16px; text-align:right;">Jumlah</th>
                        <th style="padding:12px 16px; text-align:left;">Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayarans as $bayar)
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px;">{{ $bayar->tanggal_bayar->format('d/m/Y') }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ $bayar->siswa->nama }}</td>
                            <td style="padding:10px 16px;">
                                <span style="background:#e3f2fd; color:#1976d2; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                    {{ ucwords(str_replace('_', ' ', $bayar->jenis_pembayaran)) }}
                                </span>
                            </td>
                            <td style="padding:10px 16px; text-align:right; font-weight:700; color:#4caf50;">
                                Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                            </td>
                            <td style="padding:10px 16px; color:#666; font-size:12px;">{{ $bayar->metode_pembayaran ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Tabel Cicilan -->
@if($cicilans->isNotEmpty())
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-credit-card"></i> Riwayat Cicilan Angsuran
        </h3>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                    <th style="padding:12px 16px; text-align:left;">Tanggal</th>
                    <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                    <th style="padding:12px 16px; text-align:left;">Jenis Tagihan</th>
                    <th style="padding:12px 16px; text-align:center;">Cicilan Ke-</th>
                    <th style="padding:12px 16px; text-align:right;">Jumlah</th>
                    <th style="padding:12px 16px; text-align:left;">Metode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cicilans as $cicilan)
                    <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                        <td style="padding:10px 16px;">{{ $cicilan->tanggal_bayar->format('d/m/Y') }}</td>
                        <td style="padding:10px 16px; font-weight:600;">{{ $cicilan->angsuran->siswa->nama }}</td>
                        <td style="padding:10px 16px;">
                            <span style="background:#fff3e0; color:#f57c00; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                {{ $cicilan->angsuran->jenis_tagihan }}
                            </span>
                        </td>
                        <td style="padding:10px 16px; text-align:center; font-weight:600;">{{ $cicilan->cicilan_ke }}</td>
                        <td style="padding:10px 16px; text-align:right; font-weight:700; color:#4caf50;">
                            Rp {{ number_format($cicilan->jumlah, 0, ',', '.') }}
                        </td>
                        <td style="padding:10px 16px; color:#666; font-size:12px;">{{ $cicilan->metode_pembayaran ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<style>
    .form-select {
        padding:10px 12px;
        border:2px solid #e0e0e0;
        border-radius:6px;
        font-size:14px;
        width:100%;
    }
    .form-select:focus {
        outline:none;
        border-color:#d32f2f;
    }
</style>
@endsection
