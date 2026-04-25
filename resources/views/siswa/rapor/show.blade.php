@extends('layouts.siswa')

@section('title', 'Detail Rapor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Tombol Kembali -->
            <div style="margin-bottom:20px;">
                <a href="{{ route('siswa.rapor.index') }}" 
                    style="background:#6c757d;color:white;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:13px;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rapor
                </a>
            </div>

            <!-- Tampilan Rapor menggunakan Component -->
            @php
                $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp
            
            <x-rapor-display 
                :rapor="$rapor"
                :showActions="false"
                userRole="siswa"
                :namaBulan="$namaBulan"
            />
        </div>
    </div>
</div>
@endsection