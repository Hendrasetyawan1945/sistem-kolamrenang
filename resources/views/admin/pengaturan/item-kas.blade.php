@extends('layouts.admin')

@section('content')
<div class="club-header">
    <div class="club-logo">
        <i class="fas fa-list"></i>
    </div>
    <h1 class="club-title">Item Kas</h1>
</div>

<!-- Tombol Tambah Item -->
<div style="margin-bottom: 20px;">
    <button class="btn btn-primary" onclick="showAddForm()">
        <i class="fas fa-plus"></i> Tambah Item Kas
    </button>
</div>

<!-- Form Tambah Item (Hidden) -->
<div id="addForm" class="dashboard-card" style="display: none; margin-bottom: 30px;">
    <h3 class="card-title">Tambah Item Kas Baru</h3>
    
    <form action="{{ route('admin.item-kas.store') }}" method="POST" style="display: grid; gap: 15px;">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Item</label>
                <input type="text" name="nama_item" class="form-input" placeholder="Contoh: Gaji Coach" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <option value="pendapatan">Pendapatan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jenis</label>
                <select name="jenis" class="form-select" required>
                    <option value="">Pilih Jenis</option>
                    <optgroup label="Pendapatan">
                        <option value="iuran_rutin">Iuran Rutin</option>
                        <option value="iuran_insidentil">Iuran Insidentil</option>
                        <option value="iuran_kejuaraan">Iuran Kejuaraan</option>
                        <option value="penjualan_jersey">Penjualan Jersey</option>
                        <option value="pendaftaran">Biaya Pendaftaran</option>
                    </optgroup>
                    <optgroup label="Pengeluaran">
                        <option value="gaji_coach">Gaji Coach</option>
                        <option value="operasional">Operasional</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="peralatan">Peralatan</option>
                        <option value="listrik_air">Listrik & Air</option>
                    </optgroup>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status</label>
                <select name="status" class="form-select" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Deskripsi</label>
            <textarea name="deskripsi" class="form-input" rows="3" placeholder="Deskripsi item kas..."></textarea>
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

<!-- Tabs -->
<div style="margin-bottom: 20px;">
    <div style="display: flex; border-bottom: 2px solid #e0e0e0;">
        <button class="tab-btn active" onclick="showTab('pendapatan')" id="tab-pendapatan">
            <i class="fas fa-plus-circle"></i> Pendapatan
        </button>
        <button class="tab-btn" onclick="showTab('pengeluaran')" id="tab-pengeluaran">
            <i class="fas fa-minus-circle"></i> Pengeluaran
        </button>
    </div>
</div>

<!-- Tab Pendapatan -->
<div id="content-pendapatan" class="tab-content">
    <div class="data-table">
        <div class="table-header">
            <h4>Item Pendapatan</h4>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama Item</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Jenis</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Deskripsi</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">1</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Iuran Bulanan</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Iuran Rutin</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Iuran rutin bulanan siswa</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">Aktif</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">2</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Biaya Pendaftaran</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Pendaftaran</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Biaya pendaftaran siswa baru</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">Aktif</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">3</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Penjualan Jersey</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Penjualan Jersey</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Pendapatan dari penjualan jersey klub</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">Aktif</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab Pengeluaran -->
<div id="content-pengeluaran" class="tab-content" style="display: none;">
    <div class="data-table">
        <div class="table-header">
            <h4>Item Pengeluaran</h4>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">No</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Nama Item</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Jenis</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Deskripsi</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">1</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Gaji Coach</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Gaji Coach</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Gaji bulanan pelatih renang</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">Aktif</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">2</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Listrik & Air</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Listrik & Air</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Biaya listrik dan air kolam renang</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">Aktif</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">3</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;"><strong>Maintenance Kolam</strong></td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Maintenance</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">Biaya perawatan dan maintenance kolam</td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <span class="status-badge status-active">Aktif</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 5px 8px; font-size: 11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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

function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).style.display = 'block';
    
    // Add active class to selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
}
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
    
    .tab-btn {
        padding: 12px 20px;
        border: none;
        background: #f5f5f5;
        color: #666;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tab-btn.active {
        background: white;
        color: #d32f2f;
        border-bottom-color: #d32f2f;
    }
    
    .tab-btn:hover {
        background: #e0e0e0;
    }
</style>
@endsection