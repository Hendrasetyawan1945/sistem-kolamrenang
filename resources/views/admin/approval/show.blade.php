@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-receipt"></i></div>
    <h1 class="club-title">Detail Pembayaran</h1>
</div>

<!-- Back Button -->
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.approval.pembayaran') }}" style="background:#6c757d;color:white;text-decoration:none;padding:8px 16px;border-radius:6px;font-size:14px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Approval Dashboard
    </a>
</div>

<!-- Status Badge -->
<div style="margin-bottom:20px;">
    @if($pembayaran->status === 'pending')
        <span style="background:#fff3e0;color:#e65100;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
            ⏳ Menunggu Approval
        </span>
    @elseif($pembayaran->status === 'approved')
        <span style="background:#e8f5e9;color:#2e7d32;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
            ✓ Sudah Disetujui
        </span>
    @else
        <span style="background:#ffebee;color:#c62828;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
            ✗ Ditolak
        </span>
    @endif
</div>

<!-- Payment Details -->
<div style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;">
    <h3 style="margin:0 0 20px 0;color:#333;">Detail Pembayaran</h3>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
        <div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Siswa</label>
                <div style="font-size:18px;font-weight:600;">{{ $pembayaran->siswa->nama }}</div>
                <div style="font-size:14px;color:#666;">Kelas: {{ ucfirst($pembayaran->siswa->kelas) }}</div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Jenis & Periode</label>
                <div style="font-size:16px;">{{ ucfirst(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}</div>
                <div style="font-size:14px;color:#666;">{{ \Carbon\Carbon::create($pembayaran->tahun, $pembayaran->bulan)->format('F Y') }}</div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Input oleh Coach</label>
                <div style="font-size:16px;font-weight:600;">{{ $pembayaran->inputBy->name ?? '-' }}</div>
                <div style="font-size:12px;color:#666;">{{ $pembayaran->created_at->format('d F Y, H:i') }}</div>
            </div>
        </div>
        
        <div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Jumlah Bayar</label>
                <div style="font-size:24px;font-weight:700;color:#d32f2f;">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Tanggal Bayar</label>
                <div style="font-size:16px;">{{ $pembayaran->tanggal_bayar->format('d F Y') }}</div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Metode Pembayaran</label>
                <div style="font-size:16px;">{{ $pembayaran->metode_pembayaran }}</div>
            </div>
        </div>
    </div>
    
    @if($pembayaran->keterangan)
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid #f0f0f0;">
        <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Keterangan</label>
        <div style="background:#f8f9fa;padding:15px;border-radius:6px;font-size:14px;">
            {{ $pembayaran->keterangan }}
        </div>
    </div>
    @endif
</div>

<!-- Approval Actions -->
@if($pembayaran->status === 'pending')
<div style="background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;">
    <h4 style="margin:0 0 15px 0;color:#333;">Aksi Approval</h4>
    <div style="display:flex;gap:15px;">
        <button onclick="approvePembayaran({{ $pembayaran->id }})" 
                style="background:#4caf50;color:white;border:none;padding:12px 24px;border-radius:6px;font-weight:600;cursor:pointer;font-size:14px;">
            <i class="fas fa-check"></i> Approve Pembayaran
        </button>
        
        <button onclick="rejectPembayaran({{ $pembayaran->id }})" 
                style="background:#f44336;color:white;border:none;padding:12px 24px;border-radius:6px;font-weight:600;cursor:pointer;font-size:14px;">
            <i class="fas fa-times"></i> Reject Pembayaran
        </button>
    </div>
</div>
@endif

<!-- Approval Status -->
@if($pembayaran->approved_at)
<div style="background:white;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin-bottom:20px;">
    <h4 style="margin:0 0 15px 0;color:#333;">Status Approval</h4>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Diproses oleh</label>
            <div style="font-size:16px;font-weight:600;">{{ $pembayaran->approvedBy->name ?? '-' }}</div>
        </div>
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Tanggal Approval</label>
            <div style="font-size:16px;">{{ $pembayaran->approved_at->format('d F Y, H:i') }}</div>
        </div>
    </div>
    
    @if($pembayaran->status === 'rejected' && $pembayaran->rejection_reason)
    <div style="margin-top:15px;">
        <label style="display:block;font-size:12px;color:#666;margin-bottom:5px;">Alasan Penolakan</label>
        <div style="background:#fff3cd;color:#856404;padding:15px;border-radius:6px;border:1px solid #ffeaa7;">
            {{ $pembayaran->rejection_reason }}
        </div>
    </div>
    @endif
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
            <div style="font-weight:600;font-size:14px;">Menunggu Approval</div>
            <div style="font-size:12px;color:#666;">Status: Pending</div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Reject -->
<div id="modalReject" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:30px;border-radius:12px;width:90%;max-width:400px;">
        <h3 style="margin:0 0 20px 0;color:#f44336;">Reject Pembayaran</h3>
        <form id="formReject" method="POST">
            @csrf
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Alasan Reject <span style="color:red">*</span></label>
                <textarea name="rejection_reason" required rows="3" style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;resize:vertical;" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeRejectModal()" style="padding:10px 20px;border:1px solid #ddd;background:white;border-radius:6px;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:10px 20px;background:#f44336;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function approvePembayaran(pembayaranId) {
    if (confirm('Approve pembayaran ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/approval/pembayaran/${pembayaranId}/approve`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectPembayaran(pembayaranId) {
    document.getElementById('formReject').action = `/admin/approval/pembayaran/${pembayaranId}/reject`;
    document.getElementById('modalReject').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('modalReject').style.display = 'none';
}
</script>
@endsection