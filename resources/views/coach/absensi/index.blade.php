@extends('layouts.coach')
@section('title', 'Absensi')
@section('page-title', 'Absensi')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label small mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1">Kelas</label>
                <select name="kelas_id" class="form-select form-select-sm">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasSaya as $kelas)
                    <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            <div class="col-sm-2">
                <a href="{{ route('coach.absensi.create', ['tanggal' => $tanggal, 'kelas_id' => $kelasId]) }}"
                   class="btn btn-success btn-sm w-100">
                    <i class="fas fa-plus me-1"></i>Input
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-clipboard-check me-2 text-primary"></i>
        Absensi {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="px-4 py-3" style="font-size:12px;color:#666;">Nama</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Kelas</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Status</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiList as $absensi)
                    <tr>
                        <td class="px-4 fw-semibold">{{ $absensi->siswa->nama }}</td>
                        <td><small>{{ $absensi->siswa->kelas->nama ?? '-' }}</small></td>
                        <td>
                            @php $colors = ['hadir'=>'success','sakit'=>'warning','izin'=>'info','alpha'=>'danger']; @endphp
                            <span class="badge bg-{{ $colors[$absensi->status] ?? 'secondary' }}">
                                {{ ucfirst($absensi->status) }}
                            </span>
                        </td>
                        <td><small class="text-muted">{{ $absensi->keterangan ?? '-' }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada data absensi untuk tanggal ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
