@extends('layouts.coach')
@section('title', 'Buat Rapor')
@section('page-title', 'Buat Rapor Siswa')

@section('content')
<div class="mb-3">
    <a href="{{ route('coach.rapor.index', ['bulan' => $bulan]) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-file-alt me-2 text-info"></i>Pilih Siswa untuk Membuat Rapor
        <div class="text-muted small">{{ $namaBulan[$bulanInt] }} {{ $tahunInt }}</div>
    </div>
    <div class="card-body">
        @if($siswaList->count() > 0)
            <div class="row">
                @foreach($siswaList as $siswa)
                    @php
                        // Cek apakah rapor sudah ada
                        $existingRapor = \App\Models\Rapor::where('siswa_id', $siswa->id)
                                                         ->where('bulan', $bulanInt)
                                                         ->where('tahun', $tahunInt)
                                                         ->first();
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 {{ $existingRapor ? 'border-success' : 'border-primary' }}">
                            <div class="card-body">
                                <h6 class="card-title">{{ $siswa->nama }}</h6>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-chalkboard"></i> {{ $siswa->kelas }}
                                    @if($siswa->status)
                                        <br><span class="badge bg-{{ $siswa->status === 'aktif' ? 'success' : 'warning' }} mt-1">
                                            {{ ucfirst($siswa->status) }}
                                        </span>
                                    @endif
                                </p>
                                
                                @if($existingRapor)
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('coach.rapor.show', $existingRapor) }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Rapor
                                        </a>
                                        <a href="{{ route('coach.rapor.edit', $existingRapor) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-edit"></i> Edit Rapor
                                        </a>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle"></i> 
                                            Status: {{ ucfirst($existingRapor->status) }}
                                        </small>
                                    </div>
                                @else
                                    <div class="d-grid">
                                        <a href="{{ route('coach.rapor.siswa', $siswa) }}?bulan={{ $bulanInt }}&tahun={{ $tahunInt }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Buat Rapor
                                        </a>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> Belum ada rapor
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada siswa di kelas Anda</h5>
                <p class="text-muted">Silakan hubungi admin untuk menambahkan siswa ke kelas Anda</p>
            </div>
        @endif
    </div>
</div>

<!-- Info Template -->
@if($templates->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <i class="fas fa-template me-2 text-warning"></i>Template Rapor Tersedia
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($templates as $template)
            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-alt text-primary me-2"></i>
                    <div>
                        <strong>{{ $template->nama_template }}</strong>
                        <br><small class="text-muted">{{ $template->kelas }} - {{ $template->deskripsi }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="alert alert-info mt-3 mb-0">
            <i class="fas fa-info-circle me-2"></i>
            <small>Template akan tersedia saat Anda membuat rapor untuk mempercepat proses penilaian</small>
        </div>
    </div>
</div>
@endif

@endsection
