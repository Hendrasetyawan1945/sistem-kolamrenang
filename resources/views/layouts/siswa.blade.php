<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Siswa') - Youth Swimming Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">Portal Siswa</h5>
                        <small class="text-white-50">{{ auth()->user()->name }}</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}" href="{{ route('siswa.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.iuran.*') ? 'active' : '' }}" href="{{ route('siswa.iuran.index') }}">
                            <i class="fas fa-money-bill-wave me-2"></i> Iuran
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.rapor.*') ? 'active' : '' }}" href="{{ route('siswa.rapor.index') }}">
                            <i class="fas fa-file-alt me-2"></i> Rapor
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.prestasi.*') ? 'active' : '' }}" href="{{ route('siswa.prestasi.index') }}">
                            <i class="fas fa-trophy me-2"></i> Prestasi
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.catatan-waktu.*') ? 'active' : '' }}" href="{{ route('siswa.catatan-waktu.index') }}">
                            <i class="fas fa-stopwatch me-2"></i> Catatan Waktu
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.kehadiran.*') ? 'active' : '' }}" href="{{ route('siswa.kehadiran.index') }}">
                            <i class="fas fa-calendar-check me-2"></i> Kehadiran
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.jersey.*') ? 'active' : '' }}" href="{{ route('siswa.jersey.index') }}">
                            <i class="fas fa-tshirt me-2"></i> Jersey
                        </a>
                        <a class="nav-link {{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}" href="{{ route('siswa.profile.index') }}">
                            <i class="fas fa-user-cog me-2"></i> Profil
                        </a>
                        
                        <hr class="text-white-50">
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
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