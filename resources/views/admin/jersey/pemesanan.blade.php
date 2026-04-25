@extends('layouts.admin')
@section('content')

<h1 style="color:#d32f2f;font-size:26px;font-weight:700;margin-bottom:20px;font-style:italic;">PEMESANAN JERSEY</h1>

@if(session('success'))
<div style="margin-bottom:16px;padding:12px 16px;background:#d4edda;color:#155724;border-radius:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Alur Info -->
<div style="background:#e3f2fd;border-radius:8px;padding:12px 18px;margin-bottom:20px;border-left:4px solid #2196f3;font-size:13px;color:#1565c0;">
    <strong><i class="fas fa-route"></i> Alur Jersey:</strong>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-top:6px;">
        <span style="background:#607d8b;color:white;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;">1. Dipesan</span>
        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        <span style="background:#ff9800;color:white;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;">2. Diproses</span>
        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        <span style="background:#2196f3;color:white;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;">3. Selesai (Jersey Jadi)</span>
        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        <span style="background:#4caf50;color:white;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;">4. Bayar → Diambil</span>
        <i class="fas fa-arrow-right" style="font-size:10px;"></i>
        <span style="background:#d32f2f;color:white;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;">✓ Masuk Pendapatan</span>
    </div>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:15px;margin-bottom:22px;">
    <div style="background:linear-gradient(135deg,#607d8b,#37474f);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['total'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Pesanan</div>
    </div>
    <div style="background:linear-gradient(135deg,#f44336,#c62828);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['belum_bayar'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Belum Bayar</div>
    </div>
    <div style="background:linear-gradient(135deg,#4caf50,#2e7d32);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:24px;font-weight:700;">{{ $stats['lunas'] }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Sudah Lunas</div>
    </div>
    <div style="background:linear-gradient(135deg,#9c27b0,#6a1b9a);color:white;padding:18px;border-radius:10px;text-align:center;">
        <div style="font-size:18px;font-weight:700;">Rp {{ number_format($stats['total_pendapatan'],0,',','.') }}</div>
        <div style="font-size:12px;opacity:.85;margin-top:3px;">Total Pendapatan</div>
    </div>
</div>

<!-- Form Pemesanan -->
<div style="background:white;padding:22px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
    <h3 style="margin:0 0 18px 0;font-size:16px;font-weight:600;"><i class="fas fa-plus-circle" style="color:#d32f2f;"></i> Pesan Jersey Baru</h3>
    <form method="POST" action="{{ route('admin.pemesanan.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-bottom:15px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Siswa <span style="color:red">*</span></label>
                        <select name="siswa_id" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas ?? 'Tidak ada kelas' }})</option>
                            @endforeach
                        </select>
                    </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Size <span style="color:red">*</span></label>
                <select name="jersey_size_id" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="">-- Pilih Size --</option>
                    @foreach($sizes as $sz)
                    <option value="{{ $sz->id }}">{{ $sz->nama_size }} (Stok: {{ $sz->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Tanggal Pesan <span style="color:red">*</span></label>
                <input type="date" name="tanggal_pesan" value="{{ date('Y-m-d') }}" required style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 2fr;gap:15px;margin-bottom:15px;">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">No. Punggung</label>
                <input type="text" name="nomor_punggung" placeholder="10" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Nama Punggung</label>
                <input type="text" name="nama_punggung" placeholder="Nama di jersey" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Harga (Rp)</label>
                <input type="number" name="harga" placeholder="250000" min="0" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Keterangan</label>
                <input type="text" name="keterangan" placeholder="Catatan tambahan" style="width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="reset" style="padding:8px 18px;border:1px solid #ddd;background:white;border-radius:6px;cursor:pointer;font-size:13px;">Reset</button>
            <button type="submit" style="padding:8px 18px;background:#d32f2f;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;font-size:13px;">
                <i class="fas fa-save"></i> Pesan
            </button>
        </div>
    </form>
</div>

<!-- Filter -->
<div style="background:white;padding:14px 20px;border-radius:10px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.07);">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Status Pesanan</label>
            <select name="status" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="dipesan" {{ request('status')=='dipesan'?'selected':'' }}>Dipesan</option>
                <option value="diproses" {{ request('status')=='diproses'?'selected':'' }}>Diproses</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                <option value="diambil" {{ request('status')=='diambil'?'selected':'' }}>Diambil</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Status Bayar</label>
            <select name="status_bayar" style="padding:7px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="belum_bayar" {{ request('status_bayar')=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
                <option value="lunas" {{ request('status_bayar')=='lunas'?'selected':'' }}>Lunas</option>
            </select>
        </div>
        <a href="{{ route('admin.pemesanan') }}" style="padding:7px 14px;border:1px solid #ddd;border-radius:6px;font-size:13px;text-decoration:none;color:#666;">Reset</a>
    </form>
</div>

<!-- Tabel Pesanan -->
<div style="background:white;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.07);">
    <div style="padding:14px 20px;border-bottom:1px solid #f0f0f0;">
        <h3 style="margin:0;font-size:15px;font-weight:600;"><i class="fas fa-shopping-cart" style="color:#d32f2f;"></i> Daftar Pesanan ({{ $orders->count() }})</h3>
    </div>
    @if($orders->isEmpty())
    <div style="padding:50px;text-align:center;color:#999;">
        <i class="fas fa-tshirt" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;"></i>
        Belum ada pesanan jersey
    </div>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#fafafa;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Tgl Pesan</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;color:#666;border-bottom:1px solid #eee;">Siswa</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Size</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">No/Nama Punggung</th>
                    <th style="padding:10px 14px;text-align:right;font-size:11px;color:#666;border-bottom:1px solid #eee;">Harga</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Status Pesanan</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Status Bayar</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;color:#666;border-bottom:1px solid #eee;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                @php
                    $statusColors = [
                        'dipesan'  => ['#f5f5f5','#607d8b'],
                        'diproses' => ['#fff3e0','#e65100'],
                        'selesai'  => ['#e3f2fd','#1565c0'],
                        'diambil'  => ['#e8f5e9','#2e7d32'],
                    ];
                    $sc = $statusColors[$o->status] ?? ['#f5f5f5','#666'];
                @endphp
                <tr onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;font-size:12px;">{{ $o->tanggal_pesan->format('d M Y') }}</td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;">
                        <div style="font-weight:600;">{{ $o->siswa->nama ?? 'Data siswa tidak ditemukan' }}</div>
                        <div style="font-size:11px;color:#999;">{{ $o->siswa->kelas ?? 'Tidak ada kelas' }}</div>
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <span style="background:#d32f2f;color:white;padding:3px 10px;border-radius:10px;font-weight:700;font-size:12px;">{{ $o->jerseySize->nama_size ?? 'N/A' }}</span>
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;font-size:13px;">
                        @if($o->nomor_punggung)<strong>#{{ $o->nomor_punggung }}</strong>@endif
                        @if($o->nama_punggung)<div style="font-size:11px;color:#666;">{{ $o->nama_punggung }}</div>@endif
                        @if(!$o->nomor_punggung && !$o->nama_punggung)-@endif
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:right;font-weight:600;color:#333;">
                        {{ $o->harga ? 'Rp '.number_format($o->harga,0,',','.') : '-' }}
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <form method="POST" action="{{ route('admin.pemesanan.update-status', $o) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                style="padding:4px 8px;border:1px solid #ddd;border-radius:6px;font-size:11px;font-weight:600;background:{{ $sc[0] }};color:{{ $sc[1] }};">
                                <option value="dipesan" {{ $o->status=='dipesan'?'selected':'' }}>Dipesan</option>
                                <option value="diproses" {{ $o->status=='diproses'?'selected':'' }}>Diproses</option>
                                <option value="selesai" {{ $o->status=='selesai'?'selected':'' }}>Selesai</option>
                                <option value="diambil" {{ $o->status=='diambil'?'selected':'' }}>Diambil</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        @if($o->status_bayar === 'lunas')
                            <div>
                                <span style="background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-check-circle"></i> Lunas</span>
                                <div style="font-size:10px;color:#999;margin-top:2px;">{{ $o->tanggal_bayar?->format('d M Y') }}</div>
                                <div style="font-size:10px;color:#999;">{{ $o->metode_pembayaran }}</div>
                            </div>
                        @else
                            @if($o->status === 'selesai' || $o->status === 'diambil')
                            @if($o->harga && $o->harga > 0)
                            <button onclick="openBayarModal({{ $o->id }}, '{{ addslashes($o->siswa->nama ?? 'Unknown') }}', {{ $o->harga ?? 0 }})"
                                style="background:#4caf50;color:white;border:none;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;">
                                <i class="fas fa-money-bill-wave"></i> Bayar
                            </button>
                            @else
                            <span style="background:#ffebee;color:#c62828;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-exclamation-triangle"></i> Set Harga Dulu</span>
                            @endif
                            @else
                            <span style="background:#ffebee;color:#c62828;padding:3px 8px;border-radius:8px;font-size:10px;font-weight:700;"><i class="fas fa-clock"></i> Belum Bayar</span>
                            @endif
                        @endif
                    </td>
                    <td style="padding:10px 14px;border-bottom:1px solid #f5f5f5;text-align:center;">
                        <form method="POST" action="{{ route('admin.pemesanan.destroy', $o) }}" onsubmit="return confirm('Hapus pesanan {{ $o->siswa->nama ?? 'siswa ini' }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:#ffebee;color:#f44336;border:none;padding:5px 9px;border-radius:5px;font-size:11px;cursor:pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Modal Bayar -->
<div id="bayarModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;width:90%;max-width:420px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <div style="background:linear-gradient(135deg,#4caf50,#2e7d32);color:white;padding:18px 22px;">
            <h3 style="margin:0;font-size:16px;font-weight:700;"><i class="fas fa-money-bill-wave"></i> Konfirmasi Pembayaran Jersey</h3>
            <p id="bayarNama" style="margin:4px 0 0 0;font-size:13px;opacity:.85;"></p>
        </div>
        <form id="bayarForm" method="POST" style="padding:22px;display:grid;gap:14px;">
            @csrf @method('PATCH')
            <div style="background:#e8f5e9;border-radius:8px;padding:12px 14px;font-size:13px;color:#2e7d32;">
                <i class="fas fa-info-circle"></i> Setelah konfirmasi, pembayaran ini akan <strong>otomatis masuk ke Pendapatan → Penjualan Jersey</strong>.
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Jumlah Bayar</label>
                <input type="text" id="bayarJumlah" readonly style="width:100%;padding:9px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-weight:700;color:#d32f2f;background:#f9f9f9;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Tanggal Bayar <span style="color:red">*</span></label>
                <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required style="width:100%;padding:9px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:5px;">Metode Pembayaran <span style="color:red">*</span></label>
                <select name="metode_pembayaran" required style="width:100%;padding:9px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;">
                    <option value="">Pilih...</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="QRIS">QRIS</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="button" onclick="closeBayarModal()" style="flex:1;padding:10px;border:1px solid #ddd;background:white;border-radius:8px;cursor:pointer;font-size:14px;">Batal</button>
                <button type="submit" style="flex:2;padding:10px;background:#4caf50;color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:14px;">
                    <i class="fas fa-check"></i> Konfirmasi Lunas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBayarModal(id, nama, harga) {
    document.getElementById('bayarForm').action = `/admin/pemesanan/${id}/bayar`;
    document.getElementById('bayarNama').textContent = 'Siswa: ' + nama;
    
    // Handle harga yang null atau 0
    if (harga && harga > 0) {
        document.getElementById('bayarJumlah').value = 'Rp ' + harga.toLocaleString('id-ID');
    } else {
        document.getElementById('bayarJumlah').value = 'Belum ada harga - silakan set harga terlebih dahulu';
        document.getElementById('bayarJumlah').style.color = '#f44336';
    }
    
    document.getElementById('bayarModal').style.display = 'flex';
}
function closeBayarModal() { document.getElementById('bayarModal').style.display = 'none'; }
document.getElementById('bayarModal').addEventListener('click', e => { if (e.target === document.getElementById('bayarModal')) closeBayarModal(); });
</script>
@endsection
