@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-check"></i></div>
    <h1 class="club-title">Siswa Aktif</h1>
</div>

@include('admin.siswa._alert')

<div class="data-table">
    <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-weight:600;">Total: {{ $siswas->count() }} siswa aktif</span>
        <a href="{{ route('admin.daftar-baru') }}" class="btn btn-primary" style="padding:8px 16px; font-size:13px;">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
    </div>

    @if($siswas->isEmpty())
        <div class="table-content">
            <i class="fas fa-inbox" style="font-size:40px; color:#ccc; display:block; margin-bottom:10px;"></i>
            <p>Belum ada siswa aktif</p>
        </div>
    @else
        @include('admin.siswa._table', [
            'siswas'       => $siswas,
            'extraActions' => [
                ['label' => '<i class="fas fa-pause"></i> Cuti',     'status' => 'cuti',     'color' => '#ff9800'],
                ['label' => '<i class="fas fa-times"></i> Nonaktif', 'status' => 'nonaktif', 'color' => '#9e9e9e'],
            ]
        ])
    @endif
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection
