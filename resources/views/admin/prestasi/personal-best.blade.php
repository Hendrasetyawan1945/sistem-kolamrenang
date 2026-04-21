@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">PERSONAL BEST</h1>

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:1fr 1fr auto; gap:15px; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Siswa</label>
            <select name="siswa_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Siswa</option>
                @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>{{ $siswa->nama }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nomor Lomba</label>
            <select name="nomor_lomba" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Nomor</option>
                @foreach($nomorLombas as $nomor)
                    <option value="{{ $nomor }}" {{ request('nomor_lomba') == $nomor ? 'selected' : '' }}>{{ $nomor }}</option>
                @endforeach
            </select>
        </div>
        
        <a href="{{ route('admin.catatan-waktu') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </form>
</div>

<!-- Tabel Personal Best -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-trophy"></i> Waktu Terbaik Per Siswa ({{ $personalBests->count() }})
        </h3>
    </div>

    @if($personalBests->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada data personal best
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:left;">Nomor Lomba</th>
                        <th style="padding:12px 16px; text-align:center;">Kolam</th>
                        <th style="padding:12px 16px; text-align:center;">Waktu Terbaik</th>
                        <th style="padding:12px 16px; text-align:left;">Tanggal</th>
                        <th style="padding:12px 16px; text-align:left;">Event</th>
                        <th style="padding:12px 16px; text-align:left;">Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personalBests as $pb)
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px; font-weight:600;">{{ $pb->siswa->nama }}</td>
                            <td style="padding:10px 16px;">{{ $pb->nomor_lomba }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                <span style="background:#e3f2fd; color:#1976d2; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                    {{ $pb->jenis_kolam }}
                                </span>
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
                                    <i class="fas fa-trophy" style="color:#ffd700; font-size:18px;"></i>
                                    <span style="font-weight:700; color:#d32f2f; font-size:18px;">{{ $pb->waktu }}</span>
                                </div>
                            </td>
                            <td style="padding:10px 16px;">{{ $pb->tanggal->format('d/m/Y') }}</td>
                            <td style="padding:10px 16px;">
                                <span style="background:{{ $pb->jenis_event == 'kejuaraan' ? '#ffd700' : ($pb->jenis_event == 'time_trial' ? '#ff9800' : '#e0e0e0') }}; color:{{ $pb->jenis_event == 'latihan' ? '#666' : 'white' }}; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                    {{ ucfirst(str_replace('_', ' ', $pb->jenis_event)) }}
                                </span>
                            </td>
                            <td style="padding:10px 16px; color:#666; font-size:12px;">{{ $pb->lokasi ?? '-' }}</td>
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
