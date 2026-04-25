@extends('layouts.coach')

@section('content')
<div class="coach-header">
    <div class="coach-logo"><i class="fas fa-edit"></i></div>
    <h1 class="coach-title">Edit Pembayaran</h1>
</div>

<!-- Back Button -->
<div style="margin-bottom:20px;">
    <a href="{{ route('coach.pembayaran.index') }}" style="background:#6c757d;color:white;text-decoration:none;padding:8px 16px;border-radius:6px;font-size:14px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

@if($errors->any())
<div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #f5c6cb;">
    <i class="fas fa-exclamation-triangle"></i> 
    <ul style="margin:5px 0 0 20px;padding:0;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Status Info -->
@if($pembayaran->status === 'rejected')
<div style="background:#fff3cd;color:#856404;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #ffeaa7;">
    <div style="font-weight:600;margin-bottom:8px;">
        <i class="fas fa-exclamation-triangle"></i> Pembayaran Ditolak
    </div>
    @if($pembayaran->rejection_reason)
    <div style="background:white;padding:10px;border-radius:4px;font-style:italic;margin-top:8px;">
        <strong>Alasan:</strong> {{ $pembayaran->rejection_reason }}
    </div>
    @endif
    <div style="margin-top:8px;font-size:13px;">
        Silakan perbaiki data pembayaran dan submit ulang untuk approval.
    </div>
</div>
@elseif($pembayaran->status === 'pending')
<div style="background:#e3f2fd;color:#1565c0;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #90caf9;">
    <i class="fas fa-info-circle"></i> Pembayaran ini sedang menunggu approval. Anda masih bisa mengedit sebelum diapprove.
</div>
@endif

<!-- Edit Form -->
<div style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0;color:#333;">Edit Data Pembayaran</h3>
    
    <form method="POST" action="{{ route('coach.pembayaran.update', $pembayaran) }}">
        @csrf
        @method('PUT')
        
        <!-- Siswa Info (Read-only) -->
        <div style="background:#f8f9fa;padding:20px;border-radius:8px;margin-bottom:20px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div>
                    <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Nama Siswa</label>
                    <div style="font-size:16px;font-weight:600;">{{ $pembayaran->siswa->nama }}</div>
                    <div style="font-size:12px;color:#666;">Kelas: {{ ucfirst($pembayaran->siswa->kelas) }}</div>
                </div>
                <div>
                    <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Periode</label>
                    <div style="font-size:16px;font-weight:600;">
                        {{ \Carbon\Carbon::create($pembayaran->tahun, $pembayaran->bulan)->format('F Y') }}
                    </div>
                    <div style="font-size:12px;color:#666;">{{ ucfirst(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}</div>
                </div>
            </div>
        </div>
        
        <!-- Editable Fields -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div>
                <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">
                    Jumlah Bayar (Rp) <span style="color:red">*</span>
                </label>
                <input type="number" 
                       name="jumlah" 
                       value="{{ old('jumlah', $pembayaran->jumlah) }}" 
                       required 
                       min="0"
                       step="1000"
                       style="width:100%;padding:12px;border:2px solid #e0e0e0;border-radius:6px;font-size:14px;">
                <small style="color:#666;font-size:12px;">Masukkan jumlah pembayaran dalam Rupiah</small>
            </div>
            
            <div>
                <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">
                    Tanggal Bayar <span style="color:red">*</span>
                </label>
                <input type="date" 
                       name="tanggal_bayar" 
                       value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar->format('Y-m-d')) }}" 
                       required 
                       style="width:100%;padding:12px;border:2px solid #e0e0e0;border-radius:6px;font-size:14px;">
            </div>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">
                Metode Pembayaran <span style="color:red">*</span>
            </label>
            <select name="metode_pembayaran" 
                    required 
                    style="width:100%;padding:12px;border:2px solid #e0e0e0;border-radius:6px;font-size:14px;">
                <option value="">Pilih Metode</option>
                <option value="Tunai" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="Transfer" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="QRIS" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
            </select>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">
                Keterangan
            </label>
            <textarea name="keterangan" 
                      rows="3" 
                      style="width:100%;padding:12px;border:2px solid #e0e0e0;border-radius:6px;resize:vertical;font-size:14px;" 
                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
            <small style="color:#666;font-size:12px;">Tambahkan catatan jika diperlukan</small>
        </div>
        
        <!-- Info Box -->
        <div style="background:#e3f2fd;padding:15px;border-radius:6px;margin-bottom:20px;border-left:4px solid #2196f3;">
            <div style="font-size:13px;color:#1565c0;">
                <i class="fas fa-info-circle"></i> 
                <strong>Catatan:</strong> Setelah Anda update pembayaran ini, status akan kembali menjadi "Pending" dan menunggu approval dari admin.
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="display:flex;gap:15px;justify-content:flex-end;padding-top:20px;border-top:1px solid #e0e0e0;">
            <a href="{{ route('coach.pembayaran.index') }}" 
               style="padding:12px 24px;border:2px solid #ddd;background:white;border-radius:6px;text-decoration:none;color:#333;font-weight:600;">
                Batal
            </a>
            <button type="submit" 
                    style="padding:12px 24px;background:#d32f2f;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;font-size:14px;">
                <i class="fas fa-save"></i> Update & Submit untuk Approval
            </button>
        </div>
    </form>
