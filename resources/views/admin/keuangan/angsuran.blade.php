@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">ANGSURAN</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="margin-bottom:16px; padding:12px 16px; background:#f8d7da; color:#721c24; border-radius:8px;">
        <i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}
    </div>
@endif

<!-- Form Tambah Angsuran -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-plus-circle"></i> Buat Tagihan Angsuran Baru
    </h3>
    
    <form method="POST" action="{{ route('admin.angsuran.store') }}">
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
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jenis Tagihan</label>
                <select name="jenis_tagihan" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Iuran Rutin">Iuran Rutin</option>
                    <option value="Paket Kuota">Paket Kuota</option>
                    <option value="Seragam">Seragam</option>
                    <option value="Perlengkapan">Perlengkapan</option>
                    <option value="Kejuaraan">Kejuaraan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Total Tagihan (Rp)</label>
                <input type="number" name="total_tagihan" class="form-input" placeholder="1000000" min="0" required>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tanggal Tagihan</label>
                <input type="date" name="tanggal_tagihan" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Keterangan</label>
            <textarea name="keterangan" class="form-input" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
        </div>
        
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Buat Tagihan
            </button>
        </div>
    </form>
</div>

<!-- Daftar Angsuran -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-list"></i> Daftar Angsuran
        </h3>
    </div>

    @if($angsurans->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada data angsuran
        </div>
    @else
        <div style="padding:20px;">
            @foreach($angsurans as $angsuran)
                <div style="border:2px solid {{ $angsuran->status == 'lunas' ? '#4caf50' : '#ff9800' }}; border-radius:10px; padding:20px; margin-bottom:20px; background:{{ $angsuran->status == 'lunas' ? '#f1f8f4' : '#fff8e1' }};">
                    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:15px;">
                        <div>
                            <h4 style="margin:0 0 5px 0; color:#333; font-size:18px; font-weight:700;">
                                {{ $angsuran->siswa->nama }}
                            </h4>
                            <p style="margin:0; color:#666; font-size:13px;">
                                <i class="fas fa-tag"></i> {{ $angsuran->jenis_tagihan }} • 
                                <i class="fas fa-calendar"></i> {{ $angsuran->tanggal_tagihan->format('d/m/Y') }}
                            </p>
                        </div>
                        <div style="text-align:right;">
                            <span style="background:{{ $angsuran->status == 'lunas' ? '#4caf50' : '#ff9800' }}; color:white; padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                {{ strtoupper($angsuran->status) }}
                            </span>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:15px; margin-bottom:15px; padding:15px; background:white; border-radius:8px;">
                        <div>
                            <div style="font-size:11px; color:#666; margin-bottom:3px;">Total Tagihan</div>
                            <div style="font-size:16px; font-weight:700; color:#333;">
                                Rp {{ number_format($angsuran->total_tagihan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:11px; color:#666; margin-bottom:3px;">Sudah Dibayar</div>
                            <div style="font-size:16px; font-weight:700; color:#4caf50;">
                                Rp {{ number_format($angsuran->total_dibayar, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:11px; color:#666; margin-bottom:3px;">Sisa Tagihan</div>
                            <div style="font-size:16px; font-weight:700; color:#f44336;">
                                Rp {{ number_format($angsuran->sisa_tagihan, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:11px; color:#666; margin-bottom:3px;">Cicilan</div>
                            <div style="font-size:16px; font-weight:700; color:#2196f3;">
                                {{ $angsuran->jumlah_cicilan }}x
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div style="margin-bottom:15px;">
                        <div style="background:#e0e0e0; height:8px; border-radius:10px; overflow:hidden;">
                            <div style="background:#4caf50; height:100%; width:{{ $angsuran->total_tagihan > 0 ? ($angsuran->total_dibayar / $angsuran->total_tagihan * 100) : 0 }}%; transition:width 0.3s;"></div>
                        </div>
                        <div style="font-size:11px; color:#666; margin-top:3px; text-align:right;">
                            {{ $angsuran->total_tagihan > 0 ? number_format($angsuran->total_dibayar / $angsuran->total_tagihan * 100, 1) : 0 }}% terbayar
                        </div>
                    </div>

                    <!-- Riwayat Cicilan -->
                    @if($angsuran->cicilans->isNotEmpty())
                        <details style="margin-bottom:15px;">
                            <summary style="cursor:pointer; font-weight:600; color:#2196f3; font-size:13px; padding:8px; background:white; border-radius:6px;">
                                <i class="fas fa-history"></i> Lihat Riwayat Cicilan ({{ $angsuran->cicilans->count() }})
                            </summary>
                            <div style="margin-top:10px; padding:10px; background:white; border-radius:6px;">
                                <table style="width:100%; font-size:12px;">
                                    <thead>
                                        <tr style="border-bottom:1px solid #e0e0e0;">
                                            <th style="padding:8px; text-align:left;">Cicilan Ke-</th>
                                            <th style="padding:8px; text-align:left;">Tanggal</th>
                                            <th style="padding:8px; text-align:right;">Jumlah</th>
                                            <th style="padding:8px; text-align:left;">Metode</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($angsuran->cicilans as $cicilan)
                                            <tr style="border-bottom:1px solid #f0f0f0;">
                                                <td style="padding:8px;">{{ $cicilan->cicilan_ke }}</td>
                                                <td style="padding:8px;">{{ $cicilan->tanggal_bayar->format('d/m/Y') }}</td>
                                                <td style="padding:8px; text-align:right; font-weight:600; color:#4caf50;">
                                                    Rp {{ number_format($cicilan->jumlah, 0, ',', '.') }}
                                                </td>
                                                <td style="padding:8px;">{{ $cicilan->metode_pembayaran ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    @endif

                    <!-- Tombol Aksi -->
                    <div style="display:flex; gap:10px;">
                        @if($angsuran->status == 'aktif')
                            <button onclick="bayarCicilan({{ $angsuran->id }}, '{{ $angsuran->siswa->nama }}', {{ $angsuran->sisa_tagihan }})" class="btn btn-primary" style="flex:1; padding:10px; font-size:13px;">
                                <i class="fas fa-money-bill-wave"></i> Bayar Cicilan
                            </button>
                        @endif
                        <form method="POST" action="{{ route('admin.angsuran.destroy', $angsuran) }}" style="flex:1;" onsubmit="return confirm('Hapus data angsuran ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="width:100%; padding:10px; font-size:13px;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal Bayar Cicilan -->
<div id="modalCicilan" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:12px; width:90%; max-width:500px;">
        <h3 style="margin:0 0 20px 0; color:#333;">Bayar Cicilan</h3>
        <form id="formCicilan" method="POST" action="{{ route('admin.angsuran.cicilan.store') }}">
            @csrf
            <input type="hidden" name="angsuran_id" id="angsuran_id">
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Nama Siswa</label>
                <input type="text" id="nama_siswa_cicilan" readonly style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px; background:#f5f5f5;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Sisa Tagihan</label>
                <input type="text" id="sisa_tagihan_text" readonly style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px; background:#f5f5f5;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Jumlah Bayar (Rp)</label>
                <input type="number" name="jumlah" id="jumlah_cicilan" required style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
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
                <button type="button" onclick="closeModalCicilan()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Bayar</button>
            </div>
        </form>
    </div>
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
function bayarCicilan(angsuranId, namaSiswa, sisaTagihan) {
    document.getElementById('angsuran_id').value = angsuranId;
    document.getElementById('nama_siswa_cicilan').value = namaSiswa;
    document.getElementById('sisa_tagihan_text').value = 'Rp ' + sisaTagihan.toLocaleString('id-ID');
    document.getElementById('jumlah_cicilan').value = sisaTagihan;
    document.getElementById('jumlah_cicilan').max = sisaTagihan;
    document.getElementById('modalCicilan').style.display = 'flex';
}

function closeModalCicilan() {
    document.getElementById('modalCicilan').style.display = 'none';
}
</script>
@endsection
