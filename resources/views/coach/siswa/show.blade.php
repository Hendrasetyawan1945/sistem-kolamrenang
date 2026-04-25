@extends('layouts.coach')
@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa')

@section('content')
<div class="mb-3">
    <a href="{{ route('coach.siswa.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;">
                    <i class="fas fa-user fa-2x text-primary"></i>
                </div>
                <h5 class="mb-1">{{ $siswa->nama }}</h5>
                <span class="badge {{ $siswa->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($siswa->status) }}
                </span>
                <hr>
                <div class="text-start">
                    <div class="mb-2"><small class="text-muted">Kelas</small><br>
                        <strong>{{ $siswa->kelas ?? '-' }}</strong></div>
                    <div class="mb-2"><small class="text-muted">Tanggal Lahir</small><br>
                        <strong>{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y') : '-' }}</strong></div>
                    <div class="mb-2"><small class="text-muted">No. Induk</small><br>
                        <strong>{{ $siswa->nomor_induk ?? '-' }}</strong></div>
                    <div><small class="text-muted">No. HP / Ortu</small><br>
                        <strong>{{ $siswa->no_hp ?? '-' }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Catatan Waktu Terakhir -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-stopwatch me-2 text-success"></i>Catatan Waktu Terakhir</div>
            <div class="card-body p-0">
                @php $catatanList = $siswa->catatanWaktuLatihans()->orderBy('tanggal','desc')->limit(5)->get(); @endphp
                @forelse($catatanList as $c)
                <div class="d-flex justify-content-between align-items-center px-4 py-2"
                     style="border-bottom:1px solid #f5f5f5;">
                    <div>
                        <div class="fw-semibold small">{{ $c->nomor_lomba ?? ($c->jenis_latihan ?? '-') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($c->tanggal)->format('d M Y') }}</small>
                    </div>
                    <span class="fw-bold text-success">{{ $c->waktu }}</span>
                </div>
                @empty
                <div class="text-center py-3 text-muted small">Belum ada catatan waktu</div>
                @endforelse
            </div>
        </div>

        <!-- Absensi Bulan Ini -->
        <div class="card">
            <div class="card-header"><i class="fas fa-calendar-check me-2 text-info"></i>Absensi Bulan Ini</div>
            <div class="card-body">
                @php
                    $absensi = $siswa->absensis()
                        ->whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year)
                        ->get();
                    $hadir = $absensi->where('status','hadir')->count();
                    $sakit = $absensi->where('status','sakit')->count();
                    $izin  = $absensi->where('status','izin')->count();
                    $alpha = $absensi->where('status','alpha')->count();
                @endphp
                <div class="row text-center g-2">
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#e8f8ef;">
                            <div class="fw-bold text-success fs-4">{{ $hadir }}</div>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#fff8e1;">
                            <div class="fw-bold text-warning fs-4">{{ $sakit }}</div>
                            <small class="text-muted">Sakit</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#e0f7fa;">
                            <div class="fw-bold text-info fs-4">{{ $izin }}</div>
                            <small class="text-muted">Izin</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#fce4ec;">
                            <div class="fw-bold text-danger fs-4">{{ $alpha }}</div>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
