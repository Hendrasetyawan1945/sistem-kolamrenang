@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">IURAN INSIDENTIL</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Form Input Iuran Insidentil -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-plus-circle"></i> Input Iuran Insidentil
    </h3>
    
    <form method="POST" action="{{ route('admin.iuran-insidentil.store') }}">
        @csrf
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Pilih Siswa</label>
                <select name="siswa_id" class="form-select" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama }} ({{ ucfirst($siswa->kelas) }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tanggal</label>
                <input type="date" name="tanggal_bayar" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jenis Iuran</label>
                <input type="text" name="jenis_iuran" class="form-input" placeholder="Contoh: Seragam, Perlengkapan, dll" required>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-input" placeholder="100000" min="0" required>
            </div>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Keterangan</label>
            <textarea name="keterangan" class="form-input" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
        </div>
        
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

<!-- Riwayat Iuran Insidentil -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-history"></i> Riwayat Iuran Insidentil
        </h3>
        <div style="display:flex; gap:10px;">
            <select class="form-select" style="width:150px;" onchange="filterByMonth(this.value)">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>
            <input type="text" class="form-input" placeholder="Cari siswa..." style="width:200px;" onkeyup="searchTable(this.value)">
        </div>
    </div>

    @if($insidentils->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada data iuran insidentil
        </div>
    @else
        <div style="overflow-x:auto;">
            <table id="tableInsidentil" style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">No</th>
                        <th style="padding:12px 16px; text-align:left;">Tanggal</th>
                        <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:left;">Kelas</th>
                        <th style="padding:12px 16px; text-align:left;">Jenis Iuran</th>
                        <th style="padding:12px 16px; text-align:right;">Jumlah</th>
                        <th style="padding:12px 16px; text-align:left;">Keterangan</th>
                        <th style="padding:12px 16px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($insidentils as $i => $item)
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}" data-month="{{ $item->tanggal_bayar->format('n') }}">
                            <td style="padding:10px 16px;">{{ $i + 1 }}</td>
                            <td style="padding:10px 16px;">{{ $item->tanggal_bayar->format('d/m/Y') }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ $item->siswa->nama }}</td>
                            <td style="padding:10px 16px;">{{ ucfirst($item->siswa->kelas) }}</td>
                            <td style="padding:10px 16px;">{{ $item->keterangan }}</td>
                            <td style="padding:10px 16px; text-align:right; font-weight:600; color:#4caf50;">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td style="padding:10px 16px; color:#666; font-size:12px;">
                                {{ $item->metode_pembayaran ?? '-' }}
                            </td>
                            <td style="padding:10px 16px; text-align:center;">
                                <form method="POST" action="{{ route('admin.iuran-insidentil.destroy', $item) }}" style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:5px 10px; font-size:11px; border-radius:5px; background:#f44336; color:white; border:none; cursor:pointer;">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8f9fa; border-top:2px solid #e0e0e0; font-weight:700;">
                        <td colspan="5" style="padding:12px 16px; text-align:right;">TOTAL:</td>
                        <td style="padding:12px 16px; text-align:right; color:#d32f2f; font-size:16px;">
                            Rp {{ number_format($insidentils->sum('jumlah'), 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
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
    textarea.form-input {
        resize:vertical;
    }
</style>

<script>
function searchTable(value) {
    const table = document.getElementById('tableInsidentil');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value.toLowerCase()) ? '' : 'none';
    }
}

function filterByMonth(month) {
    const table = document.getElementById('tableInsidentil');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        if (!month || row.getAttribute('data-month') === month) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}
</script>
@endsection
