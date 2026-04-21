@extends('layouts.admin')

@section('content')

<!-- Header -->
<div style="background: linear-gradient(135deg, #d32f2f, #b71c1c); color: white; padding: 25px 30px; border-radius: 12px; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <a href="{{ route('admin.iuran-kejuaraan') }}" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 13px;">
                    <i class="fas fa-arrow-left"></i> Iuran Kejuaraan
                </a>
            </div>
            <h1 style="margin: 0; font-size: 22px; font-weight: 700;">{{ $kejuaraan->nama_kejuaraan }}</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.85; font-size: 14px;">
                <i class="fas fa-map-marker-alt"></i> {{ $kejuaraan->lokasi }} &nbsp;|&nbsp;
                <i class="fas fa-calendar"></i> {{ $kejuaraan->tanggal_kejuaraan->format('d M Y') }} &nbsp;|&nbsp;
                <i class="fas fa-money-bill"></i> Rp {{ number_format($kejuaraan->biaya_pendaftaran, 0, ',', '.') }}/siswa
            </p>
        </div>
        <span style="background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
            {{ $kejuaraan->status_label }}
        </span>
    </div>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); text-align: center; border-top: 4px solid #2196f3;">
        <div style="font-size: 28px; font-weight: 700; color: #2196f3;">{{ $stats['total'] }}</div>
        <div style="font-size: 13px; color: #666; margin-top: 4px;">Total Peserta</div>
    </div>
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); text-align: center; border-top: 4px solid #4caf50;">
        <div style="font-size: 28px; font-weight: 700; color: #4caf50;">{{ $stats['lunas'] }}</div>
        <div style="font-size: 13px; color: #666; margin-top: 4px;">Sudah Lunas</div>
    </div>
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); text-align: center; border-top: 4px solid #f44336;">
        <div style="font-size: 28px; font-weight: 700; color: #f44336;">{{ $stats['belum_bayar'] }}</div>
        <div style="font-size: 13px; color: #666; margin-top: 4px;">Belum Bayar</div>
    </div>
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); text-align: center; border-top: 4px solid #ff9800;">
        <div style="font-size: 22px; font-weight: 700; color: #ff9800;">Rp {{ number_format($stats['total_terkumpul'], 0, ',', '.') }}</div>
        <div style="font-size: 13px; color: #666; margin-top: 4px;">Dana Terkumpul</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 25px; align-items: start;">

    <!-- Daftar Peserta -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); overflow: hidden;">
        <div style="padding: 18px 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #333;">
                <i class="fas fa-list"></i> Daftar Peserta
            </h3>
            <!-- Filter Tabs -->
            <div style="display: flex; gap: 5px;">
                <a href="{{ route('admin.iuran-kejuaraan.peserta', $kejuaraan) }}"
                   style="padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none;
                          {{ $filter === 'semua' ? 'background: #d32f2f; color: white;' : 'background: #f5f5f5; color: #666;' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.iuran-kejuaraan.peserta', [$kejuaraan, 'filter' => 'lunas']) }}"
                   style="padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none;
                          {{ $filter === 'lunas' ? 'background: #4caf50; color: white;' : 'background: #f5f5f5; color: #666;' }}">
                    Lunas ({{ $stats['lunas'] }})
                </a>
                <a href="{{ route('admin.iuran-kejuaraan.peserta', [$kejuaraan, 'filter' => 'belum_bayar']) }}"
                   style="padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none;
                          {{ $filter === 'belum_bayar' ? 'background: #f44336; color: white;' : 'background: #f5f5f5; color: #666;' }}">
                    Belum Bayar ({{ $stats['belum_bayar'] }})
                </a>
            </div>
        </div>

        @if($peserta->isEmpty())
            <div style="padding: 50px; text-align: center; color: #999;">
                <i class="fas fa-users" style="font-size: 40px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
                @if($filter === 'lunas') Belum ada peserta yang lunas
                @elseif($filter === 'belum_bayar') Semua peserta sudah lunas!
                @else Belum ada peserta terdaftar. Tambahkan siswa dari panel kanan.
                @endif
            </div>
        @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fafafa;">
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">No</th>
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">Nama Siswa</th>
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">Kelas</th>
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">Jumlah</th>
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">Status</th>
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">Tgl Bayar</th>
                        <th style="padding: 11px 14px; text-align: left; font-size: 12px; color: #666; border-bottom: 1px solid #eee;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peserta as $i => $p)
                    <tr style="transition: background 0.2s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #999;">{{ $i + 1 }}</td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0;">
                            <div style="font-weight: 600; font-size: 14px; color: #333;">{{ $p->siswa->nama }}</div>
                            <div style="font-size: 11px; color: #999;">{{ $p->siswa->nama_ortu }}</div>
                        </td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 13px;">{{ $p->siswa->kelas }}</td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 13px; font-weight: 600;">
                            Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                        </td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0;">
                            @if($p->status === 'lunas')
                                <span style="background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Lunas
                                </span>
                            @else
                                <span style="background: #ffebee; color: #c62828; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-clock"></i> Belum Bayar
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 12px; color: #666;">
                            {{ $p->tanggal_bayar ? $p->tanggal_bayar->format('d M Y') : '-' }}
                            @if($p->metode_pembayaran)
                                <br><span style="color: #999;">{{ $p->metode_pembayaran }}</span>
                            @endif
                        </td>
                        <td style="padding: 12px 14px; border-bottom: 1px solid #f0f0f0;">
                            <div style="display: flex; gap: 5px;">
                                @if($p->status === 'belum_bayar')
                                    <button onclick="openBayarModal({{ $p->id }}, '{{ addslashes($p->siswa->nama) }}', {{ $p->jumlah }})"
                                        style="background: #4caf50; color: white; border: none; padding: 5px 10px; border-radius: 5px; font-size: 11px; cursor: pointer;">
                                        <i class="fas fa-money-bill-wave"></i> Bayar
                                    </button>
                                @else
                                    <span style="font-size: 11px; color: #4caf50;"><i class="fas fa-check"></i> Lunas</span>
                                @endif
                                <form method="POST" action="{{ route('admin.kejuaraan-pembayaran.destroy', $p) }}" onsubmit="return confirm('Hapus {{ $p->siswa->nama }} dari peserta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: #ffebee; color: #f44336; border: none; padding: 5px 8px; border-radius: 5px; font-size: 11px; cursor: pointer;">
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

    <!-- Panel Tambah Peserta -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); overflow: hidden; position: sticky; top: 20px;">
        <div style="padding: 18px 20px; border-bottom: 1px solid #f0f0f0; background: #fafafa;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 600; color: #333;">
                <i class="fas fa-user-plus"></i> Tambah Peserta
            </h3>
            <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">{{ $siswaBelumDaftar->count() }} siswa belum terdaftar</p>
        </div>

        @if($siswaBelumDaftar->isEmpty())
            <div style="padding: 30px; text-align: center; color: #999; font-size: 13px;">
                <i class="fas fa-check-circle" style="font-size: 30px; color: #4caf50; margin-bottom: 10px; display: block;"></i>
                Semua siswa aktif sudah terdaftar
            </div>
        @else
        <form method="POST" action="{{ route('admin.iuran-kejuaraan.peserta.store', $kejuaraan) }}">
            @csrf
            <div style="padding: 15px 20px;">
                <!-- Search -->
                <input type="text" id="searchSiswa" placeholder="Cari nama siswa..." oninput="filterSiswa(this.value)"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; box-sizing: border-box; margin-bottom: 10px;">

                <!-- Pilih Semua -->
                <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: #f5f5f5; border-radius: 6px; cursor: pointer; margin-bottom: 8px; font-size: 13px; font-weight: 600;">
                    <input type="checkbox" id="selectAll" onchange="toggleAll(this)">
                    Pilih Semua
                </label>

                <!-- List Siswa -->
                <div id="siswaList" style="max-height: 350px; overflow-y: auto; border: 1px solid #eee; border-radius: 6px;">
                    @foreach($siswaBelumDaftar as $siswa)
                    <label class="siswa-item" data-nama="{{ strtolower($siswa->nama) }}"
                        style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f5f5f5; transition: background 0.2s;"
                        onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='white'">
                        <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" style="flex-shrink: 0;">
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: #333;">{{ $siswa->nama }}</div>
                            <div style="font-size: 11px; color: #999;">{{ $siswa->kelas }} &bull; {{ $siswa->telepon }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>

                @if($errors->has('siswa_ids'))
                    <p style="color: #f44336; font-size: 12px; margin: 8px 0 0 0;">{{ $errors->first('siswa_ids') }}</p>
                @endif
            </div>

            <div style="padding: 15px 20px; border-top: 1px solid #f0f0f0; background: #fafafa;">
                <button type="submit" style="width: 100%; background: #d32f2f; color: white; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-plus"></i> Tambahkan ke Peserta
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

<!-- Modal Bayar -->
<div id="bayarModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 30px; width: 90%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #333;"><i class="fas fa-money-bill-wave" style="color: #4caf50;"></i> Konfirmasi Pembayaran</h3>
        <p id="bayarNama" style="margin: 0 0 20px 0; color: #666; font-size: 14px;"></p>

        <form id="bayarForm" method="POST">
            @csrf @method('PATCH')
            <div style="display: grid; gap: 14px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Jumlah Bayar</label>
                    <input type="text" id="bayarJumlah" readonly
                        style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f9f9f9; box-sizing: border-box; font-weight: 600; color: #d32f2f;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Tanggal Bayar <span style="color: red;">*</span></label>
                    <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required
                        style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Metode Pembayaran <span style="color: red;">*</span></label>
                    <select name="metode_pembayaran" required
                        style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        <option value="">Pilih metode...</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="QRIS">QRIS</option>
                        <option value="GoPay">GoPay</option>
                        <option value="OVO">OVO</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Keterangan</label>
                    <input type="text" name="keterangan" placeholder="Opsional..."
                        style="width: 100%; padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                </div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="closeBayarModal()"
                    style="flex: 1; padding: 10px; border: 1px solid #ddd; background: white; border-radius: 8px; font-size: 14px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="flex: 2; padding: 10px; background: #4caf50; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check"></i> Konfirmasi Lunas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBayarModal(id, nama, jumlah) {
    document.getElementById('bayarForm').action = `/admin/kejuaraan-pembayaran/${id}/bayar`;
    document.getElementById('bayarNama').textContent = 'Siswa: ' + nama;
    document.getElementById('bayarJumlah').value = 'Rp ' + jumlah.toLocaleString('id-ID');
    document.getElementById('bayarModal').style.display = 'flex';
}

function closeBayarModal() {
    document.getElementById('bayarModal').style.display = 'none';
}

document.getElementById('bayarModal').addEventListener('click', function(e) {
    if (e.target === this) closeBayarModal();
});

function filterSiswa(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.siswa-item').forEach(el => {
        el.style.display = el.dataset.nama.includes(q) ? 'flex' : 'none';
    });
}

function toggleAll(cb) {
    document.querySelectorAll('#siswaList input[type="checkbox"]').forEach(el => {
        if (el.closest('.siswa-item').style.display !== 'none') {
            el.checked = cb.checked;
        }
    });
}
</script>

@endsection
