@extends('layouts.admin')

@section('content')
<h1 style="color:#d32f2f; font-size:28px; font-weight:700; margin-bottom:20px; font-style:italic;">CATATAN WAKTU</h1>

@if(session('success'))
    <div style="margin-bottom:16px; padding:12px 16px; background:#d4edda; color:#155724; border-radius:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Form Input -->
<div style="background:white; padding:25px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <h3 style="margin:0 0 20px 0; color:#333; font-size:18px;">
        <i class="fas fa-plus-circle"></i> Input Catatan Waktu
    </h3>
    
    <form method="POST" action="{{ route('admin.catatan-waktu.store') }}">
        @csrf
        
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Pilih Siswa</label>
                <select name="siswa_id" class="form-select" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jenis Event</label>
                <select name="jenis_event" class="form-select" required>
                    <option value="latihan">Latihan</option>
                    <option value="time_trial">Time Trial</option>
                    <option value="kejuaraan">Kejuaraan</option>
                </select>
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nomor Lomba</label>
                <select name="nomor_lomba" class="form-select" required>
                    <option value="">-- Pilih Nomor --</option>
                    <optgroup label="Freestyle">
                        <option value="50m Freestyle">50m Freestyle</option>
                        <option value="100m Freestyle">100m Freestyle</option>
                        <option value="200m Freestyle">200m Freestyle</option>
                        <option value="400m Freestyle">400m Freestyle</option>
                    </optgroup>
                    <optgroup label="Backstroke">
                        <option value="50m Backstroke">50m Backstroke</option>
                        <option value="100m Backstroke">100m Backstroke</option>
                        <option value="200m Backstroke">200m Backstroke</option>
                    </optgroup>
                    <optgroup label="Breaststroke">
                        <option value="50m Breaststroke">50m Breaststroke</option>
                        <option value="100m Breaststroke">100m Breaststroke</option>
                        <option value="200m Breaststroke">200m Breaststroke</option>
                    </optgroup>
                    <optgroup label="Butterfly">
                        <option value="50m Butterfly">50m Butterfly</option>
                        <option value="100m Butterfly">100m Butterfly</option>
                        <option value="200m Butterfly">200m Butterfly</option>
                    </optgroup>
                    <optgroup label="Individual Medley">
                        <option value="200m IM">200m Individual Medley</option>
                        <option value="400m IM">400m Individual Medley</option>
                    </optgroup>
                </select>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jenis Kolam</label>
                <select name="jenis_kolam" class="form-select" required onchange="updateLokasiFromKolam(this)">
                    <option value="">-- Pilih Kolam --</option>
                    @foreach($kolams as $k)
                    <option value="{{ $k->ukuran }}" data-nama="{{ $k->nama }}" data-lokasi="{{ $k->lokasi ?? '' }}">
                        {{ $k->nama }} ({{ $k->ukuran }})
                    </option>
                    @endforeach
                    <option value="25m">Lainnya — 25m (Short Course)</option>
                    <option value="50m">Lainnya — 50m (Long Course)</option>
                </select>
                <small style="color:#666;font-size:11px;">Pilih kolam → lokasi otomatis terisi</small>
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Waktu (MM:SS.MS)</label>
                <input type="text" name="waktu" class="form-input" placeholder="01:23.45" pattern="[0-9]{1,2}:[0-9]{2}\.[0-9]{2}" required>
                <small style="color:#666; font-size:11px;">Format: Menit:Detik.Milidetik</small>
            </div>
        </div>
        
        <div style="display:grid; grid-template-columns:1fr 2fr; gap:20px; margin-bottom:20px;">
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Lokasi / Venue</label>
                <input type="text" name="lokasi" id="inputLokasi" class="form-input" placeholder="Otomatis dari kolam atau isi manual">
            </div>
            
            <div>
                <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Keterangan</label>
                <input type="text" name="keterangan" class="form-input" placeholder="Catatan tambahan (opsional)">
            </div>
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

