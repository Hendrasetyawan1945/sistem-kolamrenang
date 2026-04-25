@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-check-circle"></i></div>
    <h1 class="club-title">Approval Pembayaran</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #f5c6cb;">
    <i class="fas fa-exclamation-triangle"></i> 
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach
</div>
@endif

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['pending'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Pending</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#388e3c);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['approved'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Approved</div>
    </div>
    <div style="background:linear-gradient(135deg,#f44336,#d32f2f);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['rejected'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Rejected</div>
    </div>
</div>

<!-- Filter -->
<div style="background:white;padding:20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid;grid-template-columns:150px 200px 1fr auto;gap:15px;align-items:end;">
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">Status</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="pending" {{ request('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">Coach</label>
            <select name="coach" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Coach</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}" {{ request('coach') == $coach->id ? 'selected' : '' }}>{{ $coach->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">Cari Siswa</label>
            <input type="text" name="search" class="form-input" placeholder="Nama Siswa" value="{{ request('search') }}">
        </div>
        <button type="submit" style="background:#d32f2f;color:white;border:none;padding:10px 20px;border-radius:8px;font-weight:600;cursor:pointer;">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>
</div>

<!-- Bulk Actions -->
@if(request('status', 'pending') == 'pending' && $pembayarans->count() > 0)
<div style="background:white;padding:15px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="POST" action="{{ route('admin.approval.pembayaran.bulk-approve') }}" onsubmit="return confirm('Approve semua pembayaran yang dipilih?')">
        @csrf
        <div style="display:flex;align-items:center;gap:15px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" id="selectAll" onchange="toggleAll()">
                <span style="font-weight:600;">Pilih Semua</span>
            </label>
            <button type="submit" style="background:#4caf50;color:white;border:none;padding:8px 16px;border-radius:6px;font-weight:600;cursor:pointer;">
                <i class="fas fa-check"></i> Bulk Approve
            </button>
        </div>
    </form>
</div>
@endif

<!-- Tabel Pembayaran -->
<div style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f5f5f5;">
                    @if(request('status', 'pending') == 'pending')
                        <th style="padding:12px;text-align:center;border-bottom:2px solid #e0e0e0;width:40px;">
                            <input type="checkbox" id="headerCheck" onchange="toggleAll()">
                        </th>
                    @endif
                    <th style="padding:12px;text-align:left;border-bottom:2px solid #e0e0e0;">Siswa</th>
                    <th style="padding:12px;text-align:left;border-bottom:2px solid #e0e0e0;">Periode</th>
                    <th style="padding:12px;text-align:right;border-bottom:2px solid #e0e0e0;">Jumlah</th>
                    <th style="padding:12px;text-align:left;border-bottom:2px solid #e0e0e0;">Metode</th>
                    <th style="padding:12px;text-align:left;border-bottom:2px solid #e0e0e0;">Input By</th>
                    <th style="padding:12px;text-align:center;border-bottom:2px solid #e0e0e0;">Status</th>
                    <th style="padding:12px;text-align:center;border-bottom:2px solid #e0e0e0;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayarans as $pembayaran)
                    <tr style="border-bottom:1px solid #f0f0f0;{{ $loop->even ? 'background:#fafafa;' : '' }}">
                        @if(request('status', 'pending') == 'pending' && $pembayaran->status == 'pending')
                            <td style="padding:12px;text-align:center;">
                                <input type="checkbox" name="pembayaran_ids[]" value="{{ $pembayaran->id }}" class="pembayaran-check">
                            </td>
                        @elseif(request('status', 'pending') == 'pending')
                            <td style="padding:12px;text-align:center;">-</td>
                        @endif
                        <td style="padding:12px;">
                            <div style="font-weight:600;">{{ $pembayaran->siswa->nama }}</div>
                            <div style="font-size:11px;color:#666;">{{ ucfirst($pembayaran->siswa->kelas) }}</div>
                        </td>
                        <td style="padding:12px;">
                            <div>{{ ucfirst($pembayaran->jenis_pembayaran) }}</div>
                            <div style="font-size:11px;color:#666;">
                                {{ \Carbon\Carbon::create($pembayaran->tahun, $pembayaran->bulan)->format('F Y') }}
                            </div>
                        </td>
                        <td style="padding:12px;text-align:right;font-weight:600;">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </td>
                        <td style="padding:12px;">{{ $pembayaran->metode_pembayaran }}</td>
                        <td style="padding:12px;">
                            <div style="font-size:12px;">
                                <strong>{{ $pembayaran->inputBy->name ?? '-' }}</strong>
                                @if($pembayaran->inputBy && $pembayaran->inputBy->coach)
                                    <span style="color:#666;">(Coach)</span>
                                @endif
                            </div>
                            <div style="font-size:10px;color:#666;">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</div>
                            @if($pembayaran->status !== 'pending')
                                <div style="font-size:10px;color:#2196f3;margin-top:4px;">
                                    <i class="fas fa-check-circle"></i> 
                                    @if($pembayaran->status === 'approved')
                                        Approved by: {{ $pembayaran->approvedBy->name ?? '-' }}
                                    @else
                                        Rejected by: {{ $pembayaran->approvedBy->name ?? '-' }}
                                    @endif
                                </div>
                                <div style="font-size:9px;color:#999;">
                                    {{ $pembayaran->approved_at ? $pembayaran->approved_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            @endif
                        </td>
                        <td style="padding:12px;text-align:center;">
                            @if($pembayaran->status === 'pending')
                                <span style="background:#fff3e0;color:#e65100;padding:4px 8px;border-radius:12px;font-size:11px;font-weight:600;">
                                    ⏳ Pending
                                </span>
                            @elseif($pembayaran->status === 'approved')
                                <span style="background:#e8f5e9;color:#2e7d32;padding:4px 8px;border-radius:12px;font-size:11px;font-weight:600;">
                                    ✓ Approved
                                </span>
                            @else
                                <span style="background:#ffebee;color:#c62828;padding:4px 8px;border-radius:12px;font-size:11px;font-weight:600;">
                                    ✗ Rejected
                                </span>
                            @endif
                        </td>
                        <td style="padding:12px;text-align:center;">
                            <div style="display:flex;gap:5px;justify-content:center;">
                                <button onclick="viewDetail({{ $pembayaran->id }})" 
                                    style="background:#2196f3;color:white;border:none;padding:5px 8px;border-radius:4px;cursor:pointer;font-size:11px;" 
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($pembayaran->status === 'pending')
                                    <button onclick="approvePembayaran({{ $pembayaran->id }})" 
                                        style="background:#4caf50;color:white;border:none;padding:5px 8px;border-radius:4px;cursor:pointer;font-size:11px;" 
                                        title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="rejectPembayaran({{ $pembayaran->id }})" 
                                        style="background:#f44336;color:white;border:none;padding:5px 8px;border-radius:4px;cursor:pointer;font-size:11px;" 
                                        title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ request('status', 'pending') == 'pending' ? '8' : '7' }}" style="padding:40px;text-align:center;color:#999;">
                            <i class="fas fa-inbox" style="font-size:40px;display:block;margin-bottom:10px;"></i>
                            Tidak ada data pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pembayarans->hasPages())
        <div style="padding:20px;">
            {{ $pembayarans->links() }}
        </div>
    @endif
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

