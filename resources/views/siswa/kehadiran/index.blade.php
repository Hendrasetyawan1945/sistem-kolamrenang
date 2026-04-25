@extends('layouts.siswa')

@section('title', 'Kehadiran Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check me-2"></i>Rekap Kehadiran
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Filter Bulan -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET">
                                <div class="input-group">
                                    <input type="month" name="bulan" value="{{ $bulan }}" class="form-control">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalHadir }}</h3>
                                    <p class="mb-0">Hadir</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalSakit }}</h3>
                                    <p class="mb-0">Sakit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalIzin }}</h3>
                                    <p class="mb-0">Izin</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalAlpha }}</h3>
                                    <p class="mb-0">Alpha</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Persentase Kehadiran -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4>Persentase Kehadiran</h4>
                                    <h2 class="text-{{ $persentaseKehadiran >= 80 ? 'success' : ($persentaseKehadiran >= 60 ? 'warning' : 'danger') }}">
                                        {{ $persentaseKehadiran }}%
                                    </h2>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $persentaseKehadiran >= 80 ? 'success' : ($persentaseKehadiran >= 60 ? 'warning' : 'danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $persentaseKehadiran }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Kehadiran -->
                    @if($absensiList->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensiList as $absensi)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('l') }}</td>
                                        <td>
                                            @switch($absensi->status)
                                                @case('hadir')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Hadir
                                                    </span>
                                                    @break
                                                @case('sakit')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-thermometer me-1"></i>Sakit
                                                    </span>
                                                    @break
                                                @case('izin')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-hand-paper me-1"></i>Izin
                                                    </span>
                                                    @break
                                                @case('alpha')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Alpha
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $absensi->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $absensi->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Data Kehadiran</h5>
                            @php
                                try {
                                    $displayBulan = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('F Y');
                                } catch (\Exception $e) {
                                    // Fallback jika parsing gagal
                                    $parts = explode('-', $bulan);
                                    if (count($parts) == 2) {
                                        $namaBulan = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        $bulanNum = (int)$parts[1];
                                        $tahunNum = (int)$parts[0];
                                        $displayBulan = ($namaBulan[$bulanNum] ?? 'Bulan '.$bulanNum) . ' ' . $tahunNum;
                                    } else {
                                        $displayBulan = 'bulan ini';
                                    }
                                }
                            @endphp
                            <p class="text-muted">Data kehadiran untuk {{ $displayBulan }} belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection