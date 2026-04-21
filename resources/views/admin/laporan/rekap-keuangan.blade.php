@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-chart-pie"></i></div>
    <h1 class="club-title">Rekap Keuangan</h1>
</div>

<!-- Filter -->
<div style="background:white;padding:18px 20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);margin-bottom:22px;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555;">Bulan</label>
            <select name="bulan" style="padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;min-width:120px;">
                @foreach($namaBulan as $v=>$l)
                <option value="{{ $v }}" {{ $bulan==$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555;">Tahun</label>
            <select name="tahun" style="padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                @foreach([2026,2025,2024] as $y)
                <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555;">Jenis</label>
            <select name="jenis" style="padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;min-width:140px;">
                <option value="">Semua Jenis</option>
                <option value="pemasukan" {{ $jenis=='pemasukan'?'selected':'' }}>Pemasukan</option>
                <option value="pengeluaran" {{ $jenis=='pengeluaran'?'selected':'' }}>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:#555;">Kategori</label>
            <select name="kategori" style="padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;min-width:180px;">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $k)
                <option value="{{ $k }}" {{ $kategori==$k?'selected':'' }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:8px;">
            <button type="submit" style="background:#d32f2f;color:white;border:none;padding:9px 18px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.rekap-keuangan') }}" style="background:#f5f5f5;color:#555;border:1px solid #ddd;padding:9px 14px;border-radius:6px;font-size:13px;text-decoration:none;">
                <i class="fas fa-times"></i> Reset
            </a>
            <button type="button" onclick="window.print()" style="background:#607d8b;color:white;border:none;padding:9px 14px;border-radius:6px;font-size:13px;cursor:pointer;">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </form>
</div>

<!-- Header Periode -->
<div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:white;padding:18px 25px;border-radius:10px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
    <div>
        <div style="font-size:12px;opacity:.8;">Periode</div>
        <div style="font-size:20px;font-weight:700;">{{ $namaBulan[$bulan] }} {{ $tahun }}</div>
        @if($jenis || $kategori)
        <div style="font-size:12px;opacity:.8;margin-top:3px;">
            Filter: {{ $jenis ? ucfirst($jenis) : '' }}{{ $jenis && $kategori ? ' · ' : '' }}{{ $kategori ?? '' }}
        </div>
        @endif
    </div>
    <div style="display:flex;gap:25px;text-align:center;">
        <div>
            <div style="font-size:11px;opacity:.8;">PEMASUKAN</div>
            <div style="font-size:18px;font-weight:700;color:#a5d6a7;">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
        </div>
        <div>
            <div style="font-size:11px;opacity:.8;">PENGELUARAN</div>
            <div style="font-size:18px;font-weight:700;color:#ef9a9a;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
        </div>
        <div>
            <div style="font-size:11px;opacity:.8;">SALDO BERSIH</div>
            <div style="font-size:18px;font-weight:700;color:{{ $saldoBersih>=0?'#fff':'#ffcc80' }};">
                {{ $saldoBersih>=0?'+':'' }}Rp {{ number_format($saldoBersih,0,',','.') }}
            </div>
        </div>
    </div>
</div>

<!-- 3 Summary Cards -->
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-bottom:22px;">
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);padding:20px;border-top:4px solid #4caf50;">
        <div style="font-size:11px;color:#666;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Pemasukan</div>
        <div style="font-size:24px;font-weight:700;color:#2e7d32;margin:6px 0;">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
        <div style="font-size:12px;color:#999;">{{ $semua->where('jenis','pemasukan')->count() }} transaksi</div>
    </div>
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);padding:20px;border-top:4px solid #f44336;">
        <div style="font-size:11px;color:#666;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Pengeluaran</div>
        <div style="font-size:24px;font-weight:700;color:#c62828;margin:6px 0;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
        <div style="font-size:12px;color:#999;">{{ $semua->where('jenis','pengeluaran')->count() }} transaksi</div>
    </div>
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);padding:20px;border-top:4px solid {{ $saldoBersih>=0?'#2196f3':'#ff9800' }};">
        <div style="font-size:11px;color:#666;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Saldo Bersih</div>
        <div style="font-size:24px;font-weight:700;color:{{ $saldoBersih>=0?'#1565c0':'#e65100' }};margin:6px 0;">
            {{ $saldoBersih>=0?'+':'' }}Rp {{ number_format($saldoBersih,0,',','.') }}
        </div>
        @php $pctMasuk = ($totalPemasukan+$totalPengeluaran)>0 ? ($totalPemasukan/($totalPemasukan+$totalPengeluaran)*100) : 0; @endphp
        <div style="background:#f0f0f0;border-radius:4px;height:5px;overflow:hidden;margin-top:8px;">
            <div style="background:#4caf50;height:5px;width:{{ $pctMasuk }}%;float:left;"></div>
            <div style="background:#f44336;height:5px;width:{{ 100-$pctMasuk }}%;float:left;"></div>
        </div>
    </div>
