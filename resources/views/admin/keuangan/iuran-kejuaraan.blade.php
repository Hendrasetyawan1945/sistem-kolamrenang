@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-trophy"></i>
    </div>
    <h1 class="club-title">Iuran Kejuaraan</h1>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <i class="fas fa-exclamation-triangle"></i>
        <ul style="margin: 5px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Tombol Tambah Kejuaraan -->
<div style="margin-bottom: 20px;">
    <button class="btn btn-primary" onclick="showAddForm()">
        <i class="fas fa-plus"></i> Tambah Kejuaraan Baru
    </button>
</div>

<!-- Form Tambah Kejuaraan (Hidden) -->
<div id="addForm" class="dashboard-card" style="display: none; margin-bottom: 30px;">
    <h3 class="card-title">Tambah Kejuaraan Baru</h3>
    
    <form action="{{ route('admin.iuran-kejuaraan.store') }}" method="POST" style="display: grid; gap: 15px;">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Kejuaraan</label>
                <input type="text" name="nama_kejuaraan" class="form-input" placeholder="Contoh: Kejuaraan Renang Nasional 2026" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Penyelenggara</label>
                <input type="text" name="penyelenggara" class="form-input" placeholder="Contoh: PRSI Jakarta" required>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Tanggal Kejuaraan</label>
                <input type="date" name="tanggal_kejuaraan" class="form-input" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Lokasi</label>
                <input type="text" name="lokasi" class="form-input" placeholder="Contoh: GBK Aquatic Center" required>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Biaya Pendaftaran per Siswa</label>
                <input type="number" name="biaya_pendaftaran" class="form-input" placeholder="250000" min="0" step="1000" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Batas Pendaftaran</label>
                <input type="date" name="batas_pendaftaran" class="form-input" required>
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Deskripsi Kejuaraan</label>
            <textarea name="deskripsi" class="form-input" rows="3" placeholder="Deskripsi kejuaraan, kategori yang dilombakan, dll..." required></textarea>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kategori Peserta</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-top: 10px;">
                @if(isset($availableCategories) && $availableCategories->count() > 0)
                    @foreach($availableCategories as $category)
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="kategori[]" value="{{ $category }}">
                        <span>{{ $category }}</span>
                    </label>
                    @endforeach
                @endif
                
                <!-- Manual categories -->
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="kategori[]" value="KU-6">
                    <span>KU-6</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="kategori[]" value="KU-8">
                    <span>KU-8</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="kategori[]" value="Senior">
                    <span>Senior</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="kategori[]" value="Master">
                    <span>Master</span>
                </label>
            </div>
        </div>
        
        <div class="action-buttons" style="justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="hideAddForm()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

