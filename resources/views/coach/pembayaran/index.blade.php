@extends('layouts.coach')

@section('content')
<div class="coach-header">
    <div class="coach-logo"><i class="fas fa-money-check-alt"></i></div>
    <h1 class="coach-title">Input Pembayaran Siswa</h1>
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
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $pendingCount }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Menunggu Approval</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#388e3c);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $siswas->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Siswa</div>
    </div>
</div>

<!-- Filter -->
<div style="background:white;padding:20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid;grid-template-columns:200px 1fr auto;gap:15px;align-items:end;">
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:600;font-size:14px;">Tahun</label>
            <select name="tahun" class="form-select" onchange="this.form.submit()">
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
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

<!-- Tabel Pembayaran -->
<div style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="padding:12px;text-align:left;border-bottom:2px solid #e0e0e0;position:sticky;left:0;background:#f5f5f5;z-index:10;">Nama Siswa</th>
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'] as $i => $bulan)
                        <th style="padding:12px;text-align:center;border-bottom:2px solid #e0e0e0;min-width:80px;">{{ $bulan }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr style="border-bottom:1px solid #f0f0f0;{{ $loop->even ? 'background:#fafafa;' : '' }}">
                        <td style="padding:10px;position:sticky;left:0;background:{{ $loop->even ? '#fafafa' : 'white' }};z-index:5;">
                            <div style="font-weight:600;">{{ $siswa->nama }}</div>
                            <div style="font-size:11px;color:#666;">{{ ucfirst($siswa->kelas) }}</div>
                        </td>
                        @for($bulan = 1; $bulan <= 12; $bulan++)
                            @php
                                $bayar = $siswa->pembayarans->firstWhere('bulan', $bulan);
                            @endphp
                            <td style="padding:4px;text-align:center;">
                                @if($bayar)
                                    @if($bayar->status === 'approved')
                                        <button onclick="viewPembayaran({{ $bayar->id }})"
                                            style="width:100%;padding:8px 4px;background:#4caf50;color:white;border:none;border-radius:4px;cursor:pointer;font-size:11px;">
                                            ✓ Lunas
                                        </button>
                                    @elseif($bayar->status === 'pending')
                                        <button onclick="editPembayaran({{ $siswa->id }}, {{ $bulan }}, {{ $bayar->id }})"
                                            style="width:100%;padding:8px 4px;background:#ff9800;color:white;border:none;border-radius:4px;cursor:pointer;font-size:11px;">
                                            ⏳ Pending
                                        </button>
                                    @else
                                        <button onclick="editPembayaran({{ $siswa->id }}, {{ $bulan }}, {{ $bayar->id }})"
                                            style="width:100%;padding:8px 4px;background:#f44336;color:white;border:none;border-radius:4px;cursor:pointer;font-size:11px;">
                                            ✗ Rejected
                                        </button>
                                    @endif
                                @else
                                    <button onclick="addPembayaran({{ $siswa->id }}, {{ $bulan }})"
                                        style="width:100%;padding:8px 4px;background:#9e9e9e;color:white;border:none;border-radius:4px;cursor:pointer;font-size:11px;">
                                        + Input
                                    </button>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" style="padding:40px;text-align:center;color:#999;">
                            <i class="fas fa-inbox" style="font-size:40px;display:block;margin-bottom:10px;"></i>
                            Tidak ada data siswa
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Input Pembayaran -->
<div id="modalPembayaran" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;padding:30px;border-radius:12px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;color:#333;">Input Pembayaran</h3>
        <form id="formPembayaran" method="POST" action="{{ route('coach.pembayaran.store') }}">
            @csrf
            <input type="hidden" name="siswa_id" id="siswa_id">
            <input type="hidden" name="jenis_pembayaran" value="iuran_rutin">
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="bulan" id="bulan">
            
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Nama Siswa</label>
                <input type="text" id="nama_siswa" readonly style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;background:#f5f5f5;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Bulan</label>
                <input type="text" id="bulan_text" readonly style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;background:#f5f5f5;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Jumlah Bayar (Rp) <span style="color:red">*</span></label>
                <input type="number" name="jumlah" id="jumlah" required style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Tanggal Bayar <span style="color:red">*</span></label>
                <input type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}" style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Metode Pembayaran <span style="color:red">*</span></label>
                <select name="metode_pembayaran" required style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;">
                    <option value="">Pilih Metode</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer">Transfer</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Keterangan</label>
                <textarea name="keterangan" rows="2" style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;resize:vertical;" placeholder="Keterangan tambahan (opsional)"></textarea>
            </div>
            
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="closeModal()" style="padding:10px 20px;border:1px solid #ddd;background:white;border-radius:6px;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:10px 20px;background:#d32f2f;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Submit</button>
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
const siswasData = @json($siswas);
const bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

function addPembayaran(siswaId, bulan) {
    const siswa = siswasData.find(s => s.id === siswaId);
    document.getElementById('siswa_id').value = siswaId;
    document.getElementById('bulan').value = bulan;
    document.getElementById('nama_siswa').value = siswa.nama;
    document.getElementById('bulan_text').value = bulanNames[bulan];
    document.getElementById('jumlah').value = 500000;
    
    // Reset form for new input
    document.getElementById('formPembayaran').action = "{{ route('coach.pembayaran.store') }}";
    document.getElementById('formPembayaran').method = 'POST';
    
    document.getElementById('modalPembayaran').style.display = 'flex';
}

function editPembayaran(siswaId, bulan, pembayaranId) {
    // For now, redirect to edit page
    window.location.href = `/coach/pembayaran/${pembayaranId}/edit`;
}

function viewPembayaran(pembayaranId) {
    window.location.href = `/coach/pembayaran/${pembayaranId}`;
}

function closeModal() {
    document.getElementById('modalPembayaran').style.display = 'none';
}
</script>
@endsection