@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-times"></i></div>
    <h1 class="club-title">Siswa Nonaktif</h1>
</div>

@include('admin.siswa._alert')

<div class="data-table">
    <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
        <span style="font-weight:600;">Total: {{ $siswas->count() }} siswa nonaktif</span>
    </div>

    @if($siswas->isEmpty())
        <div class="table-content">
            <i class="fas fa-inbox" style="font-size:40px; color:#ccc; display:block; margin-bottom:10px;"></i>
            <p>Tidak ada siswa nonaktif</p>
        </div>
    @else
        @include('admin.siswa._table', [
            'siswas'       => $siswas,
            'extraActions' => [
                ['label' => '<i class="fas fa-check"></i> Aktifkan', 'status' => 'aktif', 'color' => '#4caf50'],
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
