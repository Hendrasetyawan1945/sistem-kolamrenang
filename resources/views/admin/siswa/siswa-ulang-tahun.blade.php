@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-birthday-cake"></i>
    </div>
    <h1 class="club-title">Siswa Ulang Tahun</h1>
</div>

<!-- Filter Bulan -->
<div class="dashboard-card" style="margin-bottom: 30px;">
    <h3 class="card-title">Filter Bulan Ulang Tahun</h3>
    
    <form action="{{ route('admin.siswa-ulang-tahun') }}" method="GET" class="search-form">
        <select name="bulan" class="form-select">
            <option value="">Pilih Bulan</option>
            <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
            <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
            <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
            <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
            <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
            <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
            <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
            <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
            <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
            <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
            <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
            <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
        </select>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Ulang Tahun Hari Ini -->
@if(date('d') == '20' && date('m') == '04')
<div class="dashboard-card" style="background: linear-gradient(135deg, #e91e63, #ad1457); color: white; margin-bottom: 30px;">
    <h3 style="color: white; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-gift"></i>
        Ulang Tahun Hari Ini! 🎉
    </h3>
    <div style="margin-top: 15px;">
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; margin-bottom: 10px;">
            <h4 style="margin: 0; font-size: 16px;"><i class="fas fa-birthday-cake"></i> Kayden Liam Teman Kinsey</h4>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">11 tahun - Kelas: KU-12</p>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px;">
            <h4 style="margin: 0; font-size: 16px;"><i class="fas fa-birthday-cake"></i> Michelle G. Declan</h4>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">11 tahun - Kelas: KU-10</p>
        </div>
    </div>
    <div style="margin-top: 15px;">
        <button class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
            <i class="fas fa-whatsapp"></i> Kirim Ucapan
        </button>
    </div>
</div>
@endif

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>
                @php
                    $namaBulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                        '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                    ];
                @endphp
                @if(request('bulan'))
                    Daftar Ulang Tahun Bulan {{ $namaBulan[request('bulan')] ?? '' }}
                @else
                    Daftar Ulang Tahun Semua Bulan
                @endif
            </h4>
            <div>
                <input type="text" placeholder="Search..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Tanggal Lahir</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Umur</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Kelas</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Telepon Ortu</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(request('bulan') == '04')
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">1</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <strong>Kayden Liam Teman Kinsey</strong>
                            @if(date('d') == '20')
                                <span style="background: #e91e63; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px; margin-left: 5px;">HARI INI!</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">20 April 2015</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">11 tahun</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">KU-12</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">081234567890</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <button class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">
                                <i class="fas fa-whatsapp"></i> Ucapan
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">2</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <strong>Michelle G. Declan</strong>
                            @if(date('d') == '20')
                                <span style="background: #e91e63; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px; margin-left: 5px;">HARI INI!</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">20 April 2015</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">11 tahun</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">KU-10</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">081234567891</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <button class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">
                                <i class="fas fa-whatsapp"></i> Ucapan
                            </button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #666;">
                            @if(request('bulan'))
                                Tidak ada siswa yang berulang tahun di bulan {{ $namaBulan[request('bulan')] ?? request('bulan') }}
                            @else
                                Pilih bulan untuk melihat daftar siswa yang berulang tahun
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>
@endsection