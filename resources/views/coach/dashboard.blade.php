@extends('layouts.coach')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100" style="border-left:4px solid #2e6da4;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e8f0fb;flex-shrink:0;">
                    <i class="fas fa-chalkboard-teacher text-primary fa-lg"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-primary">{{ $kelasSaya->count() }}</div>
                    <div class="text-muted small">Kelas Dipegang</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100" style="border-left:4px solid #198754;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e8f8ef;flex-shrink:0;">
                    <i class="fas fa-users text-success fa-lg"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-success">{{ $totalSiswa }}</div>
                    <div class="text-muted small">Total Siswa</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100" style="border-left:4px solid #0dcaf0;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e0f8fd;flex-shrink:0;">
                    <i class="fas fa-calendar-day text-info fa-lg"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-info">{{ $jadwalHariIni->count() }}</div>
                    <div class="text-muted small">Jadwal Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100" style="border-left:4px solid #ffc107;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#fff8e1;flex-shrink:0;">
                    <i class="fas fa-clipboard-check text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="fs-3 fw-bold text-warning">{{ $absensiMingguIni }}</div>
                    <div class="text-muted small">Absensi Minggu Ini</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="fas fa-bolt me-2 text-warning"></i>Aksi Cepat</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('coach.absensi.create') }}"
                           class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="fas fa-clipboard-check fa-lg"></i>
                            <span class="small">Input Absensi</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('coach.catatan-waktu.create') }}"
                           class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="fas fa-stopwatch fa-lg"></i>
                            <span class="small">Catatan Waktu</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('coach.rapor.create') }}"
                           class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="fas fa-file-alt fa-lg"></i>
                            <span class="small">Buat Rapor</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('coach.siswa.index') }}"
                           class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1">
                            <i class="fas fa-users fa-lg"></i>
                            <span class="small">Data Siswa</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Kelas -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-chalkboard me-2 text-primary"></i>Kelas Saya</div>
            <div class="card-body p-0">
                @forelse($kelasSaya as $kelas)
                <div class="d-flex align-items-center justify-content-between px-4 py-3"
                     style="border-bottom:1px solid #f5f5f5;">
                    <div>
                        <div class="fw-semibold">{{ $kelas->nama }}</div>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>{{ $kelas->hari }},
                            {{ $kelas->jam_mulai }} – {{ $kelas->jam_selesai }}
                        </small>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        {{ $kelas->siswas->count() }} siswa
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-chalkboard fa-2x mb-2 opacity-25 d-block"></i>
                    Belum ada kelas
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-calendar-day me-2 text-info"></i>Jadwal Hari Ini</div>
            <div class="card-body">
                @forelse($jadwalHariIni as $jadwal)
                <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded"
                     style="background:#f0f7ff;">
                    <div class="text-center" style="min-width:50px;">
                        <div class="fw-bold text-primary small">{{ $jadwal->jam_mulai }}</div>
                        <div class="text-muted" style="font-size:10px;">s/d</div>
                        <div class="fw-bold text-primary small">{{ $jadwal->jam_selesai }}</div>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $jadwal->nama }}</div>
                        <small class="text-muted">{{ $jadwal->siswas->count() }} siswa terdaftar</small>
                    </div>
                    <a href="{{ route('coach.absensi.create', ['kelas_id' => $jadwal->id]) }}"
                       class="btn btn-sm btn-primary ms-auto">
                        <i class="fas fa-clipboard-check"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2 opacity-25 d-block"></i>
                    Tidak ada jadwal hari ini
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
