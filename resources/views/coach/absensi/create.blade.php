@extends('layouts.coach')
@section('title', 'Input Absensi')
@section('page-title', 'Input Absensi')

@section('content')
<div class="mb-3">
    <a href="{{ route('coach.absensi.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

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
                <button class="btn btn-outline-primary btn-sm w-100">Muat Siswa</button>
            </div>
        </form>
    </div>
</div>

<form method="POST" action="{{ route('coach.absensi.store') }}">
    @csrf
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

    <div class="card">
        <div class="card-header"><i class="fas fa-clipboard-check me-2 text-success"></i>
            Input Absensi — {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3" style="font-size:12px;color:#666;">Nama Siswa</th>
                            <th class="py-3" style="font-size:12px;color:#666;">Kelas</th>
                            <th class="py-3" style="font-size:12px;color:#666;">Status</th>
                            <th class="py-3" style="font-size:12px;color:#666;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $siswa->nama }}</td>
                            <td><small>{{ $siswa->kelas->nama ?? '-' }}</small></td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach(['hadir'=>'success','sakit'=>'warning','izin'=>'info','alpha'=>'danger'] as $val => $color)
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio"
                                               name="absensi[{{ $siswa->id }}]"
                                               id="abs_{{ $siswa->id }}_{{ $val }}"
                                               value="{{ $val }}"
                                               {{ $val === 'hadir' ? 'checked' : '' }}>
                                        <label class="form-check-label text-{{ $color }} small"
                                               for="abs_{{ $siswa->id }}_{{ $val }}">
                                            {{ ucfirst($val) }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <input type="text" name="keterangan[{{ $siswa->id }}]"
                                       class="form-control form-control-sm" placeholder="Opsional" style="width:160px;">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Pilih kelas terlebih dahulu
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($siswaList->count() > 0)
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i>Simpan Absensi
            </button>
        </div>
        @endif
    </div>
</form>
@endsection
