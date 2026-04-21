@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-chart-line"></i></div>
    <h1 class="club-title">Rekap Keuangan</h1>
</div>

<!-- Filter -->
<div style="background:white;padding:18px 20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);margin-bottom:25px;">
    <form method="GET" action="{{ route('admin.rekap-transaksi') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Bulan</label>
            <select name="bulan" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $v=>$l)
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
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Jenis</label>
            <select name="jenis" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                <option value="">Semua</option>
                <option value="pemasukan" {{ $jenis=='pemasukan'?'selected':'' }}>Pemasukan</option>
                <option value="pengeluaran" {{ $jenis=='pengeluaran'?'selected':'' }}>Pengeluaran</option>
            </select>
        </div>
        <button type="submit" style="background:#d32f2f;color:white;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
            <i class="fas fa-filter"></i> Tampilkan
        </button>
        <button type="button" onclick="window.print()" style="background:#607d8b;color:white;border:none;padding:8px 14px;border-radius:6px;font-size:13px;cursor:pointer;">
            <i class="fas fa-print"></i> Print
        </button>
    </form>
</div>

@php
    $namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
@endphp

<!-- Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:25px;">
    <div style="background:linear-gradient(135deg,#4caf50,#388e3c);color:white;padding:22px;border-radius:10px;">
        <div style="font-size:11px;opacity:.8;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Total Pemasukan</div>
        <div style="font-size:22px;font-weight:700;">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
        <div style="font-size:12px;opacity:.8;margin-top:4px;">{{ $namaBulan[$bulan] ?? '' }} {{ $tahun }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#f44336,#c62828);color:white;padding:22px;border-radius:10px;">
        <div style="font-size:11px;opacity:.8;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Total Pengeluaran</div>
        <div style="font-size:22px;font-weight:700;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
        <div style="font-size:12px;opacity:.8;margin-top:4px;">{{ $namaBulan[$bulan] ?? '' }} {{ $tahun }}</div>
    </div>
    <div style="background:linear-gradient(135deg,{{ $saldoBersih >= 0 ? '#2196f3,#1565c0' : '#ff9800,#e65100' }});color:white;padding:22px;border-radius:10px;">
        <div style="font-size:11px;opacity:.8;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Saldo Bersih</div>
        <div style="font-size:22px;font-weight:700;">{{ $saldoBersih >= 0 ? '+' : '' }}Rp {{ number_format($saldoBersih,0,',','.') }}</div>
        <div style="font-size:12px;opacity:.8;margin-top:4px;">{{ $saldoBersih >= 0 ? 'Surplus' : 'Defisit' }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#9c27b0,#6a1b9a);color:white;padding:22px;border-radius:10px;">
        <div style="font-size:11px;opacity:.8;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Total Transaksi</div>
        <div style="font-size:22px;font-weight:700;">{{ $transaksis->count() }}</div>
        <div style="font-size:12px;opacity:.8;margin-top:4px;">{{ $transaksis->where('jenis','pemasukan')->count() }} masuk · {{ $transaksis->where('jenis','pengeluaran')->count() }} keluar</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    <!-- Tabel Transaksi -->
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;">
            <h3 style="margin:0;font-size:15px;font-weight:600;">
                Transaksi {{ $namaBulan[$bulan] ?? '' }} {{ $tahun }}
                @if($jenis) <span style="font-size:12px;color:#666;">— {{ ucfirst($jenis) }}</span> @endif
            </h3>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#fafafa;">
                        <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">TGL</th>
                        <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">JENIS</th>
                        <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">KATEGORI</th>
                        <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">DESKRIPSI</th>
                        <th style="padding:10px 14px;text-align:right;font-size:11px;color:#666;border-bottom:1px solid #eee;">JUMLAH</th>
                        <th style="padding:10px 14px;text-align:right;font-size:11px;color:#666;border-bottom:1px solid #eee;">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                    <tr style="border-left:3px solid {{ $t['jenis']==='pemasukan' ? '#4caf50' : '#f44336' }};"
                        onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                        <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($t['tanggal'])->format('d M') }}
                        </td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;">
                            @if($t['jenis']==='pemasukan')
                                <span style="background:#e8f5e9;color:#2e7d32;padding:2px 7px;border-radius:8px;font-size:10px;font-weight:700;">MASUK</span>
                            @else
                                <span style="background:#ffebee;color:#c62828;padding:2px 7px;border-radius:8px;font-size:10px;font-weight:700;">KELUAR</span>
                            @endif
                        </td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;">{{ $t['kategori'] }}</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#555;">{{ Str::limit($t['deskripsi'],50) }}</td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;font-weight:600;text-align:right;
                            color:{{ $t['jenis']==='pemasukan' ? '#2e7d32' : '#c62828' }};">
                            {{ $t['jenis']==='pemasukan' ? '+' : '-' }}Rp {{ number_format($t['jumlah'],0,',','.') }}
                        </td>
                        <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;text-align:right;
                            color:{{ $t['saldo'] >= 0 ? '#333' : '#f44336' }};">
                            Rp {{ number_format($t['saldo'],0,',','.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:50px;text-align:center;color:#999;">
                            <i class="fas fa-chart-line" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                            Tidak ada transaksi di periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transaksis->count() > 0)
                <tfoot>
                    <tr style="background:#f5f5f5;font-weight:700;">
                        <td colspan="4" style="padding:12px 14px;font-size:13px;">TOTAL</td>
                        <td style="padding:12px 14px;text-align:right;font-size:13px;">
                            <span style="color:#2e7d32;">+Rp {{ number_format($totalPemasukan,0,',','.') }}</span>
                            <br><span style="color:#c62828;">-Rp {{ number_format($totalPengeluaran,0,',','.') }}</span>
                        </td>
                        <td style="padding:12px 14px;text-align:right;font-size:14px;color:{{ $saldoBersih>=0?'#2e7d32':'#c62828' }};">
                            Rp {{ number_format($saldoBersih,0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Ringkasan per Kategori -->
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;">
            <h3 style="margin:0;font-size:15px;font-weight:600;">Ringkasan per Kategori</h3>
        </div>
        <div style="padding:15px;">
            @forelse($perKategori as $kat => $data)
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                    <div>
                        <span style="font-size:13px;font-weight:600;color:#333;">{{ $kat }}</span>
                        <span style="font-size:10px;color:#999;margin-left:5px;">{{ $data['count'] }}x</span>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:{{ $data['jenis']==='pemasukan'?'#2e7d32':'#c62828' }};">
                        Rp {{ number_format($data['total'],0,',','.') }}
                    </span>
                </div>
                @php
                    $maxVal = $perKategori->max('total');
                    $pct = $maxVal > 0 ? ($data['total'] / $maxVal * 100) : 0;
                @endphp
                <div style="background:#f0f0f0;border-radius:4px;height:6px;">
                    <div style="background:{{ $data['jenis']==='pemasukan'?'#4caf50':'#f44336' }};height:6px;border-radius:4px;width:{{ $pct }}%;transition:width .3s;"></div>
                </div>
            </div>
            @empty
            <p style="text-align:center;color:#999;font-size:13px;padding:20px 0;">Tidak ada data</p>
            @endforelse
        </div>

        <!-- Pie summary -->
        @if($totalPemasukan > 0 || $totalPengeluaran > 0)
        <div style="padding:15px;border-top:1px solid #f0f0f0;">
            <div style="display:flex;gap:10px;">
                <div style="flex:1;background:#e8f5e9;padding:12px;border-radius:8px;text-align:center;">
                    <div style="font-size:11px;color:#2e7d32;font-weight:600;">PEMASUKAN</div>
                    <div style="font-size:14px;font-weight:700;color:#2e7d32;margin-top:3px;">
                        Rp {{ number_format($totalPemasukan,0,',','.') }}
                    </div>
                </div>
                <div style="flex:1;background:#ffebee;padding:12px;border-radius:8px;text-align:center;">
                    <div style="font-size:11px;color:#c62828;font-weight:600;">PENGELUARAN</div>
                    <div style="font-size:14px;font-weight:700;color:#c62828;margin-top:3px;">
                        Rp {{ number_format($totalPengeluaran,0,',','.') }}
                    </div>
                </div>
            </div>
            @php $pctMasuk = ($totalPemasukan + $totalPengeluaran) > 0 ? ($totalPemasukan / ($totalPemasukan + $totalPengeluaran) * 100) : 0; @endphp
            <div style="margin-top:12px;background:#f0f0f0;border-radius:6px;height:10px;overflow:hidden;">
                <div style="background:#4caf50;height:10px;width:{{ $pctMasuk }}%;float:left;"></div>
                <div style="background:#f44336;height:10px;width:{{ 100-$pctMasuk }}%;float:left;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:11px;color:#999;margin-top:4px;">
                <span>{{ number_format($pctMasuk,1) }}% Pemasukan</span>
                <span>{{ number_format(100-$pctMasuk,1) }}% Pengeluaran</span>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    .sidebar, .topbar, form, button { display: none !important; }
    body { background: white; }
}
</style>
@endsection
