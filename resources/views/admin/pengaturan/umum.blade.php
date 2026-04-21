@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-cog"></i>
    </div>
    <h1 class="club-title">Pengaturan Umum</h1>
</div>

<!-- Informasi Club -->
<div class="dashboard-card" style="margin-bottom: 30px;">
    <h3 class="card-title">Informasi Club</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Club</label>
                <input type="text" name="nama_club" class="form-input" value="Youth Swimming Club" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Email Club</label>
                <input type="email" name="email_club" class="form-input" value="info@youthswimming.club" required>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Telepon</label>
                <input type="tel" name="telepon" class="form-input" value="021-12345678" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">WhatsApp</label>
                <input type="tel" name="whatsapp" class="form-input" value="081234567890" required>
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Alamat</label>
            <textarea name="alamat" class="form-input" rows="3" required>Jl. Renang Indah No. 123, Jakarta Selatan 12345</textarea>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Informasi
            </button>
        </div>
    </form>
</div>

<!-- Pengaturan Sistem -->
<div class="dashboard-card" style="margin-bottom: 30px;">
    <h3 class="card-title">Pengaturan Sistem</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Zona Waktu</label>
                <select name="timezone" class="form-select">
                    <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                    <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                    <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Format Tanggal</label>
                <select name="date_format" class="form-select">
                    <option value="d/m/Y" selected>DD/MM/YYYY</option>
                    <option value="Y-m-d">YYYY-MM-DD</option>
                    <option value="m/d/Y">MM/DD/YYYY</option>
                </select>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Mata Uang</label>
                <select name="currency" class="form-select">
                    <option value="IDR" selected>Rupiah (IDR)</option>
                    <option value="USD">US Dollar (USD)</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Bahasa Sistem</label>
                <select name="language" class="form-select">
                    <option value="id" selected>Bahasa Indonesia</option>
                    <option value="en">English</option>
                </select>
            </div>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<!-- Pengaturan Notifikasi -->
<div class="dashboard-card" style="margin-bottom: 30px;">
    <h3 class="card-title">Pengaturan Notifikasi</h3>
    
    <form action="#" method="POST" style="display: grid; gap: 20px;">
        @csrf
        
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0; color: #333;">Email Notifikasi</h4>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Kirim notifikasi via email untuk pembayaran dan reminder</p>
                </div>
                <label class="switch">
                    <input type="checkbox" name="email_notification" checked>
                    <span class="slider"></span>
                </label>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0; color: #333;">WhatsApp Notifikasi</h4>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Kirim reminder pembayaran via WhatsApp</p>
                </div>
                <label class="switch">
                    <input type="checkbox" name="whatsapp_notification" checked>
                    <span class="slider"></span>
                </label>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0; color: #333;">Backup Otomatis</h4>
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Backup database secara otomatis setiap hari</p>
                </div>
                <label class="switch">
                    <input type="checkbox" name="auto_backup" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Notifikasi
            </button>
        </div>
    </form>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<style>
    .form-input, .form-select {
        width: 100%;
        padding: 8px 12px;
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
    
    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #d32f2f;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
@endsection