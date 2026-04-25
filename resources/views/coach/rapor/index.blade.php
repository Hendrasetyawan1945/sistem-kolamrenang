@extends('layouts.coach')
@section('title', 'Rapor Siswa')
@section('page-title', 'Rapor Siswa')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small mb-1">Bulan</label>
                <input type="month" name="bulan" class="form-control form-control-sm" value="{{ $bulan }}">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1">Kelas</label>
                <select name="kelas_id" class="form-select form-select-sm">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasSaya as $kelas)
                    <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            <div class="col-sm-2 ms-auto">
                <a href="{{ route('coach.rapor.create', ['bulan' => $bulan, 'kelas_id' => $kelasId]) }}"
                   class="btn btn-success btn-sm w-100">
                    <i class="fas fa-plus me-1"></i>Buat Rapor
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-file-alt me-2 text-info"></i>
        Rapor — {{ $namaBulan[$bulanInt] ?? 'Bulan '.$bulanInt }} {{ $tahunInt }}
    </div>
    <div class="card-body">
        @if($raporList->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Nilai Rata-rata</th>
                            <th>Kehadiran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($raporList as $rapor)
                        @php
                            $nilaiRataRata = 0;
                            if ($rapor->nilai && count($rapor->nilai) > 0) {
                                $totalNilai = collect($rapor->nilai)->sum('nilai');
                                $nilaiRataRata = round($totalNilai / count($rapor->nilai), 1);
                            }
                            $persentaseKehadiran = $rapor->total_pertemuan > 0 ? round(($rapor->kehadiran / $rapor->total_pertemuan) * 100, 1) : 0;
                            $namaBulanDisplay = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                            ];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $rapor->siswa->nama }}</strong>
                            </td>
                            <td>{{ $rapor->siswa->kelas }}</td>
                            <td>
                                <small>{{ ($namaBulanDisplay[$rapor->bulan] ?? 'Bln '.$rapor->bulan) }} {{ $rapor->tahun }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $rapor->status === 'selesai' ? 'success' : 'warning' }}">
                                    {{ $rapor->status === 'selesai' ? 'Selesai' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                @if($nilaiRataRata > 0)
                                    <span class="badge bg-{{ $nilaiRataRata >= 80 ? 'success' : ($nilaiRataRata >= 60 ? 'warning' : 'danger') }}">
                                        {{ $nilaiRataRata }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{ $rapor->kehadiran }}/{{ $rapor->total_pertemuan }} 
                                <small class="text-muted">({{ $persentaseKehadiran }}%)</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('coach.rapor.show', $rapor) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('coach.rapor.edit', $rapor) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada rapor untuk periode ini</h5>
                <p class="text-muted">Silakan buat rapor baru untuk siswa di kelas Anda</p>
                <a href="{{ route('coach.rapor.create', ['bulan' => $bulan, 'kelas_id' => $kelasId]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Buat Rapor Baru
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