</div>

<!-- Breakdown + Tren -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:22px;">

    <!-- Breakdown Pemasukan -->
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
        <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#f9fbe7;">
            <h3 style="margin:0;font-size:14px;font-weight:600;color:#2e7d32;"><i class="fas fa-arrow-up"></i> Rincian Pemasukan</h3>
        </div>
        <div style="padding:14px 18px;">
            @foreach($breakdownPemasukan as $item)
            @php $pct = $totalPemasukan>0 ? ($item['jumlah']/$totalPemasukan*100) : 0; @endphp
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                    <div style="display:flex;align-items:center;gap:7px;">
                        <div style="width:24px;height:24px;background:{{ $item['color'] }};border-radius:5px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas {{ $item['icon'] }}" style="color:white;font-size:10px;"></i>
                        </div>
                        <span style="font-size:12px;font-weight:600;color:#333;">{{ $item['label'] }}</span>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:12px;font-weight:700;color:#2e7d32;">Rp {{ number_format($item['jumlah'],0,',','.') }}</span>
                        <span style="font-size:10px;color:#999;margin-left:4px;">{{ number_format($pct,0) }}%</span>
                    </div>
                </div>
                <div style="background:#f0f0f0;border-radius:3px;height:5px;overflow:hidden;">
                    <div style="background:{{ $item['color'] }};height:5px;width:{{ $pct }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Breakdown Pengeluaran -->
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
        <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fff3e0;">
            <h3 style="margin:0;font-size:14px;font-weight:600;color:#c62828;"><i class="fas fa-arrow-down"></i> Rincian Pengeluaran</h3>
        </div>
        <div style="padding:14px 18px;">
            @forelse($pengeluaranPerKategori as $item)
            @php $pct = $totalPengeluaran>0 ? ($item->total/$totalPengeluaran*100) : 0; @endphp
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                    <span style="font-size:12px;font-weight:600;color:#333;">{{ ucfirst($item->kategori) }} <span style="color:#999;font-weight:400;">({{ $item->count }}x)</span></span>
                    <div style="text-align:right;">
                        <span style="font-size:12px;font-weight:700;color:#c62828;">Rp {{ number_format($item->total,0,',','.') }}</span>
                        <span style="font-size:10px;color:#999;margin-left:4px;">{{ number_format($pct,0) }}%</span>
                    </div>
                </div>
                <div style="background:#f0f0f0;border-radius:3px;height:5px;overflow:hidden;">
                    <div style="background:#f44336;height:5px;width:{{ $pct }}%;"></div>
                </div>
            </div>
            @empty
            <p style="text-align:center;color:#999;font-size:13px;padding:15px 0;">Tidak ada pengeluaran</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Tren 6 Bulan -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;margin-bottom:22px;">
    <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;">
        <h3 style="margin:0;font-size:14px;font-weight:600;"><i class="fas fa-chart-bar" style="color:#d32f2f;"></i> Tren 6 Bulan Terakhir</h3>
    </div>
    <div style="padding:18px 20px;overflow-x:auto;">
        @php $maxVal = $tren->max(fn($t) => max($t['masuk'],$t['keluar'])); @endphp
        <div style="display:flex;gap:12px;align-items:flex-end;min-width:480px;height:140px;">
            @foreach($tren as $t)
            @php $hM = $maxVal>0 ? ($t['masuk']/$maxVal*120) : 0; $hK = $maxVal>0 ? ($t['keluar']/$maxVal*120) : 0; @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;">
                <div style="display:flex;gap:2px;align-items:flex-end;height:120px;">
                    <div title="Masuk: Rp {{ number_format($t['masuk'],0,',','.') }}"
                        style="width:16px;background:#4caf50;border-radius:3px 3px 0 0;height:{{ max($hM,2) }}px;cursor:pointer;"
                        onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'"></div>
                    <div title="Keluar: Rp {{ number_format($t['keluar'],0,',','.') }}"
                        style="width:16px;background:#f44336;border-radius:3px 3px 0 0;height:{{ max($hK,2) }}px;cursor:pointer;"
                        onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'"></div>
                </div>
                <div style="font-size:9px;color:#666;text-align:center;white-space:nowrap;">{{ $t['bulan'] }}</div>
            </div>
            @endforeach
        </div>
        <div style="display:flex;gap:16px;margin-top:8px;justify-content:center;">
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#666;">
                <div style="width:10px;height:10px;background:#4caf50;border-radius:2px;"></div> Pemasukan
            </div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#666;">
                <div style="width:10px;height:10px;background:#f44336;border-radius:2px;"></div> Pengeluaran
            </div>
        </div>
    </div>
