@extends('layouts.siswa')

@section('title', 'Rapor Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt me-2"></i>Rapor Saya
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Filter -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET">
                                <div class="input-group">
                                    <select name="bulan" class="form-select">
                                        <option value="">Semua Bulan</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            @php
                                                $namaBulan = [
                                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                ];
                                            @endphp
                                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                                {{ $namaBulan[$i] }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($raporList->count() > 0)
                        <div class="row">
                            @foreach($raporList as $rapor)
                            @php
                                $nilaiRataRata = 0;
                                if ($rapor->nilai && count($rapor->nilai) > 0) {
                                    $totalNilai = collect($rapor->nilai)->sum('nilai');
                                    $nilaiRataRata = round($totalNilai / count($rapor->nilai), 1);
                                }
                                $persentaseKehadiran = $rapor->total_pertemuan > 0 ? round(($rapor->kehadiran / $rapor->total_pertemuan) * 100, 1) : 0;
                                $namaBulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-calendar"></i> {{ $namaBulan[$rapor->bulan] }} {{ $rapor->tahun }}
                                        </h6>
                                        <span class="badge bg-{{ $rapor->status === 'selesai' ? 'success' : 'warning' }} float-end">
                                            {{ $rapor->status === 'selesai' ? 'Selesai' : 'Draft' }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <div class="border-end">
                                                    <h4 class="text-primary mb-0">{{ $nilaiRataRata }}</h4>
                                                    <small class="text-muted">Nilai Rata-rata</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-success mb-0">{{ $persentaseKehadiran }}%</h4>
                                                <small class="text-muted">Kehadiran</small>
                                            </div>
                                        </div>
                                        
                                        @if($rapor->catatan_coach)
                                        <div class="mb-2">
                                            <small class="text-muted">Catatan Pelatih:</small>
                                            <p class="small mb-0">{{ Str::limit($rapor->catatan_coach, 80) }}</p>
                                        </div>
                                        @endif
                                        
                                        <div class="text-center">
                                            <a href="{{ route('siswa.rapor.show', $rapor) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-footer text-muted small">
                                        <i class="fas fa-clock"></i> {{ $rapor->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($raporList->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $raporList->appends(request()->query())->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada rapor</h5>
                            <p class="text-muted">Rapor Anda akan muncul di sini setelah pelatih membuatnya</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection