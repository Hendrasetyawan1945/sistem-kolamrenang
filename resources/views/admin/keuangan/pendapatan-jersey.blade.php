@extends('layouts.admin')
@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-tshirt"></i></div>
    <h1 class="club-title">Pendapatan Jersey</h1>
</div>

@php
$namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
@endphp

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;margin-bottom:25px;">
    <div style="background:linear-gradient(135deg,#9c27b0,#6a1b9a);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">TOTAL ORDER</div>
        <div style="font-size:26px;font-weight:700;">{{ $stats['total_order'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#388e3c);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">SELESAI</div>
        <div style="font-size:26px;font-weight:700;">{{ $stats['total_lunas'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">PENDING/PROSES</div>
        <div style="font-size:26px;font-weight:700;">{{ $stats['total_pending'] }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#2196f3,#1565c0);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">PENDAPATAN</div>
        <div style="font-size:18px;font-weight:700;">Rp {{ number_format($stats['total_pendapatan'],0,',','.') }}</div>
    </div>
</div>

<!-- Filter -->
<div style="background:white;padding:16px 20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Bulan</label>
            <select name="bulan" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                @foreach($namaBulan as $v=>$l)
                <option value="{{ $v }}" {{ $bulan==$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Tahun</label>
            <select name="tahun" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                @foreach([2026,2025,2024] as $y)
                <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Status</label>
            <select name="status" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                <option value="">Semua</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="proses" {{ request('status')=='proses'?'selected':'' }}>Proses</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
            </select>
        </div>
        <button type="submit" style="background:#d32f2f;color:white;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Tabel -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">
            Order Jersey — {{ $namaBulan[$bulan] ?? '' }} {{ $tahun }} ({{ $orders->count() }} data)
        </h3>
        <strong style="color:#9c27b0;">
            Terkumpul: Rp {{ number_format($orders->where('status','selesai')->sum('harga'),0,',','.') }}
        </strong>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Tgl Pesan</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Siswa</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Ukuran</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Punggung</th>
                    <th style="padding:11px 14px;text-align:right;font-size:12px;color:#666;border-bottom:1px solid #eee;">Harga</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $i => $o)
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $o->tanggal_pesan->format('d M Y') }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="font-weight:600;font-size:13px;">{{ $o->siswa->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#999;">{{ $o->siswa->kelas ?? '' }}</div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $o->jerseySize->ukuran ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">
                        {{ $o->nama_punggung ?? '-' }}
                        @if($o->nomor_punggung) <span style="color:#999;">#{{ $o->nomor_punggung }}</span> @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;font-weight:600;text-align:right;color:#9c27b0;">
                        Rp {{ number_format($o->harga,0,',','.') }}
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @php $colors = ['pending'=>['#fff3e0','#e65100'],'proses'=>['#e3f2fd','#1565c0'],'selesai'=>['#e8f5e9','#2e7d32'],'dibatalkan'=>['#ffebee','#c62828']]; $c = $colors[$o->status] ?? ['#f5f5f5','#666']; @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;">
                            {{ ucfirst($o->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:50px;text-align:center;color:#999;">
                        <i class="fas fa-tshirt" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Tidak ada order jersey di periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.pemesanan') }}" class="btn btn-secondary">
        <i class="fas fa-shopping-cart"></i> Kelola Pemesanan Jersey
    </a>
</div>
@endsection
