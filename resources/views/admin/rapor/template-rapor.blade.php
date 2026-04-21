@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">TEMPLATE RAPOR</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Form Buat Template -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-plus-circle"></i> Buat Template Baru
    </h3>
    
    <form method="POST" action="{{ route('admin.template-rapor.store') }}" id="formTemplate">
        @csrf
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nama Template</label>
                <input type="text" name="nama_template" class="form-input" placeholder="Contoh: Template Pemula" required>
            </div>
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Untuk Kelas</label>
                <select name="kelas" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    <option value="pemula">Pemula</option>
                    <option value="menengah">Menengah</option>
                    <option value="lanjut">Lanjut</option>
                    <option value="prestasi">Prestasi</option>
                    <option value="semua">Semua Kelas</option>
                </select>
            </div>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Deskripsi</label>
            <input type="text" name="deskripsi" class="form-input" placeholder="Deskripsi singkat template">
        </div>
        
        <!-- Komponen Penilaian -->
        <div style="margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <label style="font-weight:600; font-size:14px;">Komponen Penilaian</label>
                <span id="total-bobot" style="font-size:13px; color:#666;">Total bobot: <strong id="bobot-val">0</strong>%</span>
            </div>
            
            <div id="komponen-list" style="display:grid; gap:10px;">
                <!-- Diisi via JS -->
            </div>
            
            <button type="button" onclick="tambahKomponen()" class="btn btn-secondary" style="margin-top:10px; padding:8px 16px; font-size:13px;">
                <i class="fas fa-plus"></i> Tambah Komponen
            </button>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Template Catatan Coach</label>
            <textarea name="template_catatan" class="form-input" rows="3" placeholder="Contoh: Siswa menunjukkan perkembangan yang baik dalam..."></textarea>
        </div>
        
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Template
            </button>
        </div>
    </form>
</div>

<!-- Daftar Template -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-list"></i> Daftar Template ({{ $templates->count() }})
        </h3>
    </div>

    @if($templates->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada template rapor
        </div>
    @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px; padding:20px;">
            @foreach($templates as $template)
                <div style="border:2px solid #e0e0e0; border-radius:10px; padding:20px; position:relative;">
                    <div style="position:absolute; top:12px; right:12px; display:flex; gap:6px;">
                        <span style="background:{{ $template->aktif ? '#4caf50' : '#9e9e9e' }}; color:white; padding:3px 8px; border-radius:10px; font-size:11px; font-weight:600;">
                            {{ $template->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    
                    <h4 style="margin:0 0 5px 0; color:#d32f2f; font-size:18px; font-weight:700; padding-right:80px;">
                        {{ $template->nama_template }}
                    </h4>
                    <p style="margin:0 0 15px 0; color:#666; font-size:12px;">
                        <i class="fas fa-chalkboard"></i> {{ ucfirst($template->kelas) }}
                        @if($template->deskripsi) — {{ $template->deskripsi }} @endif
                    </p>
                    
                    <!-- Komponen -->
                    <div style="margin-bottom:15px;">
                        <div style="font-size:12px; font-weight:600; color:#333; margin-bottom:8px;">Komponen Penilaian:</div>
                        @foreach($template->komponen as $komp)
                            <div style="display:flex; justify-content:space-between; padding:6px 10px; background:#f8f9fa; border-radius:6px; margin-bottom:4px; font-size:12px;">
                                <span>{{ $komp['nama'] }}</span>
                                <span style="font-weight:700; color:#d32f2f;">{{ $komp['bobot'] }}%</span>
                            </div>
                        @endforeach
                        <div style="display:flex; justify-content:space-between; padding:6px 10px; background:#e8f5e9; border-radius:6px; font-size:12px; font-weight:700; margin-top:4px;">
                            <span>Total</span>
                            <span style="color:#4caf50;">{{ collect($template->komponen)->sum('bobot') }}%</span>
                        </div>
                    </div>
                    
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:11px; color:#999;">
                            <i class="fas fa-file-alt"></i> {{ $template->rapors_count }} rapor dibuat
                        </span>
                        <form method="POST" action="{{ route('admin.template-rapor.destroy', $template) }}" style="display:inline;" onsubmit="return confirm('Hapus template ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding:5px 10px; font-size:11px; border-radius:5px; background:#f44336; color:white; border:none; cursor:pointer;">
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
    <a href="{{ route('admin.isi-rapor') }}" class="btn btn-primary">
        <i class="fas fa-file-alt"></i> Isi Rapor Siswa
    </a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="margin-left:10px;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<style>
    .form-input, .form-select {
        width:100%;
        padding:10px 12px;
        border:2px solid #e0e0e0;
        border-radius:6px;
        font-size:14px;
    }
    .form-input:focus, .form-select:focus {
        outline:none;
        border-color:#d32f2f;
    }
    textarea.form-input { resize:vertical; }
</style>

<script>
let komponenCount = 0;

function tambahKomponen(nama = '', bobot = '') {
    komponenCount++;
    const list = document.getElementById('komponen-list');
    const div = document.createElement('div');
    div.id = 'komp-' + komponenCount;
    div.style.cssText = 'display:grid; grid-template-columns:1fr 100px 40px; gap:10px; align-items:center; padding:10px; background:#f8f9fa; border-radius:6px; border:1px solid #e0e0e0;';
    div.innerHTML = `
        <input type="text" name="komponen[${komponenCount}][nama]" class="form-input" placeholder="Nama komponen (contoh: Teknik Freestyle)" value="${nama}" required>
        <input type="number" name="komponen[${komponenCount}][bobot]" class="form-input" placeholder="Bobot %" min="1" max="100" value="${bobot}" required onchange="hitungBobot()" style="text-align:center;">
        <button type="button" onclick="hapusKomponen('komp-${komponenCount}')" style="padding:8px; background:#f44336; color:white; border:none; border-radius:6px; cursor:pointer;">
            <i class="fas fa-trash"></i>
        </button>
    `;
    list.appendChild(div);
    hitungBobot();
}

function hapusKomponen(id) {
    document.getElementById(id).remove();
    hitungBobot();
}

function hitungBobot() {
    const inputs = document.querySelectorAll('[name*="[bobot]"]');
    let total = 0;
    inputs.forEach(i => total += parseInt(i.value) || 0);
    document.getElementById('bobot-val').textContent = total;
    document.getElementById('bobot-val').style.color = total === 100 ? '#4caf50' : (total > 100 ? '#f44336' : '#ff9800');
}

// Default komponen saat load
tambahKomponen('Teknik Renang', 40);
tambahKomponen('Kehadiran & Kedisiplinan', 30);
tambahKomponen('Sikap & Attitude', 30);
</script>
@endsection
