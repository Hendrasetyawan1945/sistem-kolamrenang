@extends('layouts.coach')
@section('title', 'Edit Rapor')
@section('page-title', 'Edit Rapor')

@section('content')
<!-- Header -->
<div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:white;padding:22px 28px;border-radius:12px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
    <div>
        <a href="{{ route('coach.rapor.index', ['bulan'=>$rapor->tahun.'-'.str_pad($rapor->bulan, 2, '0', STR_PAD_LEFT)]) }}" style="color:rgba(255,255,255,.7);font-size:12px;text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rapor
        </a>
        <h1 style="margin:6px 0 4px 0;font-size:22px;font-weight:700;">Edit Rapor: {{ $rapor->siswa->nama }}</h1>
        <p style="margin:0;opacity:.85;font-size:13px;">
            <i class="fas fa-chalkboard"></i> {{ $rapor->siswa->kelas }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $namaBulan[$rapor->bulan] }} {{ $rapor->tahun }}
        </p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('coach.rapor.show', $rapor) }}" style="background:rgba(255,255,255,.2);color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;">
            <i class="fas fa-eye"></i> Lihat Rapor
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Form Edit Rapor menggunakan Component -->
<x-rapor-form 
    :siswa="$rapor->siswa"
    :rapor="$rapor"
    :templates="$templates"
    :hadir="$hadir"
    :totalPertemuan="$totalPertemuan"
    :bulan="$rapor->bulan"
    :tahun="$rapor->tahun"
    :namaBulan="$namaBulan"
    userRole="coach"
    :personalBest="$personalBest"
    :actionRoute="route('coach.rapor.update', $rapor)"
    method="PUT"
/>

@endsection