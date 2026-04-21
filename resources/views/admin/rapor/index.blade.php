@extends('layouts.admin')
@section('content')

<h1 style="color:#d32f2f;font-size:26px;font-weight:700;margin-bottom:20px;font-style:italic;">RAPOR SISWA</h1>

@if(session('success'))
<div style="margin-bottom:16px;padding:12px 16px;background:#d4edda;color:#155724;border-radius:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Alur Info -->
<div style="background:#e8f5e9;border-radius:8px;padding:12px 18px;margin-bottom:20px;border-left:4px solid #4caf50;font-size:13px;color:#2e7d32;">
    <strong><i class="fas fa-route"></i> Alur Rapor:</strong>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-top:6px;">
        <span style="background:#2e7d32;color:white;padding:3px 10px;border-radius:12px;font-size:11px;">1. Buat Template Rapor</span>
        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        <span style="background:#2e7d32;color:white;padding:3px 10px;border-radius:12px;font-size:11px;">2. Isi Rapor per Siswa</span>
        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        <span style="background:#2e7d32;color:white;padding:3px 10px;border-radius:12px;font-size:11px;">3. Cetak / Kirim ke Ortu</span>
    </div>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#2196f3,#1565c0);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['total'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Siswa</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#2e7d32);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['selesai'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Rapor Selesai</div>
    </div>
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['draft'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Draft</div>
    </div>
    <div style="background:linear-gradient(135deg,#f44336,#c62828);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['belum'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Belum Dibuat</div>
    </div>
</div>

<!-- Filter -->
<div style="background:white;padding:16px 20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.07);">
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
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Kelas</label>
            <select name="kelas" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $kelas==$k?'selected':'' }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Status</label>
            <select name="status" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                <option value="">Semua Status</option>
                <option value="selesai" {{ $statusRapor=='selesai'?'selected':'' }}>Selesai</option>
                <option value="draft" {{ $statusRapor=='draft'?'selected':'' }}>Draft</option>
                <option value="belum_dibuat" {{ $statusRapor=='belum_dibuat'?'selected':'' }}>Belum Dibuat</option>
            </select>
        </div>
        <button type="submit" style="background:#d32f2f;color:white;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('admin.rapor') }}" style="padding:8px 14px;border:1px solid #ddd;border-radius:6px;font-size:13px;text-decoration:none;color:#666;">Reset</a>
        <a href="{{ route('admin.template-rapor') }}" style="background:#9c27b0;color:white;padding:8px 16px;border-radius:6px;font-size:13px;text-decoration:none;font-weight:600;margin-left:auto;">
            <i class="fas fa-file-pdf"></i> Kelola Template
        </a>
    </form>
</div>

<!-- Tabel Siswa -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:14px 20px;border-bottom:1px solid #f0f0f0;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">
            <i class="fas fa-file-alt" style="color:#d32f2f;"></i>
            Rapor {{ $namaBulan[$bulan] }} {{ $tahun }} ({{ $siswas->count() }} siswa)
        </h3>
    </div>
    @if($siswas->isEmpty())
    <div style="padding:50px;text-align:center;color:#999;">
        <i class="fas fa-users" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
        Tidak ada siswa di filter ini
    </div>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Nama Siswa</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Kelas</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Kehadiran</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Nilai Rata-rata</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Status Rapor</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswas as $i => $s)
                @php
                    $rapor = $s->rapors->first();
                    $nilaiRata = $rapor ? $rapor->nilai_rata_rata : 0;
                    $kehadiran = $rapor ? "{$rapor->kehadiran}/{$rapor->total_pertemuan}" : '-';
                    $statusRapor = $rapor ? $rapor->status : 'belum_dibuat';
                @endphp
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-weight:600;">{{ $s->nama }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;">{{ $s->kelas }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;font-size:12px;">{{ $kehadiran }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        @if($nilaiRata > 0)
                        <span style="background:{{ $nilaiRata>=80?'#e8f5e9':($nilaiRata>=60?'#fff3e0':'#ffebee') }};
                                     color:{{ $nilaiRata>=80?'#2e7d32':($nilaiRata>=60?'#e65100':'#c62828') }};
                                     padding:4px 10px;border-radius:10px;font-weight:700;font-size:13px;">
                            {{ number_format($nilaiRata,1) }}
                        </span>
                        @else
                        <span style="color:#999;">-</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        @if($statusRapor === 'selesai')
                        <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-check-circle"></i> Selesai</span>
                        @elseif($statusRapor === 'draft')
                        <span style="background:#fff3e0;color:#e65100;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-edit"></i> Draft</span>
                        @else
                        <span style="background:#ffebee;color:#c62828;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-times-circle"></i> Belum Dibuat</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <a href="{{ route('admin.rapor.siswa', ['siswa' => $s, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                                style="background:{{ $rapor?'#ff9800':'#4caf50' }};color:white;padding:5px 10px;border-radius:5px;font-size:11px;text-decoration:none;font-weight:600;">
                                <i class="fas fa-{{ $rapor ? 'edit' : 'plus' }}"></i> {{ $rapor ? 'Edit' : 'Buat' }}
                            </a>
                            @if($rapor && $rapor->status === 'selesai')
                            <button onclick="alert('Fitur cetak PDF akan segera tersedia')"
                                style="background:#e3f2fd;color:#1565c0;padding:5px 9px;border-radius:5px;font-size:11px;border:none;cursor:pointer;">
                                <i class="fas fa-print"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
