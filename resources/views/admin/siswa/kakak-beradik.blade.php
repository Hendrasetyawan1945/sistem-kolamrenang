@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-user-friends"></i>
    </div>
    <h1 class="club-title">Kakak Beradik</h1>
</div>

<!-- Data Table -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 20px;">
                <span>No</span>
                <span>Nama Keluarga</span>
                <span>Jumlah Anak</span>
                <span>Nama Siswa</span>
                <span>Kelas</span>
                <span>Diskon</span>
                <span>Aksi</span>
            </div>
            <div>
                <input type="text" placeholder="Search..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    <div class="table-content">
        <p>Tidak ada data keluarga dengan lebih dari 1 anak</p>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>
@endsection