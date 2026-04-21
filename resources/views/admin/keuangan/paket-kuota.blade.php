@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">PAKET KUOTA</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Form Tambah Paket -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-plus-circle"></i> Tambah Paket Baru
    </h3>
    
    <form method="POST" action="{{ route('admin.paket-kuota.store') }}" style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:15px; align-items:end;">
        @csrf
        
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nama Paket</label>
            <input type="text" name="nama_paket" class="form-input" placeholder="Contoh: Paket 8x" required>
        </div>
        
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jumlah Pertemuan</label>
            <input type="number" name="jumlah_pertemuan" class="form-input" placeholder="8" min="1" required>
        </div>
        
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Harga (Rp)</label>
            <input type="number" name="harga" class="form-input" placeholder="500000" min="0" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
            <i class="fas fa-save"></i> Simpan
        </button>
    </form>
</div>

<!-- Daftar Paket -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-list"></i> Daftar Paket Kuota
        </h3>
    </div>

    @if($pakets->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada paket kuota
        </div>
    @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:20px; padding:20px;">
            @foreach($pakets as $paket)
                <div style="border:2px solid #e0e0e0; border-radius:10px; padding:20px; transition:all 0.3s ease; position:relative;">
                    <div style="position:absolute; top:10px; right:10px;">
                        <span style="background:#4caf50; color:white; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600;">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    </div>
                    
                    <div style="margin-bottom:15px;">
                        <h4 style="margin:0 0 5px 0; color:#d32f2f; font-size:20px; font-weight:700;">
                            {{ $paket->nama_paket }}
                        </h4>
                        <p style="margin:0; color:#666; font-size:13px;">
                            <i class="fas fa-calendar-alt"></i> {{ $paket->jumlah_pertemuan }}x Pertemuan
                        </p>
                    </div>
                    
                    <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin-bottom:15px;">
                        <div style="font-size:12px; color:#666; margin-bottom:5px;">Harga Paket</div>
                        <div style="font-size:24px; font-weight:700; color:#333;">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                        </div>
                        <div style="font-size:12px; color:#999; margin-top:5px;">
                            Rp {{ number_format($paket->harga / $paket->jumlah_pertemuan, 0, ',', '.') }} / pertemuan
                        </div>
                    </div>
                    
                    @if($paket->keterangan)
                        <div style="font-size:12px; color:#666; margin-bottom:15px; padding:10px; background:#fff3cd; border-radius:6px;">
                            <i class="fas fa-info-circle"></i> {{ $paket->keterangan }}
                        </div>
                    @endif
                    
                    <div style="display:flex; gap:8px;">
                        <button onclick="editPaket({{ $paket->id }})" class="btn btn-primary" style="flex:1; padding:8px; font-size:12px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('admin.paket-kuota.destroy', $paket) }}" style="flex:1;" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="width:100%; padding:8px; font-size:12px;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
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
