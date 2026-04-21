@extends('layouts.admin')
@section('content')

<h1 style="color:#d32f2f;font-size:26px;font-weight:700;margin-bottom:20px;font-style:italic;">CATATAN WAKTU LATIHAN</h1>

@if(session('success'))
<div style="margin-bottom:16px;padding:12px 16px;background:#d4edda;color:#155724;border-radius:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Alur Info -->
<div style="background:#e8f5e9;border-radius:8px;padding:12px 18px;margin-bottom:20px;border-left:4px solid #4caf50;font-size:13px;color:#2e7d32;">
    <strong><i class="fas fa-info-circle"></i> Catatan Waktu Latihan</strong> — Digunakan untuk mencatat waktu saat sesi latihan harian.
    Berbeda dengan <a href="{{ route('admin.catatan-waktu') }}" style="color:#d32f2f;font-weight:600;">Catatan Waktu Resmi</a> yang untuk kejuaraan/time trial.
    Data ini digunakan untuk memantau perkembangan siswa di <a href="{{ route('admin.progress-report') }}" style="color:#d32f2f;font-weight:600;">Progress Report</a>.
</div>

<!-- Form Input -->
<div style="background:white;padding:22px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
    <h3 style="margin:0 0 18px 0;font-size:16px;font-weight:600;color:#333;">
        <i class="fas fa-plus-circle" style="color:#4caf50;"></i> Input Catatan Latihan
    </h3>
    <form method="POST" action="{{ route('admin.catatan-waktu-latihan.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:15px;margin-bottom:15px;">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Siswa <span style="color:red">*</span></label>
                <select name="siswa_id" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Tanggal <span style="color:red">*</span></label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                    style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jenis Latihan <span style="color:red">*</span></label>
                <select name="jenis_latihan" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="teknik">Teknik</option>
                    <option value="speed">Speed</option>
                    <option value="endurance">Endurance</option>
                    <option value="test_set">Test Set</option>
                    <option value="drill">Drill</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nomor Lomba</label>
                <select name="nomor_lomba" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="">-- Pilih --</option>
                    <optgroup label="Freestyle">
                        <option value="50m Freestyle">50m Freestyle</option>
                        <option value="100m Freestyle">100m Freestyle</option>
                        <option value="200m Freestyle">200m Freestyle</option>
                    </optgroup>
                    <optgroup label="Backstroke">
                        <option value="50m Backstroke">50m Backstroke</option>
                        <option value="100m Backstroke">100m Backstroke</option>
                    </optgroup>
                    <optgroup label="Breaststroke">
                        <option value="50m Breaststroke">50m Breaststroke</option>
                        <option value="100m Breaststroke">100m Breaststroke</option>
                    </optgroup>
                    <optgroup label="Butterfly">
                        <option value="50m Butterfly">50m Butterfly</option>
                        <option value="100m Butterfly">100m Butterfly</option>
                    </optgroup>
                    <optgroup label="IM">
                        <option value="200m IM">200m IM</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 2fr;gap:15px;margin-bottom:15px;">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Set / Jarak</label>
                <input type="text" name="set_jarak" placeholder="Contoh: 4x50m" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Waktu (MM:SS.MS)</label>
                <input type="text" name="waktu" placeholder="01:23.45" pattern="[0-9]{1,2}:[0-9]{2}\.[0-9]{2}"
                    style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                <small style="color:#999;font-size:10px;">Opsional — isi jika ada catatan waktu</small>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Catatan Coach</label>
                <input type="text" name="catatan" placeholder="Catatan teknik, perbaikan, dll..." style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="reset" style="padding:8px 18px;border:1px solid #ddd;background:white;border-radius:6px;cursor:pointer;font-size:13px;">Reset</button>
            <button type="submit" style="padding:8px 18px;background:#4caf50;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;font-size:13px;">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

