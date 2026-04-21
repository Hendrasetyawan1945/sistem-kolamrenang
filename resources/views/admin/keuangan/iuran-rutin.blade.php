@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">IURAN RUTIN</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:200px 200px 1fr auto; gap:15px; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Kelas</label>
            <select name="kelas" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ ucfirst($k) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tahun</label>
            <select name="tahun" class="form-select" onchange="this.form.submit()">
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Cari</label>
            <input type="text" name="search" class="form-input" placeholder="Nama Siswa" value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>
</div>

<!-- Tabel Pembayaran -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="padding:12px; text-align:left; border-bottom:2px solid #e0e0e0; position:sticky; left:0; background:#f5f5f5; z-index:10;">Nama Siswa</th>
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'] as $i => $bulan)
                        <th style="padding:12px; text-align:center; border-bottom:2px solid #e0e0e0; min-width:60px;">{{ $bulan }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                        <td style="padding:10px; position:sticky; left:0; background:{{ $loop->even ? '#fafafa' : 'white' }}; z-index:5;">
                            <a href="javascript:void(0)" onclick="showProfile({{ $siswa->id }})" style="color:#2196f3; text-decoration:none; font-weight:600;">
                                {{ $siswa->nama }}
                            </a>
                        </td>
                        @for($bulan = 1; $bulan <= 12; $bulan++)
                            @php
                                $bayar = $siswa->pembayarans->firstWhere('bulan', $bulan);
                            @endphp
                            <td style="padding:4px; text-align:center;">
                                @if($bayar)
                                    <button onclick="editPembayaran({{ $siswa->id }}, {{ $bulan }}, {{ $bayar->jumlah }})"
                                        style="width:100%; padding:8px 4px; background:#4caf50; color:white; border:none; border-radius:4px; cursor:pointer; font-size:11px;">
                                        ✓
                                    </button>
                                @else
                                    <button onclick="addPembayaran({{ $siswa->id }}, {{ $bulan }})"
                                        style="width:100%; padding:8px 4px; background:#f44336; color:white; border:none; border-radius:4px; cursor:pointer; font-size:11px;">
                                        ✗
                                    </button>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" style="padding:40px; text-align:center; color:#999;">
                            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
                            Tidak ada data siswa
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Pembayaran -->
<div id="modalPembayaran" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:12px; width:90%; max-width:500px; max-height:90vh; overflow-y:auto;">
        <h3 style="margin:0 0 20px 0; color:#333;">Input Pembayaran</h3>
        <form id="formPembayaran" method="POST" action="{{ route('admin.pembayaran.store') }}">
            @csrf
            <input type="hidden" name="siswa_id" id="siswa_id">
            <input type="hidden" name="jenis_pembayaran" value="iuran_rutin">
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="bulan" id="bulan">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Nama Siswa</label>
                <input type="text" id="nama_siswa" readonly style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px; background:#f5f5f5;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Bulan</label>
                <input type="text" id="bulan_text" readonly style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px; background:#f5f5f5;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Jumlah Bayar (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" required style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}" style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Metode Pembayaran</label>
                <select name="metode_pembayaran" style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer">Transfer</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Profil Siswa -->
<div id="modalProfile" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:12px; width:90%; max-width:700px; max-height:90vh; overflow-y:auto;">
        <h3 style="margin:0 0 20px 0; color:#d32f2f; font-style:italic;">PROFIL SISWA</h3>
        <div id="profileContent"></div>
        <button onclick="closeProfileModal()" class="btn btn-secondary" style="margin-top:20px;">Tutup</button>
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
    document.getElementById('modalPembayaran').style.display = 'flex';
}

function editPembayaran(siswaId, bulan, jumlah) {
    addPembayaran(siswaId, bulan);
    document.getElementById('jumlah').value = jumlah;
}

function closeModal() {
    document.getElementById('modalPembayaran').style.display = 'none';
}

function showProfile(siswaId) {
    const siswa = siswasData.find(s => s.id === siswaId);
    const pembayarans = siswa.pembayarans || [];
    
    let html = `
        <div style="display:grid; grid-template-columns:1fr 2fr; gap:10px; margin-bottom:20px; font-size:14px;">
            <div style="font-weight:600;">Nama Lengkap:</div><div>${siswa.nama}</div>
            <div style="font-weight:600;">Nama Panggilan:</div><div>-</div>
            <div style="font-weight:600;">Jenis Kelamin:</div><div>${siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</div>
            <div style="font-weight:600;">Tempat & Tanggal Lahir:</div><div>${siswa.tanggal_lahir}</div>
            <div style="font-weight:600;">Tanggal Bergabung:</div><div>${siswa.created_at}</div>
        </div>
        
        <div style="background:#f5f5f5; padding:10px; border-radius:6px; text-align:center; margin:20px 0;">
            <strong>${siswa.kelas.toUpperCase()}</strong>
        </div>
        
        <h4 style="margin:20px 0 10px 0; color:#333;">Riwayat Pembayaran ${{{ $tahun }}}</h4>
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="padding:8px; text-align:left; border:1px solid #e0e0e0;">Bulan</th>
                    <th style="padding:8px; text-align:right; border:1px solid #e0e0e0;">Jumlah</th>
                    <th style="padding:8px; text-align:center; border:1px solid #e0e0e0;">Status</th>
                </tr>
            </thead>
            <tbody>`;
    
    for (let i = 1; i <= 12; i++) {
        const bayar = pembayarans.find(p => p.bulan === i);
        const status = bayar ? '<span style="color:#4caf50;">✓ Lunas</span>' : '<span style="color:#f44336;">✗ Belum</span>';
        const jumlah = bayar ? `Rp ${parseFloat(bayar.jumlah).toLocaleString('id-ID')}` : '-';
        html += `
            <tr style="border:1px solid #f0f0f0;">
                <td style="padding:8px;">${bulanNames[i]}</td>
                <td style="padding:8px; text-align:right;">${jumlah}</td>
                <td style="padding:8px; text-align:center;">${status}</td>
            </tr>`;
    }
    
    html += '</tbody></table>';
    document.getElementById('profileContent').innerHTML = html;
    document.getElementById('modalProfile').style.display = 'flex';
}

function closeProfileModal() {
    document.getElementById('modalProfile').style.display = 'none';
}
</script>
@endsection
