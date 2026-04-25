@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard Siswa</h2>
    <div class="text-muted">{{ date('d F Y') }}</div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Status Iuran</h6>
                        @if($iuranBulanIni && $iuranBulanIni->status == 'lunas')
                            <h4>LUNAS</h4>
                        @else
                            <h4>BELUM</h4>
                        @endif
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Kehadiran Bulan Ini</h6>
                        <h4>{{ $absensiCount }}x</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Kelas</h6>
                        <h4>{{ $jadwalKelas->nama ?? '-' }}</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-swimming-pool fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Tunggakan</h6>
                        <h4>Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h4>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Info Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%">Nama</td>
                        <td>: {{ $siswa->nama }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>: {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Umur</td>
                        <td>: {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->age }} tahun</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>: {{ $jadwalKelas->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Guru</td>
                        <td>: {{ $jadwalKelas->coach->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: 
                            <span class="badge bg-{{ $siswa->status == 'aktif' ? 'success' : ($siswa->status == 'cuti' ? 'warning' : 'danger') }}">
                                {{ ucfirst($siswa->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Jadwal Latihan</h5>
            </div>
            <div class="card-body">
                @if($jadwalKelas)
                    <div class="text-center p-4">
                        <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                        <h4>{{ $jadwalKelas->nama_kelas }}</h4>
                        
                        @if($jadwalKelas->jadwal && is_array($jadwalKelas->jadwal) && count($jadwalKelas->jadwal) > 0)
                            {{-- Jika menggunakan jadwal array --}}
                            @foreach($jadwalKelas->jadwal as $jadwal)
                                <p class="mb-1">
                                    <strong>{{ $jadwal['hari'] ?? 'Belum dijadwalkan' }}</strong><br>
                                    {{ ($jadwal['jam_mulai'] ?? '') }} {{ (isset($jadwal['jam_mulai']) && isset($jadwal['jam_selesai'])) ? '-' : '' }} {{ $jadwal['jam_selesai'] ?? '' }}
                                </p>
                            @endforeach
                        @elseif($jadwalKelas->hari || $jadwalKelas->jam_mulai)
                            {{-- Jika menggunakan field individual --}}
                            <p class="mb-1">
                                <strong>{{ $jadwalKelas->hari ?? 'Belum dijadwalkan' }}</strong><br>
                                {{ $jadwalKelas->jam_mulai ?? '' }} {{ ($jadwalKelas->jam_mulai && $jadwalKelas->jam_selesai) ? '-' : '' }} {{ $jadwalKelas->jam_selesai ?? '' }}
                            </p>
                        @else
                            <p class="mb-1 text-muted">Jadwal belum diatur</p>
                        @endif
                        
                        <p class="text-muted">{{ $jadwalKelas->kolam->nama ?? 'Kolam Utama' }}</p>
                        
                        @if($jadwalKelas->coach)
                            <small class="text-muted">Pelatih: {{ $jadwalKelas->coach->nama }}</small>
                        @endif
                    </div>
                @else
                    <div class="text-center p-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada jadwal kelas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Menu Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('siswa.iuran.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-money-bill-wave mb-2"></i><br>
                            Riwayat Iuran
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('siswa.rapor.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-file-alt mb-2"></i><br>
                            Rapor Saya
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('siswa.prestasi.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-trophy mb-2"></i><br>
                            Prestasi
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('siswa.kehadiran.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-calendar-check mb-2"></i><br>
                            Kehadiran
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('siswa.jersey.index') }}" class="btn btn-outline-danger w-100">
                            <i class="fas fa-tshirt mb-2"></i><br>
                            Jersey
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection