<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa Baru - Youth Swimming Club</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        
        .register-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .register-header h2 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .register-body {
            padding: 2rem;
        }
        
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            transition: border-color 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .kelas-option {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            position: relative;
            user-select: none;
        }
        
        .kelas-option:hover {
            border-color: #007bff;
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.15);
        }
        
        .kelas-option.selected {
            border-color: #007bff;
            background: #e3f2fd;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .kelas-option.selected::after {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 8px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .kelas-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        
        .kelas-price {
            color: #28a745;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .btn-register {
            background: #007bff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            width: 100%;
            transition: background-color 0.2s ease;
        }
        
        .btn-register:hover {
            background: #0056b3;
            color: white;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .info-box {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .password-help {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        @media (max-width: 768px) {
            .register-body {
                padding: 1.5rem;
            }
            
            .kelas-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="fas fa-swimming-pool me-2"></i>Youth Swimming Club</h2>
                <p class="mb-0">Pendaftaran Siswa Baru</p>
            </div>
            
            <div class="register-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('daftar.store') }}">
                    @csrf
                    
                    <!-- Data Pribadi -->
                    <div class="section-title">
                        <i class="fas fa-user me-2"></i>Data Pribadi
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                        name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pilih Kelas -->
                    <div class="section-title">
                        <i class="fas fa-swimming-pool me-2"></i>Pilih Kelas
                    </div>
                    
                    @if($kelasList->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Belum ada kelas yang tersedia. Silakan hubungi admin.
                        </div>
                    @else
                        <div class="kelas-grid">
                            @foreach($kelasList as $kelas)
                                <div class="kelas-option" onclick="selectKelas({{ $kelas->id }})">
                                    <input type="radio" name="kelas_id" value="{{ $kelas->id }}" 
                                           id="kelas_{{ $kelas->id }}" style="display: none;" 
                                           {{ old('kelas_id') == $kelas->id ? 'checked' : '' }}>
                                    <div class="kelas-name">{{ $kelas->nama_kelas }}</div>
                                    <div class="kelas-price">Rp {{ number_format($kelas->harga, 0, ',', '.') }}/bulan</div>
                                </div>
                            @endforeach
                        </div>
                        @error('kelas_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    @endif

                    <!-- Data Orang Tua -->
                    <div class="section-title">
                        <i class="fas fa-users me-2"></i>Data Orang Tua/Wali
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Orang Tua/Wali</label>
                                <input type="text" class="form-control @error('nama_ortu') is-invalid @enderror" 
                                       name="nama_ortu" value="{{ old('nama_ortu') }}" required>
                                @error('nama_ortu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('telepon') is-invalid @enderror" 
                                       name="telepon" value="{{ old('telepon') }}" required>
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Akun Login -->
                    <div class="section-title">
                        <i class="fas fa-key me-2"></i>Akun Login
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                <div class="password-help">Minimal 6 karakter</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Email dan password ini akan digunakan untuk login ke sistem. 
                        Akun akan aktif setelah diverifikasi oleh admin.
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>

                <div class="login-link">
                    <p class="text-muted">Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectKelas(kelasId) {
            // Remove selected class from all options
            document.querySelectorAll('.kelas-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            const radioButton = document.getElementById('kelas_' + kelasId);
            if (radioButton) {
                radioButton.checked = true;
                
                // Clear any previous error messages
                const errorDiv = document.querySelector('.text-danger');
                if (errorDiv && errorDiv.textContent.includes('kelas')) {
                    errorDiv.style.display = 'none';
                }
            }
            
            console.log('Kelas dipilih:', kelasId); // Debug log
        }
        
        // Set initial selection if there's old input
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="kelas_id"]:checked');
            if (checkedRadio) {
                const kelasId = checkedRadio.value;
                const kelasOption = document.querySelector(`[onclick="selectKelas(${kelasId})"]`);
                if (kelasOption) {
                    kelasOption.classList.add('selected');
                }
            }
            
            // Add click event listeners as backup
            document.querySelectorAll('.kelas-option').forEach(option => {
                option.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    if (radioInput) {
                        const kelasId = radioInput.value;
                        selectKelas(kelasId);
                    }
                });
            });
        });
        
        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedKelas = document.querySelector('input[name="kelas_id"]:checked');
            if (!selectedKelas) {
                e.preventDefault();
                alert('Silakan pilih kelas terlebih dahulu!');
                return false;
            }
        });
    </script>
</body>
</html>