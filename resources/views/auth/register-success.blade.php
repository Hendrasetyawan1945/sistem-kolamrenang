<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Youth Swimming Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .success-header {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
            padding: 40px;
        }
        .success-body {
            padding: 40px;
        }
        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .btn-login {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <i class="fas fa-check-circle success-icon"></i>
                <h2>Pendaftaran Berhasil!</h2>
            </div>
            
            <div class="success-body">
                <h4 class="text-success mb-3">Selamat!</h4>
                <p class="mb-4">
                    Pendaftaran Anda sebagai calon siswa Youth Swimming Club telah berhasil diterima. 
                    <strong>Akun login Anda sudah dibuat!</strong>
                </p>
                
                @if(session('user_email'))
                <div class="alert alert-success">
                    <i class="fas fa-user-check me-2"></i>
                    <strong>Akun Login Anda:</strong><br>
                    <strong>Email:</strong> {{ session('user_email') }}<br>
                    <strong>Status:</strong> Menunggu aktivasi admin
                </div>
                @endif
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Langkah Selanjutnya:</strong><br>
                    1. Admin akan menghubungi Anda dalam 1-2 hari kerja<br>
                    2. Setelah disetujui, status Anda akan diaktifkan<br>
                    3. Anda bisa langsung login dengan email dan password yang sudah dibuat<br>
                    4. Akses portal siswa untuk melihat jadwal, iuran, dan rapor
                </div>

                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn-login me-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Coba Login Sekarang
                    </a>
                    <a href="{{ route('daftar') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user-plus me-2"></i>Daftar Lagi
                    </a>
                </div>

                <div class="mt-4">
                    <small class="text-muted">
                        Butuh bantuan? Hubungi admin di <strong>admin@youthswimming.com</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>