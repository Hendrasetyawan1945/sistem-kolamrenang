@extends('layouts.admin')
@section('content')

<div class="club-header">
    <div class="club-logo"><i class="fas fa-user-plus"></i></div>
    <h1 class="club-title">Calon Siswa</h1>
</div>

@include('admin.siswa._alert')

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#ff9800,#e65100);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:26px;font-weight:700;">{{ $siswas->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Calon Siswa</div>
    </div>
    <div style="background:linear-gradient(135deg,#f44336,#c62828);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:26px;font-weight:700;">{{ $siswas->filter(fn($s) => !$kelasHarga->has($s->kelas))->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Kelas Belum Tersedia</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#2e7d32);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:26px;font-weight:700;">{{ $siswas->filter(fn($s) => $kelasHarga->has($s->kelas))->count() }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Siap Diaktifkan</div>
    </div>
</div>

<!-- Alur Info -->
<div style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);border-radius:10px;padding:16px 20px;margin-bottom:22px;border-left:4px solid #2196f3;">
    <div style="font-size:13px;font-weight:700;color:#1565c0;margin-bottom:8px;"><i class="fas fa-info-circle"></i> Alur Aktivasi Siswa (Fitur Baru!)</div>
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:12px;color:#1565c0;">
        <span style="background:#1565c0;color:white;padding:4px 12px;border-radius:20px;font-weight:600;">1. Daftar</span>
        <i class="fas fa-arrow-right"></i>
        <span style="background:#ff9800;color:white;padding:4px 12px;border-radius:20px;font-weight:600;">2. Calon Siswa</span>
        <i class="fas fa-arrow-right"></i>
        <span style="background:#4caf50;color:white;padding:4px 12px;border-radius:20px;font-weight:600;">3. Aktifkan + Buat Akun</span>
        <i class="fas fa-arrow-right"></i>
        <span style="background:#2196f3;color:white;padding:4px 12px;border-radius:20px;font-weight:600;">4. Siswa Aktif</span>
        <i class="fas fa-arrow-right"></i>
        <span style="background:#9c27b0;color:white;padding:4px 12px;border-radius:20px;font-weight:600;">5. Login ke Portal</span>
    </div>
    <div style="margin-top:8px;font-size:11px;color:#1565c0;background:rgba(255,255,255,0.7);padding:8px;border-radius:6px;">
        <i class="fas fa-star"></i> <strong>Baru:</strong> Sekarang bisa langsung buat akun login saat aktivasi siswa!
    </div>
</div>

<div style="background:white;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.07);overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="margin:0;font-size:15px;font-weight:600;">Daftar Calon Siswa ({{ $siswas->count() }})</h3>
        <a href="{{ route('admin.daftar-baru') }}" style="background:#d32f2f;color:white;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
    </div>

    @if($siswas->isEmpty())
    <div style="padding:50px;text-align:center;color:#999;">
        <i class="fas fa-user-plus" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
        <h5>Tidak Ada Calon Siswa</h5>
        <p>Semua siswa sudah diaktifkan atau belum ada pendaftaran baru</p>

    </div>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">No</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Nama Siswa</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Tgl Lahir</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Kelas</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Iuran/Bln</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Orang Tua</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Telepon</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Status Akun</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Daftar</th>
                    <th style="padding:11px 14px;text-align:left;font-size:12px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswas as $i => $s)
                @php $hargaKelas = $kelasHarga->get($s->kelas, 0); @endphp
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;color:#999;">{{ $i+1 }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="font-weight:600;font-size:14px;">{{ $s->nama }}</div>
                        <div style="font-size:11px;color:#999;">{{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">
                        {{ \Carbon\Carbon::parse($s->tanggal_lahir)->format('d M Y') }}
                        <div style="font-size:11px;color:#999;">{{ \Carbon\Carbon::parse($s->tanggal_lahir)->age }} tahun</div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <span style="background:#e3f2fd;color:#1565c0;padding:3px 8px;border-radius:8px;font-size:12px;font-weight:600;">{{ $s->kelas }}</span>
                        <div style="font-size:11px;color:#999;margin-top:2px;">{{ $s->paket }}</div>
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;font-weight:600;color:{{ $hargaKelas > 0 ? '#2e7d32' : '#999' }};">
                        @if($hargaKelas > 0)
                            Rp {{ number_format($hargaKelas,0,',','.') }}
                        @else
                            <span style="color:#f44336;font-size:11px;">Kelas belum ada</span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $s->nama_ortu }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:13px;">{{ $s->telepon }}</td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        @if($s->user)
                            <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:8px;font-size:11px;font-weight:600;">
                                <i class="fas fa-user-check"></i> Akun Sudah Dibuat
                            </span>
                            <div style="font-size:10px;color:#666;margin-top:2px;">{{ $s->email }}</div>
                        @else
                            <span style="background:#ffebee;color:#c62828;padding:3px 8px;border-radius:8px;font-size:11px;font-weight:600;">
                                <i class="fas fa-user-times"></i> Belum Ada Akun
                            </span>
                        @endif
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;color:#999;">
                        {{ $s->created_at->format('d M Y') }}
                    </td>
                    <td style="padding:11px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            {{-- Tombol Aktivasi --}}
                            <button onclick="openAktivasi({{ $s->id }}, '{{ addslashes($s->nama) }}', '{{ $s->kelas }}', {{ $hargaKelas }})"
                                style="background:#4caf50;color:white;border:none;padding:5px 10px;border-radius:5px;font-size:11px;cursor:pointer;font-weight:600;">
                                <i class="fas fa-check"></i> Aktifkan
                            </button>
                            <a href="{{ route('admin.siswa.edit', $s) }}"
                                style="background:#2196f3;color:white;padding:5px 10px;border-radius:5px;font-size:11px;text-decoration:none;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}" onsubmit="return confirm('Hapus {{ $s->nama }}?')">
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

<!-- Modal Aktivasi -->
<div id="aktivasiModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;width:90%;max-width:480px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <div style="background:linear-gradient(135deg,#4caf50,#2e7d32);color:white;padding:18px 22px;">
            <h3 style="margin:0;font-size:16px;font-weight:700;"><i class="fas fa-user-check"></i> Aktivasi Siswa</h3>
            <p id="aktivasiSubtitle" style="margin:4px 0 0 0;font-size:13px;opacity:.85;"></p>
        </div>
        <form id="aktivasiForm" method="POST" style="padding:22px;display:grid;gap:16px;">
            @csrf

            <!-- Info Kelas -->
            <div id="kelasInfoBox" style="background:#e8f5e9;border-radius:8px;padding:12px 16px;border-left:4px solid #4caf50;">
                <div style="font-size:13px;color:#2e7d32;" id="kelasInfoText"></div>
            </div>

            <!-- Bayar Pendaftaran? -->
            <div>
                <label style="display:block;font-size:13px;font-weight:700;margin-bottom:10px;color:#333;">
                    Apakah ada biaya pendaftaran yang dibayar?
                </label>
                <div style="display:flex;gap:10px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:2px solid #ddd;border-radius:8px;flex:1;transition:all .2s;" id="labelYa">
                        <input type="radio" name="bayar_pendaftaran" value="ya" onchange="toggleBayar(true)" style="width:16px;height:16px;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#2e7d32;">Ya, Sudah Bayar</div>
                            <div style="font-size:11px;color:#999;">Catat pembayaran pendaftaran</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:2px solid #ddd;border-radius:8px;flex:1;transition:all .2s;" id="labelTidak">
                        <input type="radio" name="bayar_pendaftaran" value="tidak" onchange="toggleBayar(false)" style="width:16px;height:16px;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#666;">Belum / Gratis</div>
                            <div style="font-size:11px;color:#999;">Aktifkan tanpa catat pembayaran</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Buat Akun Login? -->
            <div>
                <label style="display:block;font-size:13px;font-weight:700;margin-bottom:10px;color:#333;">
                    <i class="fas fa-user-plus"></i> Buat akun login untuk siswa?
                </label>
                <div style="display:flex;gap:10px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:2px solid #ddd;border-radius:8px;flex:1;transition:all .2s;" id="labelBuatAkun">
                        <input type="radio" name="buat_akun" value="ya" onchange="toggleAkun(true)" style="width:16px;height:16px;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#2196f3;">Ya, Buat Akun</div>
                            <div style="font-size:11px;color:#999;">Siswa bisa login ke portal</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:2px solid #ddd;border-radius:8px;flex:1;transition:all .2s;" id="labelTidakAkun">
                        <input type="radio" name="buat_akun" value="tidak" onchange="toggleAkun(false)" style="width:16px;height:16px;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#666;">Nanti Saja</div>
                            <div style="font-size:11px;color:#999;">Buat akun di lain waktu</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Form Akun (hidden by default) -->
            <div id="formAkun" style="display:none;background:#e3f2fd;border-radius:8px;padding:16px;gap:12px;flex-direction:column;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Email Login <span style="color:red">*</span></label>
                    <input type="email" name="email_akun" id="emailAkun" placeholder="Masukkan email untuk login"
                        style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <div style="font-size:11px;color:#666;margin-top:2px;">Email ini akan digunakan untuk login ke portal siswa</div>
                </div>
                
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:8px;">Jenis Password</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px;border:1px solid #ddd;border-radius:6px;font-size:11px;">
                            <input type="radio" name="password_type" value="default" checked style="width:14px;height:14px;">
                            <div>
                                <div style="font-weight:600;">Default</div>
                                <div style="color:#999;">123456</div>
                            </div>
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px;border:1px solid #ddd;border-radius:6px;font-size:11px;">
                            <input type="radio" name="password_type" value="tanggal_lahir" style="width:14px;height:14px;">
                            <div>
                                <div style="font-weight:600;">Tgl Lahir</div>
                                <div style="color:#999;" id="previewTglLahir">ddmmyyyy</div>
                            </div>
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px;border:1px solid #ddd;border-radius:6px;font-size:11px;">
                            <input type="radio" name="password_type" value="custom" onchange="toggleCustomPassword()" style="width:14px;height:14px;">
                            <div>
                                <div style="font-weight:600;">Custom</div>
                                <div style="color:#999;">Atur sendiri</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="customPasswordBox" style="display:none;">
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Password Custom <span style="color:red">*</span></label>
                    <input type="text" name="custom_password" placeholder="Minimal 6 karakter"
                        style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                </div>

                <div style="background:#fff3e0;border-radius:6px;padding:10px 12px;font-size:12px;color:#e65100;">
                    <i class="fas fa-info-circle"></i> Akun akan langsung bisa digunakan untuk login ke portal siswa. Password bisa diubah nanti oleh admin.
                </div>
            </div>

            <!-- Form Pembayaran (hidden by default) -->
            <div id="formBayar" style="display:none;background:#f9fbe7;border-radius:8px;padding:16px;display:none;gap:12px;flex-direction:column;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Jumlah Bayar (Rp) <span style="color:red">*</span></label>
                        <input type="number" name="jumlah_pendaftaran" id="jumlahPendaftaran" min="0" step="10000"
                            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Tanggal Bayar <span style="color:red">*</span></label>
                        <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}"
                            style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    </div>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Metode Pembayaran</label>
                    <select name="metode_pembayaran" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                        <option value="Tunai">Tunai</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="QRIS">QRIS</option>
                        <option value="GoPay">GoPay</option>
                        <option value="OVO">OVO</option>
                    </select>
                </div>
                <div style="background:#fff3e0;border-radius:6px;padding:10px 12px;font-size:12px;color:#e65100;">
                    <i class="fas fa-lightbulb"></i> Pembayaran ini akan otomatis masuk ke <strong>Laporan Keuangan</strong> sebagai pendapatan biaya pendaftaran.
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="button" onclick="closeAktivasi()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;font-size:14px;">
                    Batal
                </button>
                <button type="submit" style="flex:2;padding:10px;background:#4caf50;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:14px;">
                    <i class="fas fa-check"></i> Aktifkan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAktivasi(id, nama, kelas, harga) {
    document.getElementById('aktivasiForm').action = `/admin/siswa/${id}/aktivasi`;
    document.getElementById('aktivasiSubtitle').textContent = nama;
    document.getElementById('kelasInfoText').innerHTML =
        `<i class="fas fa-chalkboard"></i> Kelas: <strong>${kelas}</strong> &nbsp;|&nbsp; <i class="fas fa-money-bill"></i> Iuran rutin: <strong>Rp ${harga.toLocaleString('id-ID')}/bulan</strong>`;
    document.getElementById('jumlahPendaftaran').value = harga > 0 ? harga : '';
    
    // Reset form
    document.querySelectorAll('input[name="bayar_pendaftaran"]').forEach(r => r.checked = false);
    document.querySelectorAll('input[name="buat_akun"]').forEach(r => r.checked = false);
    document.querySelector('input[name="password_type"][value="default"]').checked = true;
    document.getElementById('formBayar').style.display = 'none';
    document.getElementById('formAkun').style.display = 'none';
    document.getElementById('customPasswordBox').style.display = 'none';
    
    // Set email default dari data siswa jika ada
    const siswaData = @json($siswas->keyBy('id'));
    const siswa = siswaData[id];
    if (siswa && siswa.email) {
        document.getElementById('emailAkun').value = siswa.email;
    } else {
        document.getElementById('emailAkun').value = '';
    }
    
    // Update preview tanggal lahir
    if (siswa && siswa.tanggal_lahir) {
        const tglLahir = new Date(siswa.tanggal_lahir);
        const preview = String(tglLahir.getDate()).padStart(2, '0') + 
                       String(tglLahir.getMonth() + 1).padStart(2, '0') + 
                       tglLahir.getFullYear();
        document.getElementById('previewTglLahir').textContent = preview;
    }
    
    document.getElementById('aktivasiModal').style.display = 'flex';
}

function closeAktivasi() {
    document.getElementById('aktivasiModal').style.display = 'none';
}

function toggleBayar(show) {
    const box = document.getElementById('formBayar');
    box.style.display = show ? 'flex' : 'none';
    document.getElementById('labelYa').style.border = show ? '2px solid #4caf50' : '2px solid #ddd';
    document.getElementById('labelTidak').style.border = !show ? '2px solid #ff9800' : '2px solid #ddd';
}

function toggleAkun(show) {
    const box = document.getElementById('formAkun');
    box.style.display = show ? 'flex' : 'none';
    document.getElementById('labelBuatAkun').style.border = show ? '2px solid #2196f3' : '2px solid #ddd';
    document.getElementById('labelTidakAkun').style.border = !show ? '2px solid #ff9800' : '2px solid #ddd';
}

function toggleCustomPassword() {
    const isCustom = document.querySelector('input[name="password_type"][value="custom"]').checked;
    document.getElementById('customPasswordBox').style.display = isCustom ? 'block' : 'none';
}

// Event listener untuk password type
document.querySelectorAll('input[name="password_type"]').forEach(radio => {
    radio.addEventListener('change', toggleCustomPassword);
});

document.getElementById('aktivasiModal').addEventListener('click', e => {
    if (e.target === document.getElementById('aktivasiModal')) closeAktivasi();
});
</script>

<div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection
