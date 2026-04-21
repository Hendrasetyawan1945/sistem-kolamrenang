@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-chalkboard"></i></div>
    <h1 class="club-title">Pengaturan Kelas</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #f5c6cb;">
    @foreach($errors->all() as $e) <div><i class="fas fa-exclamation-circle"></i> {{ $e }}</div> @endforeach
</div>
@endif

<!-- Tombol Tambah -->
<div style="margin-bottom:20px;">
    <button onclick="openModal()" style="background:#d32f2f;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
        <i class="fas fa-plus"></i> Tambah Kelas Baru
    </button>
</div>

<!-- Tabel Kelas -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">Daftar Kelas ({{ $kelasList->count() }})</h3>
        <span style="font-size:13px;color:#666;">Total harga rata-rata: Rp {{ number_format($kelasList->avg('harga'),0,',','.') }}/bln</span>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Kelas</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Level</th>
                    <th style="padding:11px 14px;text-align:center;font-size:12px;color:#666;border-bottom:1px solid #eee;">Kapasitas</th>
                    <th style="padding:11px 14px;text-align:center;font-size:12px;color:#666;border-bottom:1px solid #eee;">Siswa Aktif</th>
                    <th style="padding:11px 14px;text-align:right;font-size:12px;color:#666;border-bottom:1px solid #eee;">Harga/Bulan</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Deskripsi</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Status</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelasList as $i => $k)
                @php
                    $jumlahSiswa = $siswaPerKelas[$k->nama_kelas] ?? 0;
                    $pct = $k->kapasitas > 0 ? ($jumlahSiswa / $k->kapasitas * 100) : 0;
                    $colors = \App\Models\Kelas::$levelColors[$k->level] ?? ['#f5f5f5','#333'];
                @endphp
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <strong style="font-size:14px;">{{ $k->nama_kelas }}</strong>
                        @if($k->coach)
                        <div style="font-size:11px;color:#666;margin-top:2px;"><i class="fas fa-user-tie"></i> {{ $k->coach->nama }}</div>
                        @endif
                        @if($k->jadwal_string !== '-')
                        <div style="font-size:10px;color:#999;margin-top:1px;"><i class="fas fa-clock"></i> {{ $k->jadwal_string }}</div>
                        @endif
                    </td>                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <span style="background:{{ $colors[0] }};color:{{ $colors[1] }};padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;">
                            {{ $k->level_label }}
                        </span>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:center;font-size:13px;">{{ $k->kapasitas }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <div style="font-size:13px;font-weight:600;color:{{ $pct>100?'#f44336':($pct>80?'#ff9800':'#4caf50') }};">
                            {{ $jumlahSiswa }} / {{ $k->kapasitas }}
                        </div>
                        <div style="background:#f0f0f0;border-radius:3px;height:4px;margin-top:4px;overflow:hidden;">
                            <div style="background:{{ $pct>100?'#f44336':($pct>80?'#ff9800':'#4caf50') }};height:4px;width:{{ min($pct,100) }}%;"></div>
                        </div>
                        @if($pct > 100)
                            <div style="font-size:10px;color:#f44336;">Overload</div>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;text-align:right;font-size:13px;font-weight:600;color:#d32f2f;">
                        Rp {{ number_format($k->harga,0,',','.') }}
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;max-width:200px;">
                        {{ $k->deskripsi ? \Str::limit($k->deskripsi, 50) : '-' }}
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($k->aktif)
                            <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;">Aktif</span>
                        @else
                            <span style="background:#f5f5f5;color:#999;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:5px;">
                            <button onclick="openEditModal({{ $k->id }},'{{ $k->nama_kelas }}','{{ $k->level }}',{{ $k->kapasitas }},{{ $k->harga }},'{{ addslashes($k->deskripsi ?? '') }}',{{ $k->aktif ? 1 : 0 }})"
                                style="background:#fff3e0;color:#f57c00;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.kelas.destroy', $k) }}" onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?')">
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
                    <td colspan="9" style="padding:50px;text-align:center;color:#999;">
                        <i class="fas fa-chalkboard" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Belum ada kelas. Klik "Tambah Kelas Baru" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Daftar Harga -->
@if($kelasList->count() > 0)
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;margin-top:20px;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;background:linear-gradient(135deg,#d32f2f,#b71c1c);">
        <h3 style="margin:0;font-size:15px;font-weight:600;color:white;"><i class="fas fa-tags"></i> Daftar Harga Kelas</h3>
    </div>
    <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:15px;">
        @foreach($kelasList->where('aktif', true)->sortBy('harga') as $k)
        @php $colors = \App\Models\Kelas::$levelColors[$k->level] ?? ['#f5f5f5','#333']; @endphp
        <div style="border:2px solid {{ $colors[0] }};border-radius:10px;padding:16px;text-align:center;position:relative;">
            <div style="background:{{ $colors[0] }};color:{{ $colors[1] }};padding:3px 10px;border-radius:10px;font-size:10px;font-weight:700;display:inline-block;margin-bottom:8px;">
                {{ strtoupper($k->level_label) }}
            </div>
            <div style="font-size:18px;font-weight:700;color:#333;margin-bottom:4px;">{{ $k->nama_kelas }}</div>
            <div style="font-size:22px;font-weight:700;color:#d32f2f;">Rp {{ number_format($k->harga,0,',','.') }}</div>
            <div style="font-size:11px;color:#999;margin-top:2px;">per bulan</div>
            <div style="font-size:11px;color:#666;margin-top:8px;">Kapasitas: {{ $k->kapasitas }} siswa</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Modal Tambah -->
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-plus" style="color:#d32f2f;"></i> Tambah Kelas Baru</h3>
        <form method="POST" action="{{ route('admin.kelas.store') }}">
            @csrf
            @include('admin.pengaturan._form-kelas')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#d32f2f;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-edit" style="color:#f57c00;"></i> Edit Kelas</h3>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            @include('admin.pengaturan._form-kelas', ['edit' => true])
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

function openEditModal(id, nama, level, kapasitas, harga, deskripsi, aktif) {
    const f = document.getElementById('editForm');
    f.action = `/admin/kelas/${id}`;
    f.querySelector('[name="nama_kelas"]').value = nama;
    f.querySelector('[name="level"]').value = level;
    f.querySelector('[name="kapasitas"]').value = kapasitas;
    f.querySelector('[name="harga"]').value = harga;
    f.querySelector('[name="deskripsi"]').value = deskripsi;
    f.querySelector('[name="aktif"]').checked = aktif == 1;
    document.getElementById('editModal').style.display = 'flex';
}

document.querySelectorAll('#addModal,#editModal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.style.display = 'none'; });
});
</script>
@endsection
