@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-tie"></i></div>
    <h1 class="club-title">Daftar Coach</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#4caf50,#388e3c);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $coaches->where('status','aktif')->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Coach Aktif</div>
    </div>
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $coaches->where('status','cuti')->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Sedang Cuti</div>
    </div>
    <div style="background:linear-gradient(135deg,#607d8b,#37474f);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $coaches->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Coach</div>
    </div>
</div>

<!-- Tombol Tambah -->
<div style="margin-bottom:20px;">
    <button onclick="openModal()" style="background:#d32f2f;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
        <i class="fas fa-plus"></i> Tambah Coach Baru
    </button>
</div>

<!-- Tabel Coach -->
<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">Daftar Coach ({{ $coaches->count() }})</h3>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Coach</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Spesialisasi</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Pengalaman</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Kontak</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Status</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coaches as $i => $c)
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;background:linear-gradient(135deg,#d32f2f,#b71c1c);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($c->nama,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px;">{{ $c->nama }}</div>
                                @if($c->email)<div style="font-size:11px;color:#999;">{{ $c->email }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $c->spesialisasi ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $c->pengalaman ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $c->telepon ?? '-' }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @php $sc = ['aktif'=>['#e8f5e9','#2e7d32'],'cuti'=>['#fff3e0','#e65100'],'nonaktif'=>['#f5f5f5','#757575']]; $col = $sc[$c->status] ?? ['#f5f5f5','#333']; @endphp
                        <span style="background:{{ $col[0] }};color:{{ $col[1] }};padding:3px 10px;border-radius:10px;font-size:11px;font-weight:600;">
                            {{ ucfirst($c->status) }}
                        </span>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:5px;">
                            <button onclick="openEditModal({{ $c->id }},'{{ addslashes($c->nama) }}','{{ addslashes($c->spesialisasi ?? '') }}','{{ addslashes($c->pengalaman ?? '') }}','{{ $c->telepon ?? '' }}','{{ $c->email ?? '' }}','{{ addslashes($c->bio ?? '') }}','{{ $c->status }}')"
                                style="background:#fff3e0;color:#f57c00;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.coach.destroy', $c) }}" onsubmit="return confirm('Hapus coach {{ $c->nama }}?')">
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
                        <i class="fas fa-user-tie" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
                        Belum ada coach. Klik "Tambah Coach Baru" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-plus" style="color:#d32f2f;"></i> Tambah Coach Baru</h3>
        <form method="POST" action="{{ route('admin.coach.store') }}">
            @csrf
            @include('admin.pengaturan._form-coach')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="closeModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#d32f2f;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;padding:28px;width:90%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 20px 0;font-size:17px;"><i class="fas fa-edit" style="color:#f57c00;"></i> Edit Coach</h3>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            @include('admin.pengaturan._form-coach')
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

function openEditModal(id, nama, spesialisasi, pengalaman, telepon, email, bio, status) {
    const f = document.getElementById('editForm');
    f.action = `/admin/coach/${id}`;
    f.querySelector('[name="nama"]').value = nama;
    f.querySelector('[name="spesialisasi"]').value = spesialisasi;
    f.querySelector('[name="pengalaman"]').value = pengalaman;
    f.querySelector('[name="telepon"]').value = telepon;
    f.querySelector('[name="email"]').value = email;
    f.querySelector('[name="bio"]').value = bio;
    f.querySelector('[name="status"]').value = status;
    document.getElementById('editModal').style.display = 'flex';
}

document.querySelectorAll('#addModal,#editModal').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.style.display = 'none'; });
});
</script>
@endsection
