@extends('layouts.admin')
@section('content')

@php
$namaBulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
              '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
$hariIni = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
            'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'][date('l')];
@endphp

<!-- Welcome Header -->
<div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:white;padding:24px 30px;border-radius:14px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;">
    <div>
        <h1 style="margin:0 0 6px 0;font-size:24px;font-weight:700;">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p style="margin:0;opacity:.9;font-size:14px;">Youth Swimming Club — {{ $hariIni }}, {{ date('d') }} {{ $namaBulan[date('m')] }} {{ date('Y') }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;font-size:15px;font-weight:600;opacity:.9;">
        <i class="fas fa-clock"></i>
        <span id="current-time"></span>
    </div>
</div>

<!-- Stats Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:24px;">
    <a href="{{ route('admin.siswa-aktif') }}" style="text-decoration:none;">
        <div style="background:white;padding:22px;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);display:flex;align-items:center;gap:18px;transition:all .3s;border-left:4px solid #2196f3;"
            onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,.15)'"
            onmouseout="this.style.transform='';this.style.boxShadow='0 3px 15px rgba(0,0,0,.08)'">
            <div style="width:54px;height:54px;background:linear-gradient(135deg,#2196f3,#1565c0);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#333;">{{ $totalSiswaAktif }}</div>
                <div style="font-size:13px;color:#666;margin-bottom:3px;">Total Siswa Aktif</div>
                <span style="font-size:11px;font-weight:600;background:#e8f5e9;color:#4caf50;padding:2px 8px;border-radius:10px;">+{{ $siswaBaru }} bulan ini</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.rekap-keuangan') }}" style="text-decoration:none;">
        <div style="background:white;padding:22px;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);display:flex;align-items:center;gap:18px;transition:all .3s;border-left:4px solid #4caf50;"
            onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,.15)'"
            onmouseout="this.style.transform='';this.style.boxShadow='0 3px 15px rgba(0,0,0,.08)'">
            <div style="width:54px;height:54px;background:linear-gradient(135deg,#4caf50,#2e7d32);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <div style="font-size:20px;font-weight:700;color:#333;">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
                <div style="font-size:13px;color:#666;margin-bottom:3px;">Pendapatan Bulan Ini</div>
                <span style="font-size:11px;font-weight:600;background:{{ $perubahanPendapatan>=0?'#e8f5e9':'#ffebee' }};color:{{ $perubahanPendapatan>=0?'#4caf50':'#f44336' }};padding:2px 8px;border-radius:10px;">
                    {{ $perubahanPendapatan>=0?'+':'' }}{{ number_format($perubahanPendapatan,1) }}% dari bln lalu
                </span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.belum-bayar') }}" style="text-decoration:none;">
        <div style="background:white;padding:22px;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);display:flex;align-items:center;gap:18px;transition:all .3s;border-left:4px solid #ff9800;"
            onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,.15)'"
            onmouseout="this.style.transform='';this.style.boxShadow='0 3px 15px rgba(0,0,0,.08)'">
            <div style="width:54px;height:54px;background:linear-gradient(135deg,#ff9800,#e65100);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#333;">{{ $siswaBelumBayar }}</div>
                <div style="font-size:13px;color:#666;margin-bottom:3px;">Belum Bayar Iuran</div>
                <span style="font-size:11px;font-weight:600;background:#ffebee;color:#f44336;padding:2px 8px;border-radius:10px;">Perlu tindak lanjut</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.calon-siswa') }}" style="text-decoration:none;">
        <div style="background:white;padding:22px;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);display:flex;align-items:center;gap:18px;transition:all .3s;border-left:4px solid #9c27b0;"
            onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,.15)'"
            onmouseout="this.style.transform='';this.style.boxShadow='0 3px 15px rgba(0,0,0,.08)'">
            <div style="width:54px;height:54px;background:linear-gradient(135deg,#9c27b0,#6a1b9a);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:white;flex-shrink:0;">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;color:#333;">{{ $totalCalonSiswa }}</div>
                <div style="font-size:13px;color:#666;margin-bottom:3px;">Pendaftar Baru</div>
                <span style="font-size:11px;font-weight:600;background:#f3e5f5;color:#9c27b0;padding:2px 8px;border-radius:10px;">Menunggu verifikasi</span>
            </div>
        </div>
    </a>
