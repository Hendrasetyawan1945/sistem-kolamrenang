@extends('layouts.coach')
@section('title', 'Edit Catatan Waktu')
@section('page-title', 'Edit Catatan Waktu')

@section('content')
<div class="mb-3">
    <a href="{{ route('coach.catatan-waktu.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-edit me-2 text-primary"></i>Edit Catatan Waktu</div>
    <div class="card-body">
        <form method="POST" action="{{ route('coach.catatan-waktu.update', $catatanWaktu) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-select" required>
                    @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" {{ $catatanWaktu->siswa_id == $s->id ? 'selected' : '' }}>
                        {{ $s->nama }} ({{ $s->kelas->nama ?? '-' }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control"
                       value="{{ old('tanggal', \Carbon\Carbon::parse($catatanWaktu->tanggal)->format('Y-m-d')) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Latihan <span class="text-danger">*</span></label>
                <input type="text" name="jenis_latihan" class="form-control"
                       value="{{ old('jenis_latihan', $catatanWaktu->jenis_latihan) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jarak (meter) <span class="text-danger">*</span></label>
                <input type="number" name="jarak" class="form-control"
                       value="{{ old('jarak', $catatanWaktu->jarak) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Waktu <span class="text-danger">*</span></label>
                <input type="text" name="waktu" class="form-control"
                       value="{{ old('waktu', $catatanWaktu->waktu) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $catatanWaktu->catatan) }}</textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
