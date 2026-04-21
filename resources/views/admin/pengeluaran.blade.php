@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-minus-circle"></i>
    </div>
    <h1 class="club-title">Input Pengeluaran</h1>
</div>

<div class="dashboard-card" style="max-width: 600px; margin: 0 auto;">
    <h3 class="card-title">Form Input Pengeluaran</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Tanggal</label>
            <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jenis Pengeluaran</label>
            <select name="jenis" class="form-select" required>
                <option value="">Pilih jenis pengeluaran</option>
                <option value="operasional">Operasional Kolam</option>
                <option value="gaji_coach">Gaji Coach</option>
                <option value="peralatan">Peralatan Renang</option>
                <option value="maintenance">Maintenance</option>
                <option value="listrik">Listrik & Air</option>
                <option value="transport">Transport</option>
                <option value="konsumsi">Konsumsi</option>
                <option value="administrasi">Administrasi</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Deskripsi</label>
            <input type="text" name="deskripsi" class="form-input" placeholder="Deskripsi pengeluaran" required>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jumlah</label>
            <input type="number" name="jumlah" class="form-input" placeholder="0" min="0" step="1000" required>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Metode Pembayaran</label>
            <select name="metode" class="form-select" required>
                <option value="">Pilih metode pembayaran</option>
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer Bank</option>
                <option value="ewallet">E-Wallet</option>
                <option value="kartu">Kartu Debit/Kredit</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Penerima/Vendor</label>
            <input type="text" name="penerima" class="form-input" placeholder="Nama penerima atau vendor" required>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Keterangan</label>
            <textarea name="keterangan" class="form-input" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nomor Nota/Bukti</label>
            <input type="text" name="nomor_nota" class="form-input" placeholder="Nomor nota atau bukti pembayaran">
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end; margin-top: 20px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Pengeluaran
            </button>
        </div>
    </form>
</div>

<style>
    .form-input, .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #d32f2f;
    }
    
    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }
    
    input[type="number"] {
        text-align: right;
    }
</style>
@endsection