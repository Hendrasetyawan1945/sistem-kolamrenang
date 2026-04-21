@extends('layouts.admin')
@section('content')

<h1 style="color:#d32f2f;font-size:26px;font-weight:700;margin-bottom:20px;font-style:italic;">PROGRESS REPORT</h1>

<!-- Alur Penjelasan -->
<div style="background:#e3f2fd;border-radius:10px;padding:16px 20px;margin-bottom:22px;border-left:4px solid #2196f3;">
    <div style="font-size:13px;font-weight:700;color:#1565c0;margin-bottom:8px;"><i class="fas fa-route"></i> Alur Progress Report</div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;font-size:12px;color:#1565c0;">
        <span style="background:#1565c0;color:white;padding:4px 10px;border-radius:15px;">1. Input Catatan Waktu Latihan</span>
        <i class="fas fa-arrow-right"></i>
        <span style="background:#1565c0;color:white;padding:4px 10px;border-radius:15px;">2. Input Catatan Waktu Resmi (Kejuaraan/Time Trial)</span>
        <i class="fas fa-arrow-right"></i>
        <span style="background:#d32f2f;color:white;padding:4px 10px;border-radius:15px;">3. Progress Report otomatis terhitung</span>
    </div>
    <div style="margin-top:8px;font-size:12px;color:#1565c0;opacity:.8;">
        Progress dihitung dari: jumlah sesi latihan, personal best, tren waktu (apakah membaik), dan perbandingan waktu awal vs terkini.
    </div>
</div>

<!-- Filter -->
<div style="background:white;padding:16px 20px;border-radius:10px;margin-bottom:22px;box-shadow:0 2px 8px rgba(0,0,0,.07);">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Siswa</label>
            <select name="siswa_id" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;min-width:180px;" onchange="this.form.submit()">
                <option value="">Semua Siswa</option>
                @foreach($allSiswas as $s)
                <option value="{{ $s->id }}" {{ $siswaId==$s->id?'selected':'' }}>{{ $s->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Kelas</label>
            <select name="kelas" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $kelas==$k?'selected':'' }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Periode</label>
            <select name="periode" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="1_bulan" {{ $periode=='1_bulan'?'selected':'' }}>1 Bulan Terakhir</option>
                <option value="3_bulan" {{ $periode=='3_bulan'?'selected':'' }}>3 Bulan Terakhir</option>
                <option value="6_bulan" {{ $periode=='6_bulan'?'selected':'' }}>6 Bulan Terakhir</option>
                <option value="tahun_ini" {{ $periode=='tahun_ini'?'selected':'' }}>Tahun Ini</option>
            </select>
        </div>
        <a href="{{ route('admin.progress-report') }}" style="padding:7px 14px;border:1px solid #ddd;border-radius:6px;font-size:13px;text-decoration:none;color:#666;">Reset</a>
    </form>
</div>

@php
$periodeLabel = ['1_bulan'=>'1 Bulan','3_bulan'=>'3 Bulan','6_bulan'=>'6 Bulan','tahun_ini'=>'Tahun Ini'][$periode] ?? '3 Bulan';
$aktifCount = $progressData->where('aktif', true)->count();
@endphp

<!-- Summary Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#2196f3,#1565c0);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $progressData->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Siswa</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#2e7d32);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $aktifCount }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Ada Aktivitas</div>
    </div>
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $progressData->sum('total_catatan') }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Catatan Resmi</div>
    </div>
    <div style="background:linear-gradient(135deg,#9c27b0,#6a1b9a);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $progressData->sum('total_latihan') }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Sesi Latihan</div>
    </div>
</div>

