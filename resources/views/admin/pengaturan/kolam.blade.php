@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-swimming-pool"></i></div>
    <h1 class="club-title">Pengaturan Kolam</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Info Alur -->
<div style="background:#e3f2fd;border-radius:8px;padding:12px 18px;margin-bottom:20px;border-left:4px solid #2196f3;font-size:13px;color:#1565c0;">
    <strong><i class="fas fa-info-circle"></i> Kolam digunakan di:</strong>
    Catatan Waktu Resmi (Kejuaraan/Time Trial) dan Catatan Waktu Latihan.
    Saat memilih kolam, lokasi akan otomatis terisi.
</div>

<!-- Tombol Tambah -->
<div style="margin-bottom:20px;">
    <button onclick="openModal()" style="background:#d32f2f;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
        <i class="fas fa-plus"></i> Tambah Kolam
    </button>
</div>

<!-- Tabel -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">Daftar Kolam ({{ $kolams->count() }})</h3>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Kolam</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Ukuran</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Lokasi</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Keterangan</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Status</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kolams as $i => $k)
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="font-weight:600;font-size:14px;">{{ $k->nama }}</div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($k->ukuran === '50m')
                        <span style="background:#fce4ec;color:#880e4f;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;">50m — Long Course</span>
                        @else
                        <span style="background:#e3f2fd;color:#1565c0;padding:3px 10px;border-radius:10px;font-size:11px;font-weight:700;">25m — Short Course</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#555;">{{ $k->lokasi ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#666;">{{ $k->keterangan ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($k->aktif)
                        <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;">Aktif</span>
                        @else
                        <span style="background:#f5f5f5;color:#999;padding:3px 8px;border-radius:10px;font-size:11px;font-weight:600;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:5px;">
                            <button onclick="openEditModal({{ $k->id }},'{{ addslashes($k->nama) }}','{{ $k->ukuran }}','{{ addslashes($k->lokasi ?? '') }}','{{ addslashes($k->keterangan ?? '') }}',{{ $k->aktif ? 1 : 0 }})"
                                style="background:#fff3e0;color:#f57c00;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.kolam.destroy', $k) }}" onsubmit="return confirm('Hapus kolam {{ $k->nama }}?')">
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
                        <i class="fas fa-swimming-pool" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Belum ada kolam. Klik "Tambah Kolam" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:460px;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-plus" style="color:#d32f2f;"></i> Tambah Kolam</h3>
        <form method="POST" action="{{ route('admin.kolam.store') }}">
            @csrf
            @include('admin.pengaturan._form-kolam')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#d32f2f;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:460px;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-edit" style="color:#f57c00;"></i> Edit Kolam</h3>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            @include('admin.pengaturan._form-kolam', ['edit' => true])
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

function openEditModal(id, nama, ukuran, lokasi, keterangan, aktif) {
    const f = document.getElementById('editForm');
    f.action = `/admin/kolam/${id}`;
    f.querySelector('[name="nama"]').value = nama;
    f.querySelector('[name="ukuran"]').value = ukuran;
    f.querySelector('[name="lokasi"]').value = lokasi;
    f.querySelector('[name="keterangan"]').value = keterangan;
    f.querySelector('[name="aktif"]').checked = aktif == 1;
    document.getElementById('editModal').style.display = 'flex';
}

document.querySelectorAll('#addModal,#editModal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.style.display = 'none'; });
});
</script>
@endsection