<style>
.form-select, .form-input {
    padding:10px 12px;
    border:2px solid #e0e0e0;
    border-radius:6px;
    font-size:14px;
    width:100%;
}
.form-select:focus, .form-input:focus {
    outline:none;
    border-color:#d32f2f;
}
</style>

<script>
function viewDetail(pembayaranId) {
    window.location.href = `/admin/approval/pembayaran/${pembayaranId}`;
}

function approvePembayaran(pembayaranId) {
    // Cari data pembayaran dari tabel
    const row = event.target.closest('tr');
    const siswaName = row.querySelector('td:nth-child({{ request("status", "pending") == "pending" ? "2" : "1" }}) div:first-child').textContent.trim();
    const periode = row.querySelector('td:nth-child({{ request("status", "pending") == "pending" ? "3" : "2" }}) div:first-child').textContent.trim();
    const jumlah = row.querySelector('td:nth-child({{ request("status", "pending") == "pending" ? "4" : "3" }})').textContent.trim();
    const metode = row.querySelector('td:nth-child({{ request("status", "pending") == "pending" ? "5" : "4" }})').textContent.trim();
    const inputBy = row.querySelector('td:nth-child({{ request("status", "pending") == "pending" ? "6" : "5" }}) div:first-child strong').textContent.trim();
    
    if (confirm(`APPROVE PEMBAYARAN?\n\n` +
                `Siswa: ${siswaName}\n` +
                `Periode: ${periode}\n` +
                `Jumlah: ${jumlah}\n` +
                `Metode: ${metode}\n` +
                `Input oleh: ${inputBy}\n\n` +
                `Setelah di-approve, pembayaran akan tercatat sebagai LUNAS.\n` +
                `Lanjutkan?`)) {
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

function toggleAll() {
    const selectAll = document.getElementById('selectAll') || document.getElementById('headerCheck');
    const checkboxes = document.querySelectorAll('.pembayaran-check');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActionInfo();
}

function updateBulkActionInfo() {
    const checked = document.querySelectorAll('.pembayaran-check:checked');
    const count = checked.length;
    
    // Update button text if exists
    const bulkBtn = document.querySelector('button[type="submit"]');
    if (bulkBtn && bulkBtn.textContent.includes('Bulk Approve')) {
        bulkBtn.innerHTML = `<i class="fas fa-check"></i> Bulk Approve (${count})`;
    }
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pembayaran-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionInfo);
    });
});
</script>
@endsection