<!-- Filter -->
<div style="background:white;padding:16px 20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.07);">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Siswa</label>
            <select name="siswa_id" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Siswa</option>
                @foreach($siswas as $s)
                <option value="{{ $s->id }}" {{ request('siswa_id')==$s->id?'selected':'' }}>{{ $s->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Kelas</label>
            <select name="kelas" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ request('kelas')==$k?'selected':'' }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Jenis</label>
            <select name="jenis" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="teknik" {{ request('jenis')=='teknik'?'selected':'' }}>Teknik</option>
                <option value="speed" {{ request('jenis')=='speed'?'selected':'' }}>Speed</option>
                <option value="endurance" {{ request('jenis')=='endurance'?'selected':'' }}>Endurance</option>
                <option value="test_set" {{ request('jenis')=='test_set'?'selected':'' }}>Test Set</option>
                <option value="drill" {{ request('jenis')=='drill'?'selected':'' }}>Drill</option>
            </select>
        </div>
        <a href="{{ route('admin.catatan-waktu-latihan') }}" style="padding:7px 14px;border:1px solid #ddd;border-radius:6px;font-size:13px;text-decoration:none;color:#666;">Reset</a>
    </form>
</div>

<!-- Tabel -->
<div style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07);">
    <div style="padding:14px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="margin:0;font-size:15px;font-weight:600;"><i class="fas fa-clock" style="color:#4caf50;"></i> Riwayat Latihan ({{ $latihans->count() }})</h3>
    </div>
    @if($latihans->isEmpty())
    <div style="padding:50px;text-align:center;color:#999;">
        <i class="fas fa-swimming-pool" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
        Belum ada catatan latihan
    </div>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Tanggal</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Siswa</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Kelas</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Jenis</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Nomor</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Set/Jarak</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Waktu</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Catatan</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($latihans as $l)
                @php
                    $jenisColors = ['teknik'=>['#e3f2fd','#1565c0'],'speed'=>['#fce4ec','#880e4f'],'endurance'=>['#e8f5e9','#2e7d32'],'test_set'=>['#fff3e0','#e65100'],'drill'=>['#f3e5f5','#6a1b9a']];
                    $c = $jenisColors[$l->jenis_latihan] ?? ['#f5f5f5','#666'];
                @endphp
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;">{{ $l->tanggal->format('d M Y') }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-weight:600;">{{ $l->siswa->nama }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;">{{ $l->kelas }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;">
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:2px 8px;border-radius:8px;font-size:10px;font-weight:700;">{{ strtoupper($l->jenis_latihan) }}</span>
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;">{{ $l->nomor_lomba ?? '-' }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;">{{ $l->set_jarak ?? '-' }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;font-weight:700;color:#d32f2f;font-size:15px;">
                        {{ $l->waktu ?? '-' }}
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;max-width:180px;">{{ $l->catatan ?? '-' }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <div style="display:flex;gap:5px;justify-content:center;">
                            <button onclick="openEditLatihan({{ $l->id }},'{{ $l->siswa_id }}','{{ $l->tanggal->format('Y-m-d') }}','{{ $l->jenis_latihan }}','{{ $l->nomor_lomba ?? '' }}','{{ $l->set_jarak ?? '' }}','{{ $l->waktu ?? '' }}','{{ addslashes($l->catatan ?? '') }}')"
                                style="background:#ff9800;color:white;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.catatan-waktu-latihan.destroy', $l) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#ffebee;color:#f44336;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
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

<!-- Modal Edit -->
<div id="editLatihanModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:26px;width:90%;max-width:600px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 18px 0;font-size:16px;"><i class="fas fa-edit" style="color:#ff9800;"></i> Edit Catatan Latihan</h3>
        <form id="editLatihanForm" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Siswa</label>
                    <select name="siswa_id" id="elSiswaId" required style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                        @foreach($siswas as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Tanggal</label>
                    <input type="date" name="tanggal" id="elTanggal" required style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Jenis Latihan</label>
                    <select name="jenis_latihan" id="elJenis" required style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                        <option value="teknik">Teknik</option>
                        <option value="speed">Speed</option>
                        <option value="endurance">Endurance</option>
                        <option value="test_set">Test Set</option>
                        <option value="drill">Drill</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Nomor Lomba</label>
                    <input type="text" name="nomor_lomba" id="elNomor" placeholder="50m Freestyle" style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Set / Jarak</label>
                    <input type="text" name="set_jarak" id="elSet" placeholder="4x50m" style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Waktu</label>
                    <input type="text" name="waktu" id="elWaktu" placeholder="01:23.45" style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Catatan</label>
                <input type="text" name="catatan" id="elCatatan" style="width:100%;padding:7px 9px;border:1px solid #ddd;border-radius:6px;font-size:12px;box-sizing:border-box;">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:18px;">
                <button type="button" onclick="closeEditLatihan()" style="padding:9px 18px;border:1px solid #ddd;background:white;border-radius:7px;cursor:pointer;">Batal</button>
                <button type="submit" style="padding:9px 18px;background:#ff9800;color:white;border:none;border-radius:7px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditLatihan(id, siswaId, tanggal, jenis, nomor, set, waktu, catatan) {
    document.getElementById('editLatihanForm').action = `/admin/catatan-waktu-latihan/${id}`;
    document.getElementById('elSiswaId').value = siswaId;
    document.getElementById('elTanggal').value = tanggal;
    document.getElementById('elJenis').value = jenis;
    document.getElementById('elNomor').value = nomor;
    document.getElementById('elSet').value = set;
    document.getElementById('elWaktu').value = waktu;
    document.getElementById('elCatatan').value = catatan;
    document.getElementById('editLatihanModal').style.display = 'flex';
}
function closeEditLatihan() { document.getElementById('editLatihanModal').style.display = 'none'; }
document.getElementById('editLatihanModal').addEventListener('click', e => { if (e.target === document.getElementById('editLatihanModal')) closeEditLatihan(); });
</script>
@endsection