</div>

<!-- ── TABEL TRANSAKSI LENGKAP ─────────────────────────────────── -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;margin-bottom:22px;">
    <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">
            <i class="fas fa-table" style="color:#d32f2f;"></i>
            Semua Transaksi
            <span style="font-size:12px;color:#999;font-weight:400;margin-left:6px;">{{ $filtered->count() }} data</span>
        </h3>
        <div style="display:flex;gap:10px;align-items:center;">
            <!-- Search client-side -->
            <input type="text" id="searchInput" placeholder="Cari deskripsi..." oninput="filterTable(this.value)"
                style="padding:6px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;width:200px;">
            <div style="font-size:13px;font-weight:600;">
                <span style="color:#2e7d32;">+Rp {{ number_format($filtered->where('jenis','pemasukan')->sum('jumlah'),0,',','.') }}</span>
                &nbsp;/&nbsp;
                <span style="color:#c62828;">-Rp {{ number_format($filtered->where('jenis','pengeluaran')->sum('jumlah'),0,',','.') }}</span>
            </div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table id="mainTable" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">No</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">Tanggal</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">Jenis</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">Kategori</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Deskripsi</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">Metode</th>
                    <th style="padding:10px 14px;text-align:right;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">Jumlah</th>
                    <th style="padding:10px 14px;text-align:right;font-size:11px;color:#666;border-bottom:1px solid #eee;white-space:nowrap;">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filtered as $i => $t)
                <tr class="trow" style="border-left:3px solid {{ $t['jenis']==='pemasukan'?'#4caf50':'#f44336' }};"
                    onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($t['tanggal'])->format('d M Y') }}
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($t['jenis']==='pemasukan')
                            <span style="background:#e8f5e9;color:#2e7d32;padding:2px 8px;border-radius:8px;font-size:10px;font-weight:700;white-space:nowrap;">
                                <i class="fas fa-arrow-up"></i> MASUK
                            </span>
                        @else
                            <span style="background:#ffebee;color:#c62828;padding:2px 8px;border-radius:8px;font-size:10px;font-weight:700;white-space:nowrap;">
                                <i class="fas fa-arrow-down"></i> KELUAR
                            </span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;white-space:nowrap;">
                        {{ $t['kategori'] }}
                        @if($t['ref'])
                            <br><span style="font-size:10px;color:#999;">{{ $t['ref'] }}</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#555;" class="desc-cell">
                        {{ $t['deskripsi'] }}
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;white-space:nowrap;">
                        {{ $t['metode'] }}
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;font-weight:700;text-align:right;white-space:nowrap;
                        color:{{ $t['jenis']==='pemasukan'?'#2e7d32':'#c62828' }};">
                        {{ $t['jenis']==='pemasukan'?'+':'-' }}Rp {{ number_format($t['jumlah'],0,',','.') }}
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;text-align:right;white-space:nowrap;
                        color:{{ $t['saldo']>=0?'#333':'#f44336' }};">
                        Rp {{ number_format($t['saldo'],0,',','.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:50px;text-align:center;color:#999;">
                        <i class="fas fa-search" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Tidak ada transaksi di periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($filtered->count() > 0)
            <tfoot>
                <tr style="background:#f5f5f5;font-weight:700;">
                    <td colspan="6" style="padding:12px 14px;font-size:13px;">TOTAL ({{ $filtered->count() }} transaksi)</td>
                    <td style="padding:12px 14px;text-align:right;font-size:13px;">
                        <div style="color:#2e7d32;">+Rp {{ number_format($filtered->where('jenis','pemasukan')->sum('jumlah'),0,',','.') }}</div>
                        <div style="color:#c62828;">-Rp {{ number_format($filtered->where('jenis','pengeluaran')->sum('jumlah'),0,',','.') }}</div>
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

<!-- Ringkasan Akhir -->
<div style="background:linear-gradient(135deg,#263238,#37474f);color:white;padding:22px 25px;border-radius:10px;">
    <h3 style="margin:0 0 16px 0;font-size:15px;font-weight:600;"><i class="fas fa-calculator"></i> Ringkasan — {{ $namaBulan[$bulan] }} {{ $tahun }}</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:8px;">
        @foreach($breakdownPemasukan->where('jumlah','>',0) as $item)
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.08);">
            <span style="font-size:13px;opacity:.85;">{{ $item['label'] }}</span>
            <span style="font-size:13px;font-weight:600;color:#a5d6a7;">+Rp {{ number_format($item['jumlah'],0,',','.') }}</span>
        </div>
        @endforeach
        @foreach($pengeluaranPerKategori as $item)
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.08);">
            <span style="font-size:13px;opacity:.85;">Pengeluaran {{ ucfirst($item->kategori) }}</span>
            <span style="font-size:13px;font-weight:600;color:#ef9a9a;">-Rp {{ number_format($item->total,0,',','.') }}</span>
        </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-top:2px solid rgba(255,255,255,.25);margin-top:4px;grid-column:1/-1;">
            <span style="font-size:15px;font-weight:700;">SALDO BERSIH</span>
            <span style="font-size:15px;font-weight:700;color:{{ $saldoBersih>=0?'#a5d6a7':'#ffcc80' }};">
                {{ $saldoBersih>=0?'+':'' }}Rp {{ number_format($saldoBersih,0,',','.') }}
            </span>
        </div>
    </div>
</div>

<script>
function filterTable(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#mainTable tbody .trow').forEach(row => {
        const desc = row.querySelector('.desc-cell')?.textContent.toLowerCase() ?? '';
        row.style.display = desc.includes(q) ? '' : 'none';
    });
}
</script>

