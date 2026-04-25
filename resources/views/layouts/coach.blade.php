<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Guru') - Youth Swimming Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(160deg, #1a3c5e 0%, #2e6da4 100%);
            position: sticky; top: 0;
        }
        .sidebar-brand { padding: 24px 20px 16px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-brand h6 { color: #fff; font-weight: 700; margin: 0; }
        .sidebar-brand small { color: rgba(255,255,255,.55); font-size: 11px; }
        .nav-section { padding: 12px 16px 4px; font-size: 10px; text-transform: uppercase;
            letter-spacing: 1px; color: rgba(255,255,255,.4); font-weight: 600; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75); padding: 9px 16px; border-radius: 8px;
            margin: 1px 8px; font-size: 13.5px; display: flex; align-items: center; gap: 10px;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar .nav-link.active { background: rgba(255,255,255,.18); color: #fff; font-weight: 600; }
        .sidebar .nav-link i { width: 18px; text-align: center; }
        .topbar { background: #fff; border-bottom: 1px solid #e9ecef; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .topbar .page-title { font-size: 16px; font-weight: 600; color: #1a3c5e; margin: 0; }
        .main-content { padding: 24px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 6px rgba(0,0,0,.07); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; border-radius: 12px 12px 0 0 !important;
            padding: 14px 20px; font-weight: 600; }
    </style>
</head>
<body>
<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-2 col-lg-2 d-none d-md-block">
            <div class="sidebar">
                <div class="sidebar-brand">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fas fa-swimming-pool text-info"></i>
                        <h6>Youth Swimming</h6>
                    </div>
                    <small>{{ auth()->user()->name }}</small><br>
                    <span class="badge bg-info bg-opacity-25 text-info" style="font-size:10px;">Guru / Coach</span>
                </div>

                <nav class="nav flex-column py-2">
                    <div class="nav-section">Utama</div>
                    <a class="nav-link {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}"
                       href="{{ route('coach.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>

                    <div class="nav-section">Kelas & Siswa</div>
                    <a class="nav-link {{ request()->routeIs('coach.siswa.*') ? 'active' : '' }}"
                       href="{{ route('coach.siswa.index') }}">
                        <i class="fas fa-users"></i> Data Siswa
                    </a>
                    <a class="nav-link {{ request()->routeIs('coach.absensi.*') ? 'active' : '' }}"
                       href="{{ route('coach.absensi.index') }}">
                        <i class="fas fa-clipboard-check"></i> Absensi
                    </a>

                    <div class="nav-section">Latihan</div>
                    <a class="nav-link {{ request()->routeIs('coach.catatan-waktu.*') ? 'active' : '' }}"
                       href="{{ route('coach.catatan-waktu.index') }}">
                        <i class="fas fa-stopwatch"></i> Catatan Waktu
                    </a>
                    <a class="nav-link {{ request()->routeIs('coach.rapor.*') ? 'active' : '' }}"
                       href="{{ route('coach.rapor.index') }}">
                        <i class="fas fa-file-alt"></i> Rapor Siswa
                    </a>

                    <div class="nav-section">Keuangan</div>
                    <a class="nav-link {{ request()->routeIs('coach.pembayaran.*') ? 'active' : '' }}"
                       href="{{ route('coach.pembayaran.index') }}">
                        <i class="fas fa-money-check-alt"></i> Input Pembayaran
                    </a>

                    <div class="mt-auto pt-3" style="border-top:1px solid rgba(255,255,255,.1); margin: 16px 8px 0;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger-emphasis">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-10 col-lg-10">
            <div class="topbar">
                <h6 class="page-title">@yield('page-title', 'Dashboard')</h6>
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}</small>
                </div>
            </div>

            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
