@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">SISWA BELUM BAYAR IURAN</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

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
            <a href="{{ route('admin.iuran-rutin', ['tahun' => $tahun]) }}" class="btn btn-primary">
                <i class="fas fa-money-check-alt"></i> Input Pembayaran
            </a>
        </div>
    </form>
</div>

<!-- Alert Info -->
<div style="background:#fff3cd; border-left:4px solid #ff9800; padding:15px 20px; border-radius:8px; margin-bottom:20px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <i class="fas fa-exclamation-triangle" style="font-size:24px; color:#ff9800;"></i>
        <div>
            <strong style="color:#856404;">Total {{ $siswas->count() }} siswa belum bayar iuran</strong>
            <p style="margin:5px 0 0 0; color:#856404; font-size:13px;">
                Periode: {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}
            </p>
        </div>
    </div>
</div>

<!-- Tabel Siswa Belum Bayar -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    @if($siswas->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-check-circle" style="font-size:40px; color:#4caf50; display:block; margin-bottom:10px;"></i>
            <p style="font-size:16px; font-weight:600; color:#4caf50;">Semua siswa sudah bayar!</p>
            <p style="font-size:13px; color:#666;">Tidak ada siswa yang belum bayar iuran bulan ini.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">No</th>
                        <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:left;">Kelas</th>
                        <th style="padding:12px 16px; text-align:left;">Nama Orang Tua</th>
                        <th style="padding:12px 16px; text-align:left;">Telepon</th>
                        <th style="padding:12px 16px; text-align:left;">Email</th>
                        <th style="padding:12px 16px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $i => $siswa)
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px;">{{ $i + 1 }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ $siswa->nama }}</td>
                            <td style="padding:10px 16px;">{{ ucfirst($siswa->kelas) }}</td>
                            <td style="padding:10px 16px;">{{ $siswa->nama_ortu }}</td>
                            <td style="padding:10px 16px;">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->telepon) }}" target="_blank" style="color:#25d366; text-decoration:none;">
                                    <i class="fab fa-whatsapp"></i> {{ $siswa->telepon }}
                                </a>
                            </td>
                            <td style="padding:10px 16px; color:#666; font-size:12px;">{{ $siswa->email ?? '-' }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                <a href="{{ route('admin.iuran-rutin', ['tahun' => $tahun, 'search' => $siswa->nama]) }}" class="btn btn-primary" style="padding:6px 12px; font-size:12px; text-decoration:none;">
                                    <i class="fas fa-money-bill-wave"></i> Bayar
                                </a>
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