</div>

<!-- Aksi Cepat -->
<div style="margin-bottom:24px;">
    <h3 style="margin:0 0 14px 0;font-size:16px;font-weight:600;color:#333;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-bolt" style="color:#d32f2f;"></i> Aksi Cepat
    </h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
        @foreach([
            ['route'=>'admin.daftar-baru','icon'=>'fa-user-plus','color'=>'#4caf50,#2e7d32','title'=>'Daftar Siswa Baru','sub'=>'Tambah siswa ke sistem'],
            ['route'=>'admin.iuran-rutin','icon'=>'fa-money-check-alt','color'=>'#2196f3,#1565c0','title'=>'Input Pembayaran','sub'=>'Catat iuran siswa'],
            ['route'=>'admin.belum-bayar','icon'=>'fa-bell','color'=>'#ff9800,#e65100','title'=>'Tagih Iuran','sub'=>$siswaBelumBayar.' siswa belum bayar'],
            ['route'=>'admin.rekap-keuangan','icon'=>'fa-chart-pie','color'=>'#9c27b0,#6a1b9a','title'=>'Rekap Keuangan','sub'=>'Lihat laporan keuangan'],
        ] as $a)
        <a href="{{ route($a['route']) }}" style="background:white;padding:16px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.07);display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;transition:all .3s;"
            onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.12)'"
            onmouseout="this.style.transform='';this.style.boxShadow='0 2px 10px rgba(0,0,0,.07)'">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,{{ $a['color'] }});border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:white;flex-shrink:0;">
                <i class="fas {{ $a['icon'] }}"></i>
            </div>
            <div>
                <div style="font-size:13px;font-weight:600;color:#333;">{{ $a['title'] }}</div>
                <div style="font-size:11px;color:#999;">{{ $a['sub'] }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Main Grid: Timeline + Info Cards -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:22px;margin-bottom:24px;">

    <!-- Lapor, Bos! -->
    <div style="background:white;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
            <h3 style="margin:0;font-size:16px;font-weight:600;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-bullhorn" style="color:#d32f2f;"></i> Lapor, Bos!
            </h3>
            <a href="{{ route('admin.rekap-keuangan') }}" style="font-size:12px;color:#d32f2f;text-decoration:none;">Lihat Semua</a>
        </div>
        <div style="padding:18px 22px;max-height:520px;overflow-y:auto;">

            {{-- Reminder Pembayaran --}}
            <div style="position:relative;padding-left:26px;margin-bottom:22px;">
                <div style="position:absolute;left:0;top:6px;width:12px;height:12px;border-radius:50%;background:#ff9800;box-shadow:0 0 0 3px #fff3e0;"></div>
                <div style="font-size:11px;color:#999;margin-bottom:6px;"><i class="fas fa-exclamation-triangle" style="color:#ff9800;"></i> {{ $hariIni }}, {{ date('d') }} {{ $namaBulan[date('m')] }} {{ date('Y') }}</div>
                <div style="background:#fff8e1;border-left:4px solid #ff9800;border-radius:8px;padding:14px;">
                    <div style="font-size:14px;font-weight:600;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                        <i class="fas fa-money-bill-wave"></i> Reminder Pembayaran
                    </div>
                    <p style="margin:0 0 10px 0;font-size:13px;line-height:1.6;">
                        Bulan {{ $namaBulan[date('m')] }}: <strong style="color:#4caf50;">{{ $totalSudahBayar }} siswa</strong> sudah bayar,
                        <strong style="color:#f44336;">{{ $siswaBelumBayar }} siswa</strong> belum bayar.
                        @if($belumBayarPerKelas) Kelas <strong>{{ $belumBayarPerKelas->kelas }}</strong> terbanyak belum bayar ({{ $belumBayarPerKelas->total }} siswa). @endif
                    </p>
                    <div style="display:flex;gap:8px;">
                        <a href="{{ route('admin.belum-bayar') }}" style="background:#d32f2f;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">Lihat Detail</a>
                        <a href="{{ route('admin.iuran-rutin') }}" style="background:#f5f5f5;color:#555;padding:5px 12px;border-radius:6px;font-size:11px;text-decoration:none;">Input Pembayaran</a>
                    </div>
                </div>
            </div>

            {{-- Pendaftaran Baru --}}
            @if($totalCalonSiswa > 0)
            <div style="position:relative;padding-left:26px;margin-bottom:22px;">
                <div style="position:absolute;left:0;top:6px;width:12px;height:12px;border-radius:50%;background:#2196f3;box-shadow:0 0 0 3px #e3f2fd;"></div>
                <div style="font-size:11px;color:#999;margin-bottom:6px;"><i class="fas fa-user-plus" style="color:#2196f3;"></i> {{ $hariIni }}, {{ date('d') }} {{ $namaBulan[date('m')] }} {{ date('Y') }}</div>
                <div style="background:#e3f2fd;border-left:4px solid #2196f3;border-radius:8px;padding:14px;">
                    <div style="font-size:14px;font-weight:600;margin-bottom:6px;"><i class="fas fa-clipboard-list"></i> Pendaftaran Baru</div>
                    <p style="margin:0 0 10px 0;font-size:13px;line-height:1.6;">
                        <strong>{{ $totalCalonSiswa }} calon siswa</strong> menunggu verifikasi dan aktivasi.
                    </p>
                    <div style="display:flex;gap:8px;">
                        <a href="{{ route('admin.calon-siswa') }}" style="background:#1565c0;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">Lihat Pendaftar</a>
                        <a href="{{ route('admin.daftar-baru') }}" style="background:#f5f5f5;color:#555;padding:5px 12px;border-radius:6px;font-size:11px;text-decoration:none;">Tambah Baru</a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Ulang Tahun --}}
            @if($ultahHariIni->count() > 0)
            <div style="position:relative;padding-left:26px;margin-bottom:22px;">
                <div style="position:absolute;left:0;top:6px;width:12px;height:12px;border-radius:50%;background:#e91e63;box-shadow:0 0 0 3px #fce4ec;"></div>
                <div style="font-size:11px;color:#999;margin-bottom:6px;"><i class="fas fa-birthday-cake" style="color:#e91e63;"></i> {{ $hariIni }}, {{ date('d') }} {{ $namaBulan[date('m')] }} {{ date('Y') }}</div>
                <div style="background:#fce4ec;border-left:4px solid #e91e63;border-radius:8px;padding:14px;">
                    <div style="font-size:14px;font-weight:600;margin-bottom:8px;"><i class="fas fa-gift"></i> Happy Birthday! 🎉</div>
                    @foreach($ultahHariIni as $s)
                    @php $umur = \Carbon\Carbon::parse($s->tanggal_lahir)->age; @endphp
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                        <div style="width:32px;height:32px;background:#e91e63;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($s->nama,0,2)) }}
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;">{{ $s->nama }}</div>
                            <div style="font-size:11px;color:#666;">{{ $umur }} tahun — Kelas {{ $s->kelas }}</div>
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ route('admin.siswa-ulang-tahun') }}" style="background:#880e4f;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;display:inline-block;margin-top:4px;">Lihat Semua</a>
                </div>
            </div>
            @endif

            {{-- Pembayaran Hari Ini --}}
            <div style="position:relative;padding-left:26px;margin-bottom:22px;">
                <div style="position:absolute;left:0;top:6px;width:12px;height:12px;border-radius:50%;background:#4caf50;box-shadow:0 0 0 3px #e8f5e9;"></div>
                <div style="font-size:11px;color:#999;margin-bottom:6px;"><i class="fas fa-chart-line" style="color:#4caf50;"></i> Hari ini</div>
                <div style="background:#e8f5e9;border-left:4px solid #4caf50;border-radius:8px;padding:14px;">
                    <div style="font-size:14px;font-weight:600;margin-bottom:8px;"><i class="fas fa-money-check-alt"></i> Pembayaran Hari Ini</div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;">
                        <span>Iuran Rutin</span>
                        <strong>Rp {{ number_format($iuranRutinHariIni,0,',','.') }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
                        <span>Iuran Insidentil</span>
                        <strong>Rp {{ number_format($iuranInsidentilHariIni,0,',','.') }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:700;border-top:1px solid #c8e6c9;padding-top:8px;">
                        <span>Total</span>
                        <span style="color:#2e7d32;">Rp {{ number_format($totalBayarHariIni,0,',','.') }}</span>
                    </div>
                    <a href="{{ route('admin.rekap-transaksi') }}" style="background:#2e7d32;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;display:inline-block;margin-top:10px;">Lihat Rekap</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Panel Kanan -->
    <div style="display:flex;flex-direction:column;gap:18px;">

        <!-- Pembayaran Hari Ini -->
        <div style="background:white;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);overflow:hidden;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h4 style="margin:0;font-size:14px;font-weight:600;display:flex;align-items:center;gap:7px;">
                    <i class="fas fa-calendar-day" style="color:#d32f2f;"></i> Pembayaran Hari Ini
                </h4>
            </div>
            <div style="padding:14px 18px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
                    <span style="color:#666;">Iuran Rutin</span>
                    <strong>Rp {{ number_format($iuranRutinHariIni,0,',','.') }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:10px;">
                    <span style="color:#666;">Iuran Insidentil</span>
                    <strong>Rp {{ number_format($iuranInsidentilHariIni,0,',','.') }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:700;border-top:1px solid #f0f0f0;padding-top:10px;">
                    <span>Total</span>
                    <span style="color:#2e7d32;">Rp {{ number_format($totalBayarHariIni,0,',','.') }}</span>
                </div>
            </div>
        </div>

        <!-- Kelas Hari Ini -->
        <div style="background:white;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);overflow:hidden;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h4 style="margin:0;font-size:14px;font-weight:600;display:flex;align-items:center;gap:7px;">
                    <i class="fas fa-swimming-pool" style="color:#d32f2f;"></i> Kelas Hari Ini ({{ $hariIni }})
                </h4>
            </div>
            <div style="padding:12px 18px;">
                @forelse($kelasHariIni as $item)
                <a href="{{ route('admin.kelas') }}" style="text-decoration:none;color:inherit;">
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f5f5f5;"
                        onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                        <div style="background:#d32f2f;color:white;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:700;min-width:44px;text-align:center;flex-shrink:0;">
                            {{ $item['jadwal']['jam_mulai'] ?? '-' }}
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#333;">{{ $item['kelas']->nama_kelas }}</div>
                            <div style="font-size:11px;color:#999;">
                                {{ $item['kelas']->coach?->nama ?? 'Belum ada coach' }} &bull; {{ $item['jumlah_siswa'] }} siswa
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div style="padding:20px;text-align:center;color:#999;font-size:13px;">
                    <i class="fas fa-calendar-times" style="font-size:24px;opacity:.3;display:block;margin-bottom:8px;"></i>
                    Tidak ada kelas hari ini
                </div>
                @endforelse
            </div>
        </div>

        <!-- Reminder -->
        <div style="background:white;border-radius:12px;box-shadow:0 3px 15px rgba(0,0,0,.08);overflow:hidden;border-left:4px solid #ff9800;">
            <div style="padding:14px 18px;border-bottom:1px solid #f0f0f0;background:#fafafa;">
                <h4 style="margin:0;font-size:14px;font-weight:600;display:flex;align-items:center;gap:7px;">
                    <i class="fas fa-bell" style="color:#ff9800;"></i> Reminder
                </h4>
            </div>
            <div style="padding:12px 18px;display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('admin.belum-bayar') }}" style="text-decoration:none;">
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:#fff3e0;border-radius:7px;font-size:12px;color:#e65100;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><strong>{{ $siswaBelumBayar }} siswa</strong> belum bayar iuran bulan ini</span>
                    </div>
                </a>
                @if($ultahHariIni->count() > 0)
                <a href="{{ route('admin.siswa-ulang-tahun') }}" style="text-decoration:none;">
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:#fce4ec;border-radius:7px;font-size:12px;color:#880e4f;">
                        <i class="fas fa-birthday-cake"></i>
                        <span><strong>{{ $ultahHariIni->count() }} siswa</strong> berulang tahun hari ini</span>
                    </div>
                </a>
                @endif
                @if($totalCalonSiswa > 0)
                <a href="{{ route('admin.calon-siswa') }}" style="text-decoration:none;">
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:#f3e5f5;border-radius:7px;font-size:12px;color:#6a1b9a;">
                        <i class="fas fa-user-plus"></i>
                        <span><strong>{{ $totalCalonSiswa }} pendaftar</strong> menunggu verifikasi</span>
                    </div>
                </a>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
function updateTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
}
updateTime();
setInterval(updateTime, 1000);
</script>
@endsection
