@extends('layouts.coach')

@section('content')
<div class="coach-header">
    <div class="coach-logo"><i class="fas fa-receipt"></i></div>
    <h1 class="coach-title">Detail Pembayaran</h1>
</div>

<!-- Back Button -->
<div style="margin-bottom:20px;">
    <a href="{{ route('coach.pembayaran.index') }}" style="background:#6c757d;color:white;text-decoration:none;padding:8px 16px;border-radius:6px;font-size:14px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<!-- Status Badge -->
<div style="margin-bottom:20px;">
    @if($pembayaran->status === 'pending')
        <span style="background:#fff3e0;color:#e65100;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
            ⏳ Menunggu Approval Admin
        </span>
    @elseif($pembayaran->status === 'approved')
        <span style="background:#e8f5e9;color:#2e7d32;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
            ✓ Pembayaran Disetujui
        </span>
    @else
        <span style="background:#ffebee;color:#c62828;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
            ✗ Pembayaran Ditolak
        </span>
    @endif
</div>

<!-- Rejection Reason -->
@if($pembayaran->status === 'rejected' && $pembayaran->rejection_reason)
<div style="background:#fff3cd;color:#856404;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #ffeaa7;">
    <div style="font-weight:600;margin-bottom:8px;">
        <i class="fas fa-exclamation-triangle"></i> Alasan Penolakan
    </div>
    <div style="background:white;padding:10px;border-radius:4px;font-style:italic;">
        {{ $pembayaran->rejection_reason }}
    </div>
</div>
@endif

<!-- Payment Details -->
<div style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;">
    <h3 style="margin:0 0 20px 0;color:#333;">Detail Pembayaran</h3>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Nama Siswa</label>
                <div style="font-size:16px;font-weight:600;">{{ $pembayaran->siswa->nama }}</div>
                <div style="font-size:12px;color:#666;">Kelas: {{ ucfirst($pembayaran->siswa->kelas) }}</div>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Jenis Pembayaran</label>
                <div style="font-size:14px;">{{ ucfirst(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}</div>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Periode</label>
                <div style="font-size:14px;">{{ \Carbon\Carbon::create($pembayaran->tahun, $pembayaran->bulan)->format('F Y') }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Jumlah Bayar</label>
                <div style="font-size:18px;font-weight:700;color:#d32f2f;">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Tanggal Bayar</label>
                <div style="font-size:14px;">{{ $pembayaran->tanggal_bayar->format('d F Y') }}</div>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:3px;">Metode Pembayaran</label>
                <div style="font-size:14px;">{{ $pembayaran->metode_pembayaran }}</div>
            </div>
        </div>
    </div>
    
    @if($pembayaran->keterangan)
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid #f0f0f0;">
        <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Keterangan</label>
        <div style="background:#f8f9fa;padding:10px;border-radius:6px;font-size:14px;">
            {{ $pembayaran->keterangan }}
        </div>
    </div>
    @endif
</div>

<!-- Action Buttons -->
@if(in_array($pembayaran->status, ['pending', 'rejected']))
<div style="background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;">
    <h4 style="margin:0 0 15px 0;color:#333;">Aksi</h4>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('coach.pembayaran.edit', $pembayaran) }}" 
           style="background:#ff9800;color:white;text-decoration:none;padding:10px 20px;border-radius:6px;font-weight:600;">
            <i class="fas fa-edit"></i> Edit Pembayaran
        </a>
        
        @if($pembayaran->status === 'rejected')
        <div style="background:#fff3cd;color:#856404;padding:10px 15px;border-radius:6px;font-size:12px;">
            <i class="fas fa-info-circle"></i> Pembayaran ditolak, silakan edit dan submit ulang
        </div>
        @endif
    </div>
</div>
@endif

<!-- Status History -->
<div style="background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
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
        
        <!-- Approved/Rejected -->
        @if($pembayaran->approved_at)
        <div style="position:relative;padding-left:40px;">
            <div style="position:absolute;left:6px;top:2px;width:18px;height:18px;background:{{ $pembayaran->status === 'approved' ? '#4caf50' : '#f44336' }};border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-{{ $pembayaran->status === 'approved' ? 'check' : 'times' }}" style="font-size:8px;color:white;"></i>
            </div>
            <div style="font-weight:600;font-size:14px;">
                {{ $pembayaran->status === 'approved' ? 'Pembayaran Disetujui' : 'Pembayaran Ditolak' }}
            </div>
            <div style="font-size:12px;color:#666;">{{ $pembayaran->approved_at->format('d F Y, H:i') }}</div>
            <div style="font-size:12px;color:#666;">oleh {{ $pembayaran->approvedBy->name ?? '-' }}</div>
        </div>
        @else
        <div style="position:relative;padding-left:40px;">
            <div style="position:absolute;left:6px;top:2px;width:18px;height:18px;background:#ff9800;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-clock" style="font-size:8px;color:white;"></i>
            </div>
            <div style="font-weight:600;font-size:14px;">Menunggu Approval Admin</div>
            <div style="font-size:12px;color:#666;">Status: Pending</div>
        </div>
        @endif
    </div>
</div>
@endsection