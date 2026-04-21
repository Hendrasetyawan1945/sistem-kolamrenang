@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">REKAP JUMLAH SISWA</h1>

<!-- Filter Tahun -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:flex; gap:15px; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tahun</label>
            <select name="tahun" class="form-select" style="width:150px;" onchange="this.form.submit()">
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:15px; margin-bottom:25px;">
    <a href="{{ route('admin.siswa-aktif') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg, #4caf50, #45a049); color:white; padding:20px; border-radius:10px; text-align:center; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="font-size:36px; font-weight:700;">{{ $totalAktif }}</div>
            <div style="font-size:13px; opacity:0.9; margin-top:5px;">Siswa Aktif</div>
        </div>
    </a>
    <a href="{{ route('admin.calon-siswa') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg, #2196f3, #1976d2); color:white; padding:20px; border-radius:10px; text-align:center; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="font-size:36px; font-weight:700;">{{ $totalCalon }}</div>
            <div style="font-size:13px; opacity:0.9; margin-top:5px;">Calon Siswa</div>
        </div>
    </a>
    <a href="{{ route('admin.siswa-cuti') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg, #ff9800, #f57c00); color:white; padding:20px; border-radius:10px; text-align:center; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="font-size:36px; font-weight:700;">{{ $totalCuti }}</div>
            <div style="font-size:13px; opacity:0.9; margin-top:5px;">Siswa Cuti</div>
        </div>
    </a>
    <a href="{{ route('admin.siswa-nonaktif') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg, #f44336, #d32f2f); color:white; padding:20px; border-radius:10px; text-align:center; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="font-size:36px; font-weight:700;">{{ $totalNonaktif }}</div>
            <div style="font-size:13px; opacity:0.9; margin-top:5px;">Siswa Nonaktif</div>
        </div>
    </a>
    <div style="background:linear-gradient(135deg, #607d8b, #455a64); color:white; padding:20px; border-radius:10px; text-align:center;">
        <div style="font-size:36px; font-weight:700;">{{ $totalSemua }}</div>
        <div style="font-size:13px; opacity:0.9; margin-top:5px;">Total Semua</div>
    </div>
</div>

<!-- Breakdown Per Kelas -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px;">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-chalkboard"></i> Jumlah Siswa Aktif Per Kelas
        </h3>
    </div>
    
    @if($perKelas->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada data
        </div>
    @else
        <div style="padding:20px;">
            @foreach($perKelas as $kelas)
                @php
                    $persen = $totalAktif > 0 ? ($kelas->total / $totalAktif * 100) : 0;
                    $colors = ['#d32f2f','#2196f3','#4caf50','#ff9800','#9c27b0','#607d8b','#e91e63','#00bcd4'];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div style="margin-bottom:15px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="background:{{ $color }}; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:700;">
                                {{ ucfirst($kelas->kelas) }}
                            </span>
                        </div>
                        <div style="font-weight:700; color:#333;">
                            {{ $kelas->total }} siswa
                            <span style="color:#999; font-weight:400; font-size:12px;">({{ number_format($persen, 1) }}%)</span>
                        </div>
                    </div>
                    <div style="background:#e0e0e0; height:10px; border-radius:10px; overflow:hidden;">
                        <div style="background:{{ $color }}; height:100%; width:{{ $persen }}%; transition:width 0.5s;"></div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Tabel Detail -->
        <div style="overflow-x:auto; border-top:1px solid #e0e0e0;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">No</th>
                        <th style="padding:12px 16px; text-align:left;">Kelas</th>
                        <th style="padding:12px 16px; text-align:center;">Jumlah Siswa</th>
                        <th style="padding:12px 16px; text-align:center;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perKelas as $kelas)
                        @php $persen = $totalAktif > 0 ? ($kelas->total / $totalAktif * 100) : 0; @endphp
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px;">{{ $loop->iteration }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ ucfirst($kelas->kelas) }}</td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; font-size:18px; color:#d32f2f;">{{ $kelas->total }}</td>
                            <td style="padding:10px 16px; text-align:center;">{{ number_format($persen, 1) }}%</td>
                        </tr>
                    @endforeach
                    <tr style="background:#f8f9fa; border-top:2px solid #e0e0e0; font-weight:700;">
                        <td colspan="2" style="padding:12px 16px; text-align:right;">TOTAL:</td>
                        <td style="padding:12px 16px; text-align:center; font-size:18px; color:#d32f2f;">{{ $totalAktif }}</td>
                        <td style="padding:12px 16px; text-align:center;">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Grafik Siswa Baru Per Bulan -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px;">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-chart-bar"></i> Siswa Baru Per Bulan - {{ $tahun }}
        </h3>
    </div>
    <div style="padding:20px;">
        @php
            $bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
            $maxBaru = max(array_values($siswaBaru)) ?: 1;
        @endphp
        <div style="display:flex; align-items:flex-end; gap:8px; height:150px; padding-bottom:25px; position:relative;">
            @foreach($bulanNames as $i => $nama)
                @php
                    $val = $siswaBaru[$i + 1];
                    $tinggi = $maxBaru > 0 ? ($val / $maxBaru * 120) : 0;
                @endphp
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="font-size:11px; font-weight:700; color:#4caf50;">{{ $val > 0 ? $val : '' }}</div>
                    <div style="width:100%; background:{{ $val > 0 ? '#4caf50' : '#e0e0e0' }}; height:{{ max($tinggi, 4) }}px; border-radius:4px 4px 0 0; transition:height 0.5s;"></div>
                    <div style="font-size:10px; color:#666; margin-top:4px;">{{ $nama }}</div>
                </div>
            @endforeach
        </div>
    </div>
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
    }
    .form-select:focus {
        outline:none;
        border-color:#d32f2f;
    }
</style>
@endsection