</div>

<!-- Payment History -->
<div style="background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-top:20px;">
    <h4 style="margin:0 0 15px 0;color:#333;">Riwayat Status</h4>
    
    <div style="position:relative;">
        <!-- Timeline -->
        <div style="position:absolute;left:15px;top:0;bottom:0;width:2px;background:#e0e0e0;"></div>
        
        <!-- Created -->
        <div style="position:relative;padding-left:40px;margin-bottom:20px;">
            <div style="position:absolute;left:6px;top:2px;width:18px;height:18px;background:#2196f3;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-plus" style="font-size:8px;color:white;"></i>
            </div>
            <div style="font-weight:600;font-size:14px;">Pembayaran Dibuat</div>
            <div style="font-size:12px;color:#666;">{{ $pembayaran->created_at->format('d F Y, H:i') }}</div>
            <div style="font-size:12px;color:#666;">oleh {{ $pembayaran->inputBy->name ?? '-' }}</div>
        </div>
        
        <!-- Current Status -->
        @if($pembayaran->approved_at)
        <div style="position:relative;padding-left:40px;margin-bottom:20px;">
            <div style="position:absolute;left:6px;top:2px;width:18px;height:18px;background:{{ $pembayaran->status === 'approved' ? '#4caf50' : '#f44336' }};border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-{{ $pembayaran->status === 'approved' ? 'check' : 'times' }}" style="font-size:8px;color:white;"></i>
            </div>
            <div style="font-weight:600;font-size:14px;">
                {{ $pembayaran->status === 'approved' ? 'Pembayaran Disetujui' : 'Pembayaran Ditolak' }}
            </div>
            <div style="font-size:12px;color:#666;">{{ $pembayaran->approved_at->format('d F Y, H:i') }}</div>
            <div style="font-size:12px;color:#666;">oleh {{ $pembayaran->approvedBy->name ?? '-' }}</div>
        </div>
        @endif
        
        <!-- Editing Now -->
        <div style="position:relative;padding-left:40px;">
            <div style="position:absolute;left:6px;top:2px;width:18px;height:18px;background:#ff9800;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-edit" style="font-size:8px;color:white;"></i>
            </div>
            <div style="font-weight:600;font-size:14px;">Sedang Diedit</div>
            <div style="font-size:12px;color:#666;">Sekarang</div>
        </div>
    </div>
</div>

<style>
input:focus, select:focus, textarea:focus {
    outline:none;
    border-color:#d32f2f !important;
}
</style>
@endsection
