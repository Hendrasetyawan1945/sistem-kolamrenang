@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">REKAP KEHADIRAN</h1>

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:150px 150px 200px auto; gap:15px; align-items:end;">
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
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Kelas</label>
            <select name="kelas" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <a href="{{ route('admin.absensi') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Absensi
            </a>
        </div>
    </form>
</div>

<!-- Tabel Rekap -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-chart-bar"></i> Rekap Kehadiran - {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}
        </h3>
    </div>

    @if($siswas->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Tidak ada data siswa
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">No</th>
                        <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:left;">Kelas</th>
                        <th style="padding:12px 16px; text-align:center; background:#e8f5e9; color:#4caf50;">Hadir</th>
                        <th style="padding:12px 16px; text-align:center; background:#e3f2fd; color:#2196f3;">Izin</th>
                        <th style="padding:12px 16px; text-align:center; background:#fff3e0; color:#ff9800;">Sakit</th>
                        <th style="padding:12px 16px; text-align:center; background:#ffebee; color:#f44336;">Alpha</th>
                        <th style="padding:12px 16px; text-align:center;">Total</th>
                        <th style="padding:12px 16px; text-align:center;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $i => $siswa)
                        @php
                            $persentase = $siswa->total_absensi > 0 ? ($siswa->total_hadir / $siswa->total_absensi * 100) : 0;
                        @endphp
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px;">{{ $i + 1 }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ $siswa->nama }}</td>
                            <td style="padding:10px 16px;">{{ ucfirst($siswa->kelas) }}</td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; color:#4caf50;">{{ $siswa->total_hadir }}</td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; color:#2196f3;">{{ $siswa->total_izin }}</td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; color:#ff9800;">{{ $siswa->total_sakit }}</td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; color:#f44336;">{{ $siswa->total_alpha }}</td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700;">{{ $siswa->total_absensi }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                <div style="display:flex; align-items:center; gap:8px; justify-content:center;">
                                    <div style="flex:1; background:#e0e0e0; height:8px; border-radius:10px; overflow:hidden; max-width:100px;">
                                        <div style="background:{{ $persentase >= 80 ? '#4caf50' : ($persentase >= 60 ? '#ff9800' : '#f44336') }}; height:100%; width:{{ $persentase }}%;"></div>
                                    </div>
                                    <span style="font-weight:700; min-width:45px;">{{ number_format($persentase, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

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