<style>
@media print {
    .sidebar, .top-bar, form, button, #searchInput { display: none !important; }
    .main-content { margin-left: 0 !important; }
    body { background: white; }
}
</style>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MONITORING IURAN RUTIN                                         --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;margin-top:22px;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">
            <i class="fas fa-money-check-alt" style="color:#d32f2f;"></i>
            Monitoring Iuran Rutin — {{ $namaBulan[$bulan] }} {{ $tahun }}
        </h3>
        <div style="display:flex;gap:15px;font-size:13px;">
            <span style="color:#2e7d32;font-weight:600;"><i class="fas fa-check-circle"></i> {{ $totalSudahBayar }} Sudah Bayar</span>
            <span style="color:#c62828;font-weight:600;"><i class="fas fa-clock"></i> {{ $totalBelumBayar }} Belum Bayar</span>
            <span style="color:#666;">dari {{ $totalSiswaAktif }} siswa aktif</span>
        </div>
    </div>
    @php $pctBayar = $totalSiswaAktif > 0 ? ($totalSudahBayar / $totalSiswaAktif * 100) : 0; @endphp
    <div style="padding:12px 20px;background:#fafafa;border-bottom:1px solid #f0f0f0;">
        <div style="display:flex;justify-content:space-between;font-size:12px;color:#666;margin-bottom:5px;">
            <span>Progress Pembayaran</span>
            <span style="font-weight:600;">{{ number_format($pctBayar,0) }}%</span>
        </div>
        <div style="background:#f0f0f0;border-radius:6px;height:10px;overflow:hidden;">
            <div style="background:linear-gradient(90deg,#4caf50,#2e7d32);height:10px;width:{{ $pctBayar }}%;border-radius:6px;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:11px;color:#999;margin-top:4px;">
            <span style="color:#2e7d32;">{{ $totalSudahBayar }} siswa lunas</span>
            <span style="color:#c62828;">{{ $totalBelumBayar }} siswa belum bayar</span>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;">
        <div style="border-right:1px solid #f0f0f0;">
            <div style="padding:11px 16px;background:#e8f5e9;border-bottom:1px solid #c8e6c9;">
                <span style="font-size:13px;font-weight:700;color:#2e7d32;"><i class="fas fa-check-circle"></i> Sudah Bayar ({{ $totalSudahBayar }})</span>
            </div>
            <div style="max-height:280px;overflow-y:auto;">
                @forelse($sudahBayar as $s)
                <div style="padding:9px 16px;border-bottom:1px solid #f5f5f5;display:flex;justify-content:space-between;align-items:center;"
                    onmouseover="this.style.background='#f9fbe7'" onmouseout="this.style.background='white'">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $s->nama }}</div>
                        <div style="font-size:11px;color:#999;">{{ $s->kelas }}</div>
                    </div>
                    <span style="background:#e8f5e9;color:#2e7d32;padding:2px 8px;border-radius:8px;font-size:10px;font-weight:700;">LUNAS</span>
                </div>
                @empty
                <div style="padding:30px;text-align:center;color:#999;font-size:13px;">Belum ada yang bayar</div>
                @endforelse
            </div>
        </div>
        <div>
            <div style="padding:11px 16px;background:#ffebee;border-bottom:1px solid #ffcdd2;">
                <span style="font-size:13px;font-weight:700;color:#c62828;"><i class="fas fa-clock"></i> Belum Bayar ({{ $totalBelumBayar }})</span>
            </div>
            <div style="max-height:280px;overflow-y:auto;">
                @forelse($belumBayar as $s)
                <div style="padding:9px 16px;border-bottom:1px solid #f5f5f5;display:flex;justify-content:space-between;align-items:center;"
                    onmouseover="this.style.background='#fff8f8'" onmouseout="this.style.background='white'">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $s->nama }}</div>
                        <div style="font-size:11px;color:#999;">{{ $s->kelas }} &bull; {{ $s->telepon }}</div>
                    </div>
                    <a href="{{ route('admin.iuran-rutin') }}" style="background:#ffebee;color:#c62828;padding:2px 8px;border-radius:8px;font-size:10px;font-weight:700;text-decoration:none;">TAGIH</a>
                </div>
                @empty
                <div style="padding:30px;text-align:center;color:#4caf50;font-size:13px;">
                    <i class="fas fa-check-circle" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                    Semua siswa sudah bayar!
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MONITORING PENDAFTARAN                                          --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:22px;">
    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
        <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#fff3e0,#ffe0b2);">
            <h3 style="margin:0;font-size:14px;font-weight:600;color:#e65100;"><i class="fas fa-user-plus"></i> Calon Siswa Menunggu Verifikasi</h3>
            <span style="background:#ff9800;color:white;padding:2px 10px;border-radius:10px;font-size:12px;font-weight:700;">{{ $calonSiswa->count() }}</span>
        </div>
        @if($calonSiswa->isEmpty())
        <div style="padding:30px;text-align:center;color:#999;font-size:13px;">Tidak ada calon siswa</div>
        @else
        <div style="max-height:300px;overflow-y:auto;">
            @foreach($calonSiswa as $s)
            <div style="padding:10px 16px;border-bottom:1px solid #f5f5f5;display:flex;justify-content:space-between;align-items:center;"
                onmouseover="this.style.background='#fffde7'" onmouseout="this.style.background='white'">
                <div>
                    <div style="font-size:13px;font-weight:600;">{{ $s->nama }}</div>
                    <div style="font-size:11px;color:#999;">{{ $s->kelas }} &bull; {{ $s->created_at->format('d M Y') }}</div>
                    <div style="font-size:11px;color:#666;">{{ $s->nama_ortu }} &bull; {{ $s->telepon }}</div>
                </div>
                <a href="{{ route('admin.calon-siswa') }}" style="background:#fff3e0;color:#e65100;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">Proses</a>
            </div>
            @endforeach
        </div>
        <div style="padding:10px 16px;border-top:1px solid #f0f0f0;text-align:right;">
            <a href="{{ route('admin.calon-siswa') }}" style="font-size:12px;color:#e65100;text-decoration:none;font-weight:600;">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        @endif
    </div>

    <div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
        <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);">
            <h3 style="margin:0;font-size:14px;font-weight:600;color:#2e7d32;"><i class="fas fa-user-check"></i> Siswa Baru — {{ $namaBulan[$bulan] }} {{ $tahun }}</h3>
            <span style="background:#4caf50;color:white;padding:2px 10px;border-radius:10px;font-size:12px;font-weight:700;">{{ $siswaBaru->count() }}</span>
        </div>
        @if($siswaBaru->isEmpty())
        <div style="padding:30px;text-align:center;color:#999;font-size:13px;">Tidak ada siswa baru bulan ini</div>
        @else
        <div style="max-height:300px;overflow-y:auto;">
            @foreach($siswaBaru as $s)
            <div style="padding:10px 16px;border-bottom:1px solid #f5f5f5;display:flex;justify-content:space-between;align-items:center;"
                onmouseover="this.style.background='#f9fbe7'" onmouseout="this.style.background='white'">
                <div>
                    <div style="font-size:13px;font-weight:600;">{{ $s->nama }}</div>
                    <div style="font-size:11px;color:#999;">{{ $s->kelas }} &bull; {{ $s->created_at->format('d M Y') }}</div>
                </div>
                <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;">AKTIF</span>
            </div>
            @endforeach
        </div>
        <div style="padding:10px 16px;border-top:1px solid #f0f0f0;text-align:right;">
            <a href="{{ route('admin.siswa-aktif') }}" style="font-size:12px;color:#2e7d32;text-decoration:none;font-weight:600;">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        @endif
    </div>
</div>

@endsection
