@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">ABSENSI SISWA</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:200px 200px auto; gap:15px; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tanggal</label>
            <input type="date" name="tanggal" class="form-input" value="{{ $tanggal }}" onchange="this.form.submit()">
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
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.absensi.rekap') }}" class="btn btn-primary">
                <i class="fas fa-chart-bar"></i> Lihat Rekap
            </a>
        </div>
    </form>
</div>

<!-- Tabel Absensi -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-clipboard-list"></i> Daftar Absensi - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
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
                        <th style="padding:12px 16px; text-align:center;">Status</th>
                        <th style="padding:12px 16px; text-align:center;">Jam Masuk</th>
                        <th style="padding:12px 16px; text-align:center;">Jam Keluar</th>
                        <th style="padding:12px 16px; text-align:left;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $i => $siswa)
                        @php
                            $absensi = $siswa->absensis->first();
                        @endphp
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px;">{{ $i + 1 }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ $siswa->nama }}</td>
                            <td style="padding:10px 16px;">{{ ucfirst($siswa->kelas) }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                <form method="POST" action="{{ route('admin.absensi.store') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                    <select name="status" onchange="this.form.submit()" style="padding:6px 10px; border:2px solid #e0e0e0; border-radius:6px; font-size:12px; font-weight:600;">
                                        <option value="hadir" {{ $absensi && $absensi->status == 'hadir' ? 'selected' : '' }} style="color:#4caf50;">✓ Hadir</option>
                                        <option value="izin" {{ $absensi && $absensi->status == 'izin' ? 'selected' : '' }} style="color:#2196f3;">Izin</option>
                                        <option value="sakit" {{ $absensi && $absensi->status == 'sakit' ? 'selected' : '' }} style="color:#ff9800;">Sakit</option>
                                        <option value="alpha" {{ $absensi && $absensi->status == 'alpha' ? 'selected' : '' }} style="color:#f44336;">✗ Alpha</option>
                                    </select>
                                </form>
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                {{ $absensi && $absensi->jam_masuk ? $absensi->jam_masuk : '-' }}
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                {{ $absensi && $absensi->jam_keluar ? $absensi->jam_keluar : '-' }}
                            </td>
                            <td style="padding:10px 16px; color:#666; font-size:12px;">
                                {{ $absensi && $absensi->keterangan ? $absensi->keterangan : '-' }}
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
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<style>
    .form-input, .form-select {
        padding:10px 12px;
        border:2px solid #e0e0e0;
        border-radius:6px;
        font-size:14px;
        width:100%;
    }
    .form-input:focus, .form-select:focus {
        outline:none;
        border-color:#d32f2f;
    }
</style>
@endsection
