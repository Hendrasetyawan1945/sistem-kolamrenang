@extends('layouts.admin')
@section('content')
<div class="club-header">
    <div class="club-logo"><i class="fas fa-minus-circle"></i></div>
    <h1 class="club-title">Pengeluaran</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@php
$namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
@endphp

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:15px;margin-bottom:25px;">
    <div style="background:linear-gradient(135deg,#f44336,#c62828);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">BULAN INI</div>
        <div style="font-size:18px;font-weight:700;">Rp {{ number_format($stats['bulan_ini'],0,',','.') }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#e91e63,#880e4f);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">TAHUN INI</div>
        <div style="font-size:18px;font-weight:700;">Rp {{ number_format($stats['tahun_ini'],0,',','.') }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">TERBESAR BULAN INI</div>
        <div style="font-size:18px;font-weight:700;">Rp {{ number_format($stats['terbesar'],0,',','.') }}</div>
    </div>
    <div style="background:linear-gradient(135deg,#607d8b,#37474f);color:white;padding:20px;border-radius:10px;text-align:center;">
        <div style="font-size:11px;opacity:.8;margin-bottom:4px;">TOTAL FILTER</div>
        <div style="font-size:18px;font-weight:700;">Rp {{ number_format($stats['total_filter'],0,',','.') }}</div>
    </div>
</div>

<!-- Tombol Tambah -->
<div style="margin-bottom:20px;">
    <button onclick="openModal()" style="background:#d32f2f;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
        <i class="fas fa-plus"></i> Tambah Pengeluaran
    </button>
</div>

<!-- Filter -->
<div style="background:white;padding:16px 20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.keuangan.pengeluaran') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Bulan</label>
            <select name="bulan" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                @foreach($namaBulan as $v=>$l)
                <option value="{{ $v }}" {{ $bulan==$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Tahun</label>
            <select name="tahun" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                @foreach([2026,2025,2024] as $y)
                <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Kategori</label>
            <select name="kategori" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                <option value="">Semua</option>
                @foreach($kategoriList as $k)
                <option value="{{ $k }}" {{ request('kategori')==$k?'selected':'' }}>{{ ucfirst($k) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" style="background:#d32f2f;color:white;border:none;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('admin.keuangan.pengeluaran') }}" style="padding:8px 14px;border:1px solid #ddd;border-radius:6px;font-size:13px;text-decoration:none;color:#666;">Reset</a>
    </form>
</div>

<!-- Tabel -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">
            Pengeluaran {{ $namaBulan[$bulan] ?? '' }} {{ $tahun }} ({{ $pengeluarans->count() }} data)
        </h3>
        <strong style="color:#f44336;">Total: Rp {{ number_format($pengeluarans->sum('jumlah'),0,',','.') }}</strong>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Tanggal</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Kategori</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Keterangan</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Metode</th>
                    <th style="padding:11px 14px;text-align:right;font-size:12px;color:#666;border-bottom:1px solid #eee;">Jumlah</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluarans as $i => $p)
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $p->tanggal->format('d M Y') }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <span style="background:#ffebee;color:#c62828;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;">{{ ucfirst($p->kategori) }}</span>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $p->keterangan }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;">{{ $p->metode_pembayaran ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;font-weight:600;text-align:right;color:#c62828;">
                        Rp {{ number_format($p->jumlah,0,',','.') }}
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:5px;">
                            <button onclick="openEditModal({{ $p->id }},'{{ $p->tanggal->format('Y-m-d') }}','{{ $p->kategori }}','{{ addslashes($p->keterangan) }}',{{ $p->jumlah }},'{{ $p->metode_pembayaran ?? '' }}')"
                                style="background:#fff3e0;color:#f57c00;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.keuangan.pengeluaran.destroy', $p) }}" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#ffebee;color:#f44336;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:50px;text-align:center;color:#999;">
                        <i class="fas fa-minus-circle" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Tidak ada pengeluaran di periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:480px;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-plus" style="color:#d32f2f;"></i> Tambah Pengeluaran</h3>
        <form method="POST" action="{{ route('admin.keuangan.pengeluaran.store') }}">
            @csrf
            @include('admin.keuangan._form-pengeluaran')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#d32f2f;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:480px;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-edit" style="color:#f57c00;"></i> Edit Pengeluaran</h3>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            @include('admin.keuangan._form-pengeluaran', ['edit' => true])
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeEditModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#f57c00;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() { document.getElementById('addModal').style.display = 'flex'; }
function closeModal() { document.getElementById('addModal').style.display = 'none'; }
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

function openEditModal(id, tanggal, kategori, keterangan, jumlah, metode) {
    const f = document.getElementById('editForm');
    f.action = `/admin/keuangan/pengeluaran/${id}`;
    f.querySelector('[name="tanggal"]').value = tanggal;
    f.querySelector('[name="kategori"]').value = kategori;
    f.querySelector('[name="keterangan"]').value = keterangan;
    f.querySelector('[name="jumlah"]').value = jumlah;
    f.querySelector('[name="metode_pembayaran"]').value = metode;
    document.getElementById('editModal').style.display = 'flex';
}

document.querySelectorAll('#addModal,#editModal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.style.display = 'none'; });
});
</script>
@endsection