<!-- Filter -->
<div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:15px; align-items:end;">
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Siswa</label>
            <select name="siswa_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Siswa</option>
                @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>{{ $siswa->nama }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Nomor Lomba</label>
            <select name="nomor_lomba" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Nomor</option>
                @foreach($nomorLombas as $nomor)
                    <option value="{{ $nomor }}" {{ request('nomor_lomba') == $nomor ? 'selected' : '' }}>{{ $nomor }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:14px;">Jenis Event</label>
            <select name="jenis_event" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Event</option>
                <option value="latihan" {{ request('jenis_event') == 'latihan' ? 'selected' : '' }}>Latihan</option>
                <option value="time_trial" {{ request('jenis_event') == 'time_trial' ? 'selected' : '' }}>Time Trial</option>
                <option value="kejuaraan" {{ request('jenis_event') == 'kejuaraan' ? 'selected' : '' }}>Kejuaraan</option>
            </select>
        </div>
        
        <a href="{{ route('admin.personal-best') }}" class="btn btn-primary">
            <i class="fas fa-trophy"></i> Personal Best
        </a>
    </form>
</div>

<!-- Tabel Catatan -->
<div style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <div style="padding:20px; border-bottom:1px solid #e0e0e0; background:#f8f9fa;">
        <h3 style="margin:0; color:#333; font-size:16px; font-weight:600;">
            <i class="fas fa-stopwatch"></i> Riwayat Catatan Waktu ({{ $catatans->count() }})
        </h3>
    </div>

    @if($catatans->isEmpty())
        <div style="padding:40px; text-align:center; color:#999;">
            <i class="fas fa-inbox" style="font-size:40px; display:block; margin-bottom:10px;"></i>
            Belum ada catatan waktu
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5; border-bottom:2px solid #e0e0e0;">
                        <th style="padding:12px 16px; text-align:left;">Tanggal</th>
                        <th style="padding:12px 16px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:left;">Nomor Lomba</th>
                        <th style="padding:12px 16px; text-align:center;">Kolam</th>
                        <th style="padding:12px 16px; text-align:center;">Waktu</th>
                        <th style="padding:12px 16px; text-align:left;">Event</th>
                        <th style="padding:12px 16px; text-align:left;">Lokasi</th>
                        <th style="padding:12px 16px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($catatans as $catatan)
                        <tr style="border-bottom:1px solid #f0f0f0; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                            <td style="padding:10px 16px;">{{ $catatan->tanggal->format('d/m/Y') }}</td>
                            <td style="padding:10px 16px; font-weight:600;">{{ $catatan->siswa->nama }}</td>
                            <td style="padding:10px 16px;">{{ $catatan->nomor_lomba }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                <span style="background:#e3f2fd; color:#1976d2; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                    {{ $catatan->jenis_kolam }}
                                </span>
                            </td>
                            <td style="padding:10px 16px; text-align:center; font-weight:700; color:#d32f2f; font-size:16px;">
                                {{ $catatan->waktu }}
                            </td>
                            <td style="padding:10px 16px;">
                                <span style="background:{{ $catatan->jenis_event == 'kejuaraan' ? '#ffd700' : ($catatan->jenis_event == 'time_trial' ? '#ff9800' : '#e0e0e0') }}; color:{{ $catatan->jenis_event == 'latihan' ? '#666' : 'white' }}; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                    {{ ucfirst(str_replace('_', ' ', $catatan->jenis_event)) }}
                                </span>
                            </td>
                            <td style="padding:10px 16px; color:#666; font-size:12px;">{{ $catatan->lokasi ?? '-' }}</td>
                            <td style="padding:10px 16px; text-align:center;">
                                <div style="display:flex;gap:5px;justify-content:center;">
                                    <button onclick="openEditModal({{ $catatan->id }}, '{{ $catatan->siswa_id }}', '{{ $catatan->tanggal->format('Y-m-d') }}', '{{ $catatan->nomor_lomba }}', '{{ $catatan->jenis_kolam }}', '{{ $catatan->waktu }}', '{{ $catatan->jenis_event }}', '{{ addslashes($catatan->lokasi ?? '') }}', '{{ addslashes($catatan->keterangan ?? '') }}')"
                                        style="padding:5px 9px; font-size:11px; border-radius:5px; background:#ff9800; color:white; border:none; cursor:pointer;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.catatan-waktu.destroy', $catatan) }}" style="display:inline;" onsubmit="return confirm('Hapus catatan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding:5px 9px; font-size:11px; border-radius:5px; background:#f44336; color:white; border:none; cursor:pointer;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:600px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;color:#333;"><i class="fas fa-edit" style="color:#ff9800;"></i> Edit Catatan Waktu</h3>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-bottom:15px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Siswa</label>
                    <select name="siswa_id" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;" id="editSiswaId">
                        @foreach($siswas as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Tanggal</label>
                    <input type="date" name="tanggal" id="editTanggal" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jenis Event</label>
                    <select name="jenis_event" id="editJenisEvent" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                        <option value="latihan">Latihan</option>
                        <option value="time_trial">Time Trial</option>
                        <option value="kejuaraan">Kejuaraan</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-bottom:15px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nomor Lomba</label>
                    <input type="text" name="nomor_lomba" id="editNomorLomba" required placeholder="50m Freestyle" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jenis Kolam</label>
                    <select name="jenis_kolam" id="editJenisKolam" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                        <option value="25m">25m (Short Course)</option>
                        <option value="50m">50m (Long Course)</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Waktu (MM:SS.MS)</label>
                    <input type="text" name="waktu" id="editWaktu" required placeholder="01:23.45" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Lokasi</label>
                    <input type="text" name="lokasi" id="editLokasi" placeholder="Nama kolam/venue" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Keterangan</label>
                    <input type="text" name="keterangan" id="editKeterangan" placeholder="Catatan tambahan" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeEditModal()" style="padding:10px 20px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:10px 20px;background:#ff9800;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, siswaId, tanggal, nomorLomba, jenisKolam, waktu, jenisEvent, lokasi, keterangan) {
    document.getElementById('editForm').action = `/admin/catatan-waktu/${id}`;
    document.getElementById('editSiswaId').value = siswaId;
    document.getElementById('editTanggal').value = tanggal;
    document.getElementById('editNomorLomba').value = nomorLomba;
    document.getElementById('editJenisKolam').value = jenisKolam;
    document.getElementById('editWaktu').value = waktu;
    document.getElementById('editJenisEvent').value = jenisEvent;
    document.getElementById('editLokasi').value = lokasi;
    document.getElementById('editKeterangan').value = keterangan;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }
document.getElementById('editModal').addEventListener('click', e => { if (e.target === document.getElementById('editModal')) closeEditModal(); });

function updateLokasiFromKolam(sel) {
    const opt = sel.options[sel.selectedIndex];
    const lokasi = opt.dataset.lokasi || '';
    const lokasiInput = document.getElementById('inputLokasi');
    if (lokasiInput && lokasi) lokasiInput.value = lokasi;
}
</script>

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
</style>
@endsection
