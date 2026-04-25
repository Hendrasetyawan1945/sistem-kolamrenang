@extends('layouts.coach')
@section('title', 'Detail Rapor')
@section('page-title', 'Detail Rapor')

@section('content')
<!-- Tombol Kembali -->
<div style="margin-bottom:20px;">
    <a href="{{ route('coach.rapor.index', ['bulan' => $rapor->tahun.'-'.str_pad($rapor->bulan, 2, '0', STR_PAD_LEFT)]) }}" 
        style="background:#6c757d;color:white;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:13px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rapor
    </a>
</div>

<!-- Tampilan Rapor menggunakan Component -->
<x-rapor-display 
    :rapor="$rapor"
    :showActions="true"
    userRole="coach"
    :namaBulan="$namaBulan"
/>

@endsection