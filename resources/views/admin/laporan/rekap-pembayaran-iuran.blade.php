@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">REKAP PEMBAYARAN IURAN</h1>

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:150px 150px 200px; gap:15px; align-items:end;">
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
    </form>
</div>

<!-- Summary Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px; margin-bottom:30px;">
    <div style="background:linear-gradient(135deg, #2196f3, #1976d2); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Total Siswa</div>
        <div style="font-size:32px; font-weight:700;">{{ $totalSiswa }}</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #4caf50, #45a049); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Sudah Bayar</div>
        <div style="font-size:32px; font-weight:700;">{{ $sudahBayar }}</div>
        <div style="font-size:11px; opacity:0.8; margin-top:5px;">{{ $totalSiswa > 0 ? number_format($sudahBayar/$totalSiswa*100, 1) : 0 }}%</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #f44336, #d32f2f); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Belum Bayar</div>
        <div style="font-size:32px; font-weight:700;">{{ $belumBayar }}</div>
        <div style="font-size:11px; opacity:0.8; margin-top:5px;">{{ $totalSiswa > 0 ? number_format($belumBayar/$totalSiswa*100, 1) : 0 }}%</div>
    </div>
    
    <div style="background:linear-gradient(135deg, #ff9800, #f57c00); color:white; padding:20px; border-radius:10px;">
        <div style="font-size:12px; opacity:0.9; margin-bottom:5px;">Total Pendapatan</div>
        <div style="font-size:24px; font-weight:700;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Tabs -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="display:flex; border-bottom:2px solid #e0e0e0;">
        <button onclick="showTab('semua')" id="tab-semua" class="tab-btn active" style="flex:1; padding:15px; border:none; background:white; cursor:pointer; font-weight:600; border-bottom:3px solid #d32f2f;">
            Semua ({{ $totalSiswa }})
        </button>
        <button onclick="showTab('sudah')" id="tab-sudah" class="tab-btn" style="flex:1; padding:15px; border:none; background:white; cursor:pointer; font-weight:600; border-bottom:3px solid transparent;">
            Sudah Bayar ({{ $sudahBayar }})
        </button>
        <button onclick="showTab('belum')" id="tab-belum" class="tab-btn" style="flex:1; padding:15px; border:none; background:white; cursor:pointer; font-weight:600; border-bottom:3px solid transparent;">
            Belum Bayar ({{ $belumBayar }})
        </button>
    </div>

    <!-- Tab Content -->
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                    <th style="padding:12px 16px; text-align:left;">No</th>
                    <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                    <th style="padding:12px 16px; text-align:left;">Kelas</th>
                    <th style="padding:12px 16px; text-align:left;">Nama Orang Tua</th>
                    <th style="padding:12px 16px; text-align:left;">Telepon</th>
                    <th style="padding:12px 16px; text-align:center;">Status</th>
                    <th style="padding:12px 16px; text-align:right;">Jumlah</th>
                    <th style="padding:12px 16px; text-align:left;">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach($siswas as $i => $siswa)
                    @php
                        $pembayaran = $siswa->pembayarans->first();
                        $sudahBayar = $pembayaran !== null;
                    @endphp
                    <tr class="row-item" data-status="{{ $sudahBayar ? 'sudah' : 'belum' }}" style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                        <td style="padding:10px 16px;">{{ $i + 1 }}</td>
                        <td style="padding:10px 16px; font-weight:600;">{{ $siswa->nama }}</td>
                        <td style="padding:10px 16px;">{{ ucfirst($siswa->kelas) }}</td>
                        <td style="padding:10px 16px;">{{ $siswa->nama_ortu }}</td>
                        <td style="padding:10px 16px;">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->telepon) }}" target="_blank" style="color:#25d366; text-decoration:none;">
                                <i class="fab fa-whatsapp"></i> {{ $siswa->telepon }}
                            </a>
                        </td>
                        <td style="padding:10px 16px; text-align:center;">
                            @if($sudahBayar)
                                <span style="background:#4caf50; color:white; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                    <i class="fas fa-check-circle"></i> LUNAS
                                </span>
                            @else
                                <span style="background:#f44336; color:white; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                    <i class="fas fa-times-circle"></i> BELUM
                                </span>
                            @endif
                        </td>
                        <td style="padding:10px 16px; text-align:right; font-weight:700; color:{{ $sudahBayar ? '#4caf50' : '#999' }};">
                            {{ $sudahBayar ? 'Rp ' . number_format($pembayaran->jumlah, 0, ',', '.') : '-' }}
                        </td>
                        <td style="padding:10px 16px; color:#666; font-size:12px;">
                            {{ $sudahBayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
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
    .tab-btn.active {
        border-bottom-color:#d32f2f !important;
        color:#d32f2f;
    }
</style>

<script>
function showTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.style.borderBottomColor = 'transparent';
        btn.style.color = '#666';
    });
    
    const activeBtn = document.getElementById('tab-' + tab);
    activeBtn.classList.add('active');
    activeBtn.style.borderBottomColor = '#d32f2f';
    activeBtn.style.color = '#d32f2f';
    
    // Filter rows
    const rows = document.querySelectorAll('.row-item');
    rows.forEach(row => {
        if (tab === 'semua') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.status === tab ? '' : 'none';
        }
    });
}
</script>
@endsection