<!-- Tabel Progress -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;margin-bottom:22px;">
    <div style="padding:14px 20px;border-bottom:1px solid #f0f0f0;">
        <h3 style="margin:0;font-size:15px;font-weight:600;"><i class="fas fa-chart-line" style="color:#d32f2f;"></i> Progress Siswa — {{ $periodeLabel }} Terakhir</h3>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Siswa</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Kelas</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Catatan Resmi</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Sesi Latihan</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Personal Best</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Tren Waktu</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Status</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressData as $p)
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-weight:600;">{{ $p['siswa']->nama }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;">{{ $p['siswa']->kelas }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <span style="background:#e3f2fd;color:#1565c0;padding:3px 10px;border-radius:10px;font-size:12px;font-weight:700;">{{ $p['total_catatan'] }}</span>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <span style="background:#f3e5f5;color:#6a1b9a;padding:3px 10px;border-radius:10px;font-size:12px;font-weight:700;">{{ $p['total_latihan'] }}</span>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($p['personal_bests']->isEmpty())
                            <span style="color:#999;font-size:12px;">Belum ada</span>
                        @else
                            @foreach($p['personal_bests']->take(2) as $pb)
                            <div style="font-size:11px;margin-bottom:2px;">
                                <span style="color:#666;">{{ $pb->nomor_lomba }}</span>
                                <strong style="color:#d32f2f;margin-left:5px;">{{ $pb->best_waktu }}</strong>
                                <span style="color:#999;font-size:10px;">({{ $pb->jenis_kolam }})</span>
                            </div>
                            @endforeach
                            @if($p['personal_bests']->count() > 2)
                            <span style="font-size:10px;color:#999;">+{{ $p['personal_bests']->count()-2 }} lainnya</span>
                            @endif
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($p['tren']->isEmpty())
                            <span style="color:#999;font-size:12px;">Data kurang</span>
                        @else
                            @foreach($p['tren']->take(2) as $nomor => $t)
                            <div style="font-size:11px;margin-bottom:3px;display:flex;align-items:center;gap:5px;">
                                <span style="color:#666;font-size:10px;">{{ $nomor }}:</span>
                                <span style="color:#999;">{{ $t['awal'] }}</span>
                                <i class="fas fa-arrow-right" style="font-size:9px;color:#999;"></i>
                                <span style="font-weight:600;color:{{ $t['membaik']?'#2e7d32':'#c62828' }};">{{ $t['akhir'] }}</span>
                                <span style="font-size:10px;color:{{ $t['membaik']?'#4caf50':'#f44336' }};">
                                    {{ $t['membaik'] ? '▼ Membaik' : '▲ Melambat' }}
                                </span>
                            </div>
                            @endforeach
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        @if(!$p['aktif'])
                            <span style="background:#f5f5f5;color:#999;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;">Tidak Aktif</span>
                        @elseif($p['total_catatan'] + $p['total_latihan'] >= 10)
                            <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-star"></i> Aktif</span>
                        @else
                            <span style="background:#fff3e0;color:#e65100;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;">Perlu Perhatian</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <button onclick="toggleDetail('detail-{{ $p['siswa']->id }}')"
                            style="background:#e3f2fd;color:#1565c0;border:none;padding:5px 10px;border-radius:5px;font-size:11px;cursor:pointer;">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                <!-- Detail Row -->
                <tr id="detail-{{ $p['siswa']->id }}" style="display:none;background:#f8f9fa;">
                    <td colspan="8" style="padding:16px 20px;border-bottom:2px solid #e0e0e0;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <!-- Personal Best Detail -->
                            <div>
                                <div style="font-size:12px;font-weight:700;color:#333;margin-bottom:10px;"><i class="fas fa-trophy" style="color:#ffc107;"></i> Personal Best Semua Nomor</div>
                                @if($p['personal_bests']->isEmpty())
                                <p style="color:#999;font-size:12px;">Belum ada catatan waktu resmi</p>
                                @else
                                <table style="width:100%;font-size:12px;border-collapse:collapse;">
                                    @foreach($p['personal_bests'] as $pb)
                                    <tr>
                                        <td style="padding:4px 8px;border-bottom:1px solid #eee;">{{ $pb->nomor_lomba }}</td>
                                        <td style="padding:4px 8px;border-bottom:1px solid #eee;color:#999;">{{ $pb->jenis_kolam }}</td>
                                        <td style="padding:4px 8px;border-bottom:1px solid #eee;font-weight:700;color:#d32f2f;">{{ $pb->best_waktu }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                @endif
                            </div>
                            <!-- Tren Detail -->
                            <div>
                                <div style="font-size:12px;font-weight:700;color:#333;margin-bottom:10px;"><i class="fas fa-chart-line" style="color:#2196f3;"></i> Tren Perkembangan</div>
                                @if($p['tren']->isEmpty())
                                <p style="color:#999;font-size:12px;">Butuh minimal 2 catatan per nomor untuk melihat tren</p>
                                @else
                                @foreach($p['tren'] as $nomor => $t)
                                <div style="margin-bottom:10px;">
                                    <div style="font-size:11px;color:#666;margin-bottom:4px;">{{ $nomor }}</div>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span style="background:#f5f5f5;padding:3px 8px;border-radius:5px;font-size:12px;">{{ $t['awal'] }}</span>
                                        <i class="fas fa-long-arrow-alt-right" style="color:#999;"></i>
                                        <span style="background:{{ $t['membaik']?'#e8f5e9':'#ffebee' }};padding:3px 8px;border-radius:5px;font-size:12px;font-weight:700;color:{{ $t['membaik']?'#2e7d32':'#c62828' }};">{{ $t['akhir'] }}</span>
                                        @php $selisihAbs = abs($t['selisih']); $mnt = floor($selisihAbs/60); $dtk = $selisihAbs%60; @endphp
                                        <span style="font-size:11px;color:{{ $t['membaik']?'#4caf50':'#f44336' }};">
                                            {{ $t['membaik'] ? '▼' : '▲' }} {{ $mnt > 0 ? $mnt.'m ' : '' }}{{ number_format($dtk,2) }}s
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div style="margin-top:12px;display:flex;gap:10px;">
                            <a href="{{ route('admin.catatan-waktu', ['siswa_id' => $p['siswa']->id]) }}"
                                style="background:#d32f2f;color:white;padding:6px 14px;border-radius:6px;font-size:12px;text-decoration:none;font-weight:600;">
                                <i class="fas fa-stopwatch"></i> Lihat Catatan Waktu
                            </a>
                            <a href="{{ route('admin.catatan-waktu-latihan', ['siswa_id' => $p['siswa']->id]) }}"
                                style="background:#4caf50;color:white;padding:6px 14px;border-radius:6px;font-size:12px;text-decoration:none;font-weight:600;">
                                <i class="fas fa-clock"></i> Lihat Catatan Latihan
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:50px;text-align:center;color:#999;">
                        <i class="fas fa-chart-line" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Tidak ada data siswa
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleDetail(id) {
    const row = document.getElementById(id);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endsection
