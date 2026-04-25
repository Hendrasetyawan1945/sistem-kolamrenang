@extends('layouts.coach')
@section('title', 'Catatan Waktu')
@section('page-title', 'Catatan Waktu Latihan')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-3">
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
            <div class="col-sm-3">
                <label class="form-label small mb-1">Siswa</label>
                <select name="siswa_id" class="form-select form-select-sm">
                    <option value="">Semua Siswa</option>
                    @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" {{ $siswaId == $s->id ? 'selected' : '' }}>
                        {{ $s->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            <div class="col-sm-2 ms-auto">
                <a href="{{ route('coach.catatan-waktu.create') }}" class="btn btn-success btn-sm w-100">
                    <i class="fas fa-plus me-1"></i>Tambah
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-stopwatch me-2 text-success"></i>Daftar Catatan Waktu</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="px-4 py-3" style="font-size:12px;color:#666;">Tanggal</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Siswa</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Kelas</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Jenis Latihan</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Jarak</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Waktu</th>
                        <th class="py-3" style="font-size:12px;color:#666;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($catatanList as $c)
                    <tr>
                        <td class="px-4"><small>{{ \Carbon\Carbon::parse($c->tanggal)->format('d M Y') }}</small></td>
                        <td class="fw-semibold">{{ $c->siswa->nama }}</td>
                        <td><small>{{ $c->siswa->kelas->nama ?? '-' }}</small></td>
                        <td><small>{{ $c->jenis_latihan ?? $c->nomor_lomba ?? '-' }}</small></td>
                        <td><small>{{ isset($c->jarak) ? $c->jarak.'m' : '-' }}</small></td>
                        <td><strong class="text-success">{{ $c->waktu }}</strong></td>
                        <td>
                            <a href="{{ route('coach.catatan-waktu.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-stopwatch fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada catatan waktu
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($catatanList->hasPages())
        <div class="px-4 py-3">{{ $catatanList->links() }}</div>
        @endif
    </div>
</div>
@endsection
