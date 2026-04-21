@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-plus"></i></div>
    <h1 class="club-title">Daftar Siswa Baru</h1>
</div>

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:20px;border:1px solid #f5c6cb;">
    @foreach($errors->all() as $e)<div><i class="fas fa-exclamation-circle"></i> {{ $e }}</div>@endforeach
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:25px;align-items:start;">

    <!-- Form Pendaftaran -->
    <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
        <div style="padding:18px 24px;border-bottom:1px solid #f0f0f0;background:linear-gradient(135deg,#d32f2f,#b71c1c);">
            <h3 style="margin:0;font-size:16px;font-weight:600;color:white;"><i class="fas fa-clipboard-list"></i> Form Pendaftaran Siswa Baru</h3>
        </div>
        <form action="{{ route('admin.daftar-baru.store') }}" method="POST" style="padding:24px;display:grid;gap:18px;">
            @csrf

            {{-- Data Siswa --}}
            <div style="font-size:12px;font-weight:700;color:#d32f2f;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #f0f0f0;padding-bottom:8px;">
                Data Siswa
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Lengkap <span style="color:red">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Nama lengkap siswa"
                        style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Tanggal Lahir <span style="color:red">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                        style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jenis Kelamin <span style="color:red">*</span></label>
                    <select name="jenis_kelamin" required style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Alamat <span style="color:red">*</span></label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" required placeholder="Alamat lengkap"
                        style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
            </div>

            {{-- Pilih Kelas --}}
            <div style="font-size:12px;font-weight:700;color:#d32f2f;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #f0f0f0;padding-bottom:8px;margin-top:4px;">
                Pilih Kelas
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:8px;">Kelas Renang <span style="color:red">*</span></label>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;" id="kelasGrid">
                    @foreach($kelasList as $k)
                    @php
                        $colors = \App\Models\Kelas::$levelColors[$k->level] ?? ['#f5f5f5','#333'];
                        $jumlah = \App\Models\Siswa::where('status','aktif')->where('kelas',$k->nama_kelas)->count();
                        $sisa = $k->kapasitas - $jumlah;
                    @endphp
                    <label style="cursor:pointer;">
                        <input type="radio" name="kelas" value="{{ $k->nama_kelas }}" {{ old('kelas')==$k->nama_kelas?'checked':'' }}
                            style="display:none;" onchange="updateKelasInfo({{ $k->id }})">
                        <div class="kelas-card" data-id="{{ $k->id }}"
                            style="border:2px solid #e0e0e0;border-radius:10px;padding:14px;transition:all .2s;position:relative;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                                <div style="font-size:15px;font-weight:700;color:#333;">{{ $k->nama_kelas }}</div>
                                <span style="background:{{ $colors[0] }};color:{{ $colors[1] }};padding:2px 7px;border-radius:8px;font-size:10px;font-weight:700;">{{ strtoupper($k->level_label) }}</span>
                            </div>
                            <div style="font-size:18px;font-weight:700;color:#d32f2f;margin-bottom:4px;">Rp {{ number_format($k->harga,0,',','.') }}<span style="font-size:11px;color:#999;font-weight:400;">/bln</span></div>
                            <div style="font-size:11px;color:#666;">
                                <i class="fas fa-users"></i> {{ $jumlah }}/{{ $k->kapasitas }} siswa
                                @if($k->coach) &bull; <i class="fas fa-user-tie"></i> {{ $k->coach->nama }} @endif
                            </div>
                            @if($k->jadwal_string !== '-')
                            <div style="font-size:10px;color:#999;margin-top:3px;"><i class="fas fa-clock"></i> {{ $k->jadwal_string }}</div>
                            @endif
                            @if($sisa <= 0)
                            <div style="position:absolute;inset:0;background:rgba(255,255,255,.8);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <span style="background:#f44336;color:white;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;">PENUH</span>
                            </div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
                @if($kelasList->isEmpty())
                <div style="padding:20px;background:#fff3e0;border-radius:8px;color:#e65100;font-size:13px;">
                    <i class="fas fa-exclamation-triangle"></i> Belum ada kelas aktif.
                    <a href="{{ route('admin.kelas') }}" style="color:#d32f2f;font-weight:600;">Tambah kelas di Pengaturan</a>
                </div>
                @endif
            </div>

            {{-- Info Kelas Terpilih --}}
            <div id="kelasInfo" style="display:none;background:#e3f2fd;border-radius:8px;padding:12px 16px;border-left:4px solid #2196f3;">
                <div style="font-size:13px;font-weight:600;color:#1565c0;" id="kelasInfoText"></div>
            </div>

            {{-- Paket --}}
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Paket <span style="color:red">*</span></label>
                <select name="paket" required style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="">Pilih paket...</option>
                    <option value="Reguler" {{ old('paket')=='Reguler'?'selected':'' }}>Reguler (Bulanan)</option>
                    <option value="8x" {{ old('paket')=='8x'?'selected':'' }}>8x Pertemuan</option>
                    <option value="12x" {{ old('paket')=='12x'?'selected':'' }}>12x Pertemuan</option>
                </select>
            </div>

            {{-- Data Orang Tua --}}
            <div style="font-size:12px;font-weight:700;color:#d32f2f;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #f0f0f0;padding-bottom:8px;margin-top:4px;">
                Data Orang Tua / Wali
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Orang Tua <span style="color:red">*</span></label>
                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required placeholder="Nama orang tua/wali"
                        style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">No. Telepon <span style="color:red">*</span></label>
                    <input type="tel" name="telepon" value="{{ old('telepon') }}" required placeholder="08xxxxxxxxxx"
                        style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                    style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Catatan Khusus</label>
                <textarea name="catatan" rows="2" placeholder="Kondisi kesehatan, alergi, atau catatan lainnya..."
                    style="width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;resize:vertical;">{{ old('catatan') }}</textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('admin.calon-siswa') }}" style="padding:10px 20px;border:1px solid #ddd;background:white;border-radius:8px;font-size:14px;text-decoration:none;color:#666;">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" style="padding:10px 24px;background:#d32f2f;color:white;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-save"></i> Simpan Pendaftaran
                </button>
            </div>
        </form>
    </div>

    <!-- Panel Info Kelas -->
    <div style="position:sticky;top:20px;">
        <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;margin-bottom:15px;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h3 style="margin:0;font-size:14px;font-weight:600;"><i class="fas fa-tags" style="color:#d32f2f;"></i> Daftar Harga Kelas</h3>
            </div>
            <div style="padding:12px;">
                @foreach($kelasList->sortBy('harga') as $k)
                @php $colors = \App\Models\Kelas::$levelColors[$k->level] ?? ['#f5f5f5','#333']; @endphp
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 10px;border-radius:6px;margin-bottom:5px;background:#fafafa;">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $k->nama_kelas }}</div>
                        <span style="background:{{ $colors[0] }};color:{{ $colors[1] }};padding:1px 6px;border-radius:6px;font-size:10px;font-weight:600;">{{ $k->level_label }}</span>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:14px;font-weight:700;color:#d32f2f;">Rp {{ number_format($k->harga,0,',','.') }}</div>
                        <div style="font-size:10px;color:#999;">per bulan</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h3 style="margin:0;font-size:14px;font-weight:600;"><i class="fas fa-user-tie" style="color:#d32f2f;"></i> Daftar Coach</h3>
            </div>
            <div style="padding:12px;">
                @foreach($kelasList->filter(fn($k) => $k->coach)->unique('coach_id') as $k)
                <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:6px;margin-bottom:5px;background:#fafafa;">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#d32f2f,#b71c1c);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:12px;flex-shrink:0;">
                        {{ strtoupper(substr($k->coach->nama,0,1)) }}
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;">{{ $k->coach->nama }}</div>
                        <div style="font-size:10px;color:#999;">{{ $k->nama_kelas }} &bull; {{ $k->coach->spesialisasi ?? '' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
// Data kelas untuk JS
const kelasData = {
    @foreach($kelasList as $k)
    {{ $k->id }}: {
        nama: '{{ $k->nama_kelas }}',
        harga: {{ $k->harga }},
        coach: '{{ $k->coach->nama ?? "-" }}',
        jadwal: '{{ $k->jadwal_string }}',
        level: '{{ $k->level_label }}',
    },
    @endforeach
};

function updateKelasInfo(id) {
    const k = kelasData[id];
    if (!k) return;

    // Highlight selected card
    document.querySelectorAll('.kelas-card').forEach(c => {
        c.style.border = '2px solid #e0e0e0';
        c.style.background = 'white';
    });
    const selected = document.querySelector(`.kelas-card[data-id="${id}"]`);
    if (selected) {
        selected.style.border = '2px solid #d32f2f';
        selected.style.background = '#fff5f5';
    }

    // Show info
    const info = document.getElementById('kelasInfo');
    const text = document.getElementById('kelasInfoText');
    text.innerHTML = `<i class="fas fa-info-circle"></i> Kelas <strong>${k.nama}</strong> (${k.level}) — Iuran: <strong>Rp ${k.harga.toLocaleString('id-ID')}/bulan</strong> | Coach: ${k.coach} | Jadwal: ${k.jadwal}`;
    info.style.display = 'block';
}

// Restore selection on page load (old input)
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="kelas"]:checked');
    if (checked) {
        const card = checked.closest('label').querySelector('.kelas-card');
        if (card) updateKelasInfo(parseInt(card.dataset.id));
    }
});
</script>
@endsection
