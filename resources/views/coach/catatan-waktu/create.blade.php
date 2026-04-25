@extends('layouts.coach')
@section('title', 'Tambah Catatan Waktu')
@section('page-title', 'Tambah Catatan Waktu')

@section('content')
<div class="mb-3">
    <a href="{{ route('coach.catatan-waktu.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header"><i class="fas fa-stopwatch me-2 text-success"></i>Form Catatan Waktu</div>
    <div class="card-body">
        <form method="POST" action="{{ route('coach.catatan-waktu.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-select" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->nama }} ({{ $s->kelas->nama ?? '-' }})
                    </option>
                    @endforeach
                </select>
                @error('siswa_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Latihan <span class="text-danger">*</span></label>
                <input type="text" name="jenis_latihan" class="form-control"
                       placeholder="cth: Freestyle, Breaststroke" value="{{ old('jenis_latihan') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jarak (meter) <span class="text-danger">*</span></label>
                <input type="number" name="jarak" class="form-control" placeholder="cth: 50" value="{{ old('jarak') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Waktu <span class="text-danger">*</span></label>
                <input type="text" name="waktu" class="form-control"
                       placeholder="cth: 01:23.45" value="{{ old('waktu') }}" required>
                <div class="form-text">Format: MM:SS.ms atau SS.ms</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"
                          placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