<!-- Daftar Kejuaraan -->
<div class="data-table">
    <div class="table-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h4>Daftar Kejuaraan</h4>
            <div style="display: flex; gap: 10px; align-items: center;">
                <select style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Semua Status</option>
                    <option value="pendaftaran">Pendaftaran</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
                <input type="text" placeholder="Search..." style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama Kejuaraan</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Tanggal</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Lokasi</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Biaya</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Peserta</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($kejuaraaans) && $kejuaraaans->count() > 0)
                    @foreach($kejuaraaans as $index => $kejuaraan)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <strong>{{ $kejuaraan->nama_kejuaraan }}</strong>
                            <br><small style="color: #666;">{{ $kejuaraan->penyelenggara }}</small>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $kejuaraan->tanggal_kejuaraan->format('d M Y') }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $kejuaraan->lokasi }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Rp {{ number_format($kejuaraan->biaya_pendaftaran, 0, ',', '.') }}</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            @php $total = $kejuaraan->pembayarans_count ?? 0; $lunas = $kejuaraan->lunas_count ?? 0; $belum = $kejuaraan->belum_bayar_count ?? 0; @endphp
                            <span style="color: #4caf50; font-weight: bold;">{{ $total }}</span> peserta
                            <br><small style="color: #4caf50;"><i class="fas fa-check-circle"></i> {{ $lunas }} lunas</small>
                            <small style="color: #f44336; margin-left: 6px;"><i class="fas fa-clock"></i> {{ $belum }} belum</small>
                            <br><small style="color: #999;">Batas: {{ $kejuaraan->batas_pendaftaran->format('d M Y') }}</small>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge {{ $kejuaraan->status_class }}">{{ $kejuaraan->status_label }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.iuran-kejuaraan.peserta', $kejuaraan) }}" class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;" title="Kelola Peserta & Pembayaran">
                                    <i class="fas fa-users"></i>
                                </a>
                                <button class="btn btn-warning" style="padding: 5px 8px; font-size: 11px;" onclick="editStatus({{ $kejuaraan->id }}, '{{ addslashes($kejuaraan->nama_kejuaraan) }}', '{{ $kejuaraan->status }}')" title="Edit Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="8" style="padding: 40px; text-align: center; color: #666;">
                        <i class="fas fa-trophy" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                        <br>Belum ada kejuaraan yang terdaftar
                        <br><small>Klik tombol "Tambah Kejuaraan Baru" untuk menambah kejuaraan</small>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Statistik Kejuaraan -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
    <div style="background: linear-gradient(135deg, #ff9800, #f57c00); color: white; padding: 20px; border-radius: 8px; text-align: center;">
        <h3 style="margin: 0; font-size: 24px;">{{ isset($kejuaraaans) ? $kejuaraaans->count() : 0 }}</h3>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Total Kejuaraan</p>
    </div>
    <div style="background: linear-gradient(135deg, #4caf50, #45a049); color: white; padding: 20px; border-radius: 8px; text-align: center;">
        <h3 style="margin: 0; font-size: 24px;">{{ isset($kejuaraaans) ? $kejuaraaans->where('status', 'pendaftaran')->count() : 0 }}</h3>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Pendaftaran Dibuka</p>
    </div>
    <div style="background: linear-gradient(135deg, #2196f3, #1976d2); color: white; padding: 20px; border-radius: 8px; text-align: center;">
        <h3 style="margin: 0; font-size: 24px;">{{ isset($kejuaraaans) ? $kejuaraaans->where('status', 'selesai')->count() : 0 }}</h3>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Kejuaraan Selesai</p>
    </div>
    <div style="background: linear-gradient(135deg, #9c27b0, #7b1fa2); color: white; padding: 20px; border-radius: 8px; text-align: center;">
        <h3 style="margin: 0; font-size: 24px;">Rp {{ isset($kejuaraaans) ? number_format($kejuaraaans->sum('biaya_pendaftaran'), 0, ',', '.') : '0' }}</h3>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Total Biaya Pendaftaran</p>
    </div>
</div>

<!-- Modal Edit Status -->
<div id="editStatusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
        <h3 style="margin: 0 0 20px 0; color: #333;">Edit Status Kejuaraan</h3>
        
        <form id="editStatusForm" method="POST" style="display: grid; gap: 15px;">
            @csrf
            @method('PATCH')
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Kejuaraan</label>
                <input type="text" id="editNamaKejuaraan" class="form-input" readonly style="background: #f5f5f5;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status Kejuaraan</label>
                <select name="status" id="editStatus" class="form-input" required>
                    <option value="akan_datang">Akan Datang</option>
                    <option value="pendaftaran">Pendaftaran Dibuka</option>
                    <option value="pendaftaran_tutup">Pendaftaran Ditutup</option>
                    <option value="berlangsung">Sedang Berlangsung</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-input" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
            </div>
            
            <div class="action-buttons" style="justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin mengubah status kejuaraan ini?')">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div style="margin-top: 20px;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<script>
function showAddForm() {
    document.getElementById('addForm').style.display = 'block';
}

function hideAddForm() {
    document.getElementById('addForm').style.display = 'none';
}

function editStatus(id, namaKejuaraan, currentStatus) {
    // Set form action URL
    document.getElementById('editStatusForm').action = `/admin/iuran-kejuaraan/${id}/status`;
    
    // Fill form data
    document.getElementById('editNamaKejuaraan').value = namaKejuaraan;
    document.getElementById('editStatus').value = currentStatus;
    
    // Show modal
    document.getElementById('editStatusModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editStatusModal').style.display = 'none';
    document.getElementById('editStatusForm').reset();
}

// Close modal when clicking outside
document.getElementById('editStatusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});
</script>

<style>
    .form-input, .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #d32f2f;
    }
    
    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }
    
    input[type="number"] {
        text-align: right;
    }

    /* Modal Styles */
    #editStatusModal {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    #editStatusModal > div {
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { transform: translate(-50%, -60%); opacity: 0; }
        to { transform: translate(-50%, -50%); opacity: 1; }
    }

    /* Status Badge Styles */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-akan-datang {
        background: #e3f2fd;
        color: #1976d2;
    }

    .status-pendaftaran {
        background: #fff3e0;
        color: #f57c00;
    }

    .status-pendaftaran-tutup {
        background: #ffebee;
        color: #d32f2f;
    }

    .status-berlangsung {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .status-selesai {
        background: #e8f5e8;
        color: #4caf50;
    }

    .status-dibatalkan {
        background: #fafafa;
        color: #757575;
    }

    /* Button hover effects */
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
        border: none;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #f57c00, #ef6c00);
    }
</style>
@endsection