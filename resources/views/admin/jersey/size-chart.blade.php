@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">SIZE CHART JERSEY</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Form Tambah Size -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-plus-circle"></i> Tambah Size Baru
    </h3>
    
    <form method="POST" action="{{ route('admin.size-chart.store') }}">
        @csrf
        
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:15px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nama Size</label>
                <input type="text" name="nama_size" class="form-input" placeholder="XS, S, M, L, XL" required>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Lebar Dada (cm)</label>
                <input type="number" name="lebar_dada" class="form-input" placeholder="40">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Panjang Badan (cm)</label>
                <input type="number" name="panjang_badan" class="form-input" placeholder="50">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Panjang Lengan (cm)</label>
                <input type="number" name="panjang_lengan" class="form-input" placeholder="15">
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:15px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tinggi Min (cm)</label>
                <input type="number" name="tinggi_badan_min" class="form-input" placeholder="120">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tinggi Max (cm)</label>
                <input type="number" name="tinggi_badan_max" class="form-input" placeholder="130">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Berat Min (kg)</label>
                <input type="number" name="berat_badan_min" class="form-input" placeholder="25">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Berat Max (kg)</label>
                <input type="number" name="berat_badan_max" class="form-input" placeholder="35">
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:15px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Umur Min</label>
                <input type="number" name="umur_min" class="form-input" placeholder="8">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Umur Max</label>
                <input type="number" name="umur_max" class="form-input" placeholder="10">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Stok Awal</label>
                <input type="number" name="stok" class="form-input" value="0" min="0">
            </div>
        </div>
        
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

<!-- Tabel Size Chart -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-ruler"></i> Daftar Size Jersey
        </h3>
    </div>

    @if($sizes->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada size chart
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 10px; text-align:center;">Size</th>
                        <th style="padding:12px 10px; text-align:center;">Lebar Dada</th>
                        <th style="padding:12px 10px; text-align:center;">Panjang Badan</th>
                        <th style="padding:12px 10px; text-align:center;">Panjang Lengan</th>
                        <th style="padding:12px 10px; text-align:center;">Tinggi Badan</th>
                        <th style="padding:12px 10px; text-align:center;">Berat Badan</th>
                        <th style="padding:12px 10px; text-align:center;">Umur</th>
                        <th style="padding:12px 10px; text-align:center;">Stok</th>
                        <th style="padding:12px 10px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sizes as $size)
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px; text-align:center;">
                                <span style="background:#d32f2f; color:white; padding:6px 12px; border-radius:20px; font-weight:700; font-size:14px;">
                                    {{ $size->nama_size }}
                                </span>
                            </td>
                            <td style="padding:10px; text-align:center;">{{ $size->lebar_dada ?? '-' }} cm</td>
                            <td style="padding:10px; text-align:center;">{{ $size->panjang_badan ?? '-' }} cm</td>
                            <td style="padding:10px; text-align:center;">{{ $size->panjang_lengan ?? '-' }} cm</td>
                            <td style="padding:10px; text-align:center;">
                                {{ $size->tinggi_badan_min ?? '-' }} - {{ $size->tinggi_badan_max ?? '-' }} cm
                            </td>
                            <td style="padding:10px; text-align:center;">
                                {{ $size->berat_badan_min ?? '-' }} - {{ $size->berat_badan_max ?? '-' }} kg
                            </td>
                            <td style="padding:10px; text-align:center;">
                                {{ $size->umur_min ?? '-' }} - {{ $size->umur_max ?? '-' }} th
                            </td>
                            <td style="padding:10px; text-align:center;">
                                <form method="POST" action="{{ route('admin.size-chart.update', $size) }}" style="display:inline-flex; gap:5px; align-items:center;">
                                    @csrf @method('PATCH')
                                    <input type="number" name="stok" value="{{ $size->stok }}" min="0" style="width:60px; padding:4px 8px; border:2px solid #e0e0e0; border-radius:4px; text-align:center;">
                                    <button type="submit" style="padding:4px 8px; background:#4caf50; color:white; border:none; border-radius:4px; cursor:pointer;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </td>
                            <td style="padding:10px; text-align:center;">
                                <form method="POST" action="{{ route('admin.size-chart.destroy', $size) }}" style="display:inline;" onsubmit="return confirm('Hapus size ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:5px 10px; font-size:11px; border-radius:5px; background:#f44336; color:white; border:none; cursor:pointer;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<style>
    .form-input {
        width:100%;
        padding:10px 12px;
        border:2px solid #e0e0e0;
        border-radius:6px;
        font-size:14px;
    }
    .form-input:focus {
        outline:none;
        border-color:#d32f2f;
    }
</style>
@endsection
