<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth Swimming Club - Club Administration</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px 15px;
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h3 {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            padding: 0;
            list-style: none;
        }

        .sidebar-menu > li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu > li > a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-menu > li > a:hover,
        .sidebar-menu > li.active > a {
            background: rgba(255,255,255,0.1);
            padding-left: 25px;
        }

        .sidebar-menu > li > a i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        .submenu {
            background: rgba(0,0,0,0.2);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px 12px 50px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .submenu li a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .submenu li a i {
            width: 16px;
            margin-right: 10px;
            font-size: 14px;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .top-bar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 30px;
        }

        .club-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .club-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .club-title {
            font-size: 28px;
            font-weight: bold;
            color: #d32f2f;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .form-select, .form-input {
            flex: 1;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: #d32f2f;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #4caf50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #d32f2f;
            color: white;
        }

        .btn-secondary:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }

        .data-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .table-header {
            background: #f5f5f5;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
            color: #333;
        }

        .table-content {
            padding: 20px;
            text-align: center;
            color: #666;
        }

        .dropdown-toggle::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle.active::after {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-swimming-pool"></i> Club Administration</h3>
        </div>
        
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('keuangan')">
                    <i class="fas fa-money-bill-wave"></i>
                    Keuangan
                </a>
                <ul class="submenu" id="keuangan">
                    <li><a href="{{ route('admin.approval.pembayaran') }}"><i class="fas fa-check-circle"></i> Approval Pembayaran</a></li>
                    <li><a href="{{ route('admin.iuran-rutin') }}"><i class="fas fa-calendar-check"></i> Iuran Rutin</a></li>
                    <li><a href="{{ route('admin.paket-kuota') }}"><i class="fas fa-box"></i> Paket Kuota</a></li>
                    <li><a href="{{ route('admin.iuran-insidentil') }}"><i class="fas fa-exclamation-triangle"></i> Iuran Insidentil</a></li>
                    <li><a href="{{ route('admin.iuran-kejuaraan') }}"><i class="fas fa-trophy"></i> Iuran Kejuaraan</a></li>
                    <li><a href="{{ route('admin.angsuran') }}"><i class="fas fa-credit-card"></i> Angsuran</a></li>
                    <li><a href="{{ route('admin.pendapatan-lain') }}"><i class="fas fa-plus-circle"></i> Pendapatan Lain</a></li>
                    <li><a href="{{ route('admin.keuangan.pendapatan-jersey') }}"><i class="fas fa-tshirt"></i> Pendapatan Jersey</a></li>
                    <li><a href="{{ route('admin.keuangan.pengeluaran') }}"><i class="fas fa-minus-circle"></i> Pengeluaran</a></li>
                    <li style="border-top: 1px solid rgba(255,255,255,0.15); margin-top: 5px; padding-top: 5px;">
                        <a href="{{ route('admin.rekap-keuangan') }}" style="color: rgba(255,255,255,1); font-weight: 600;">
                            <i class="fas fa-chart-pie"></i> Rekap Keuangan
                        </a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('siswa')">
                    <i class="fas fa-users"></i>
                    Siswa
                </a>
                <ul class="submenu" id="siswa">
                    <li><a href="{{ route('admin.calon-siswa') }}"><i class="fas fa-user-plus"></i> Calon Siswa
                        @php $calonCount = \App\Models\Siswa::where('status','calon')->count(); @endphp
                        @if($calonCount > 0)
                        <span style="background:#ff9800;color:white;padding:1px 7px;border-radius:10px;font-size:10px;font-weight:700;margin-left:5px;">{{ $calonCount }}</span>
                        @endif
                    </a></li>
                    <li><a href="{{ route('admin.siswa-aktif') }}"><i class="fas fa-user-check"></i> Siswa Aktif</a></li>
                    <li><a href="{{ route('admin.siswa-cuti') }}"><i class="fas fa-user-clock"></i> Siswa Cuti</a></li>
                    <li><a href="{{ route('admin.siswa-nonaktif') }}"><i class="fas fa-user-times"></i> Siswa Nonaktif</a></li>
                    <li><a href="{{ route('admin.kakak-beradik') }}"><i class="fas fa-user-friends"></i> Kakak Beradik</a></li>
                    <li><a href="{{ route('admin.siswa-ulang-tahun') }}"><i class="fas fa-birthday-cake"></i> Siswa Ulang Tahun</a></li>
                    <li style="border-top:1px solid rgba(255,255,255,.15);margin-top:5px;padding-top:5px;">
                        <a href="{{ route('admin.kelas') }}" style="font-weight:600;"><i class="fas fa-chalkboard"></i> Kelas</a>
                    </li>
                    <li><a href="{{ route('admin.coach') }}"><i class="fas fa-user-tie"></i> Guru</a></li>
                </ul>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('absensi')">
                    <i class="fas fa-clipboard-check"></i>
                    Absensi
                </a>
                <ul class="submenu" id="absensi">
                    <li><a href="{{ route('admin.absensi') }}"><i class="fas fa-check-square"></i> Input Absensi</a></li>
                    <li><a href="{{ route('admin.absensi.rekap') }}"><i class="fas fa-chart-bar"></i> Rekap Kehadiran</a></li>
                </ul>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('prestasi')">
                    <i class="fas fa-medal"></i>
                    Prestasi
                </a>
                <ul class="submenu" id="prestasi">
                    <li><a href="{{ route('admin.catatan-waktu') }}"><i class="fas fa-stopwatch"></i> Catatan Waktu</a></li>
                    <li><a href="{{ route('admin.personal-best') }}"><i class="fas fa-star"></i> Personal Best</a></li>
                    <li><a href="{{ route('admin.catatan-waktu-latihan') }}"><i class="fas fa-clock"></i> Catatan Waktu Latihan</a></li>
                    <li><a href="{{ route('admin.progress-report') }}"><i class="fas fa-chart-line"></i> Progress Report</a></li>
                    <li><a href="{{ route('admin.nomor-nonstandar') }}"><i class="fas fa-list-ol"></i> Nomor Nonstandar</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('rapor')">
                    <i class="fas fa-file-alt"></i>
                    Rapor
                </a>
                <ul class="submenu" id="rapor">
                    <li><a href="{{ route('admin.rapor') }}"><i class="fas fa-file-alt"></i> Rapor Siswa</a></li>
                    <li><a href="{{ route('admin.template-rapor') }}"><i class="fas fa-file-pdf"></i> Template Rapor</a></li>
                </ul>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('jersey')">
                    <i class="fas fa-tshirt"></i>
                    Jersey
                </a>
                <ul class="submenu" id="jersey">
                    <li><a href="{{ route('admin.jersey-map') }}"><i class="fas fa-map"></i> Jersey Map</a></li>
                    <li><a href="{{ route('admin.size-chart') }}"><i class="fas fa-ruler"></i> Size Chart</a></li>
                    <li><a href="{{ route('admin.pemesanan') }}"><i class="fas fa-shopping-cart"></i> Pemesanan</a></li>
                    <li><a href="{{ route('admin.master-ukuran') }}"><i class="fas fa-cogs"></i> Master Ukuran</a></li>
                </ul>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('laporan')">
                    <i class="fas fa-file-alt"></i>
                    Laporan
                </a>
                <ul class="submenu" id="laporan">
                    <li><a href="{{ route('admin.rekap-keuangan') }}"><i class="fas fa-chart-pie"></i> Rekap Keuangan</a></li>
                    <li><a href="{{ route('admin.rekap-transaksi') }}"><i class="fas fa-receipt"></i> Rekap Transaksi</a></li>
                    <li><a href="{{ route('admin.rekap-pembayaran-iuran') }}"><i class="fas fa-money-check"></i> Rekap Pembayaran Iuran</a></li>
                    <li><a href="{{ route('admin.rekap-jumlah-siswa') }}"><i class="fas fa-users"></i> Rekap Jumlah Siswa</a></li>
                </ul>
            </li>
            
            <li class="{{ request()->routeIs('admin.akun.*') ? 'active' : '' }}">
                <a href="{{ route('admin.akun.index') }}">
                    <i class="fas fa-user-cog"></i>
                    Kelola Akun
                </a>
            </li>
            
            <li>
                <a href="#" class="dropdown-toggle" onclick="toggleSubmenu('pengaturan')">
                    <i class="fas fa-cog"></i>
                    Pengaturan
                </a>
                <ul class="submenu" id="pengaturan">
                    <li><a href="{{ route('admin.kelas') }}"><i class="fas fa-chalkboard"></i> Kelas</a></li>
                    <li><a href="{{ route('admin.coach') }}"><i class="fas fa-user-tie"></i> Guru</a></li>
                    <li><a href="{{ route('admin.kolam') }}"><i class="fas fa-swimming-pool"></i> Kolam</a></li>
                    <li><a href="{{ route('admin.metode-pembayaran') }}"><i class="fas fa-credit-card"></i> Metode Pembayaran</a></li>
                    <li><a href="{{ route('admin.umum') }}"><i class="fas fa-globe"></i> Umum</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <button class="btn btn-primary d-md-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="{{ route('admin.profil') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;color:#555;padding:6px 12px;border-radius:8px;transition:background .2s;"
                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background=''">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#d32f2f,#b71c1c);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span style="font-size: 14px; font-weight:600;">{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px; border-radius: 6px;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            const toggle = event.target.closest('.dropdown-toggle');
            
            // Close all other submenus
            document.querySelectorAll('.submenu').forEach(menu => {
                if (menu.id !== id) {
                    menu.classList.remove('show');
                }
            });
            
            document.querySelectorAll('.dropdown-toggle').forEach(btn => {
                if (btn !== toggle) {
                    btn.classList.remove('active');
                }
            });
            
            // Toggle current submenu
            submenu.classList.toggle('show');
            toggle.classList.toggle('active');
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Auto-open active menu
        document.addEventListener('DOMContentLoaded', function() {
            const activeLink = document.querySelector('.sidebar-menu .active');
            if (activeLink) {
                const parentSubmenu = activeLink.closest('.submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('show');
                    const toggle = parentSubmenu.previousElementSibling;
                    if (toggle) {
                        toggle.classList.add('active');
                    }
                }
            }

            // ── FORMAT RUPIAH OTOMATIS ─────────────────────────────
            function formatRupiah(val) {
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            function unformat(val) {
                return val.replace(/[^0-9]/g, '');
            }

            function initRupiah(input) {
                if (input.dataset.rupiahInit) return;
                input.dataset.rupiahInit = '1';

                // Buat hidden input pembawa nilai asli ke server
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = input.name;
                input.name   = '__display_' + input.name;
                input.parentNode.insertBefore(hidden, input.nextSibling);

                // Set nilai awal
                const initVal = unformat(input.value || '');
                if (initVal) { hidden.value = initVal; input.value = formatRupiah(initVal); }

                input.setAttribute('inputmode', 'numeric');
                input.setAttribute('autocomplete', 'off');
                input.style.textAlign = 'right';

                input.addEventListener('input', function() {
                    const raw = unformat(this.value);
                    hidden.value = raw;
                    this.value   = raw ? formatRupiah(raw) : '';
                });
            }

            // Kata kunci nama field yang dianggap rupiah
            const keys = ['harga','jumlah','biaya','nominal','tarif','iuran','gaji',
                          'bayar','tagihan','cicilan','total','pendapatan','pengeluaran',
                          'angsuran','cicilan','denda'];

            document.querySelectorAll('input[type="number"], input[type="text"]').forEach(inp => {
                const n = (inp.name || inp.id || '').toLowerCase();
                if (keys.some(k => n.includes(k))) {
                    if (inp.type === 'number') inp.type = 'text';
                    initRupiah(inp);
                }
            });
        });
    </script>
</body>
</html>