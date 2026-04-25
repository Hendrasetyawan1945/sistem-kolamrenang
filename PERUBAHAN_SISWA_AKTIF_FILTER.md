# Perubahan Halaman Siswa Aktif - Filter Tabs & Hapus Buat Akun Massal

## 📋 Ringkasan Perubahan

Dokumen ini mencatat perubahan yang dilakukan pada halaman **Siswa Aktif** sesuai permintaan:

### ✅ PERUBAHAN 1: Filter Tabs pada Statistik
Mengubah item statistik menjadi tombol filter yang dapat diklik:
- **Total** → Filter semua siswa aktif (default)
- **Punya Akun** → Filter siswa yang sudah punya akun (STATUS AKUN = "Ada Akun")
- **Belum Akun** → Filter siswa yang belum punya akun (STATUS AKUN = "Siap Dibuat")
- **Email Valid** → Tetap sebagai info statis (tidak dijadikan filter)

### ✅ PERUBAHAN 2: Hapus Section Buat Akun Massal
Menghapus seluruh section/modal "Buat Akun Massal untuk Siswa":
- Modal dialog dengan form checkbox list siswa
- Tombol "Pilih Semua" dan "Batal Semua"
- Tombol trigger "Buat Akun (12)" di stats bar
- Semua logic/handler terkait fitur ini

**Catatan:** Tombol "Kelola Akun" tetap dipertahankan.

---

## 🔧 Detail Implementasi

### File yang Dimodifikasi
- `resources/views/admin/siswa/siswa-aktif.blade.php`

### 1. Perubahan CSS

#### Tambahan Style untuk Filter Tabs
```css
.stat-item {
    font-size: 0.8rem;
    color: #6c757d;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.stat-item:hover {
    background: #f8f9fa;
}

.stat-item.active {
    background: #e3f2fd;
    border-color: #2196f3;
    color: #1976d2;
}

.stat-item.static {
    cursor: default;
    padding: 0;
}

.stat-item.static:hover {
    background: transparent;
}

.stat-item.active .stat-number {
    color: #1976d2;
}
```

### 2. Perubahan HTML

#### Stats Bar (Sebelum)
```html
<div class="stat-item">
    Total: <span class="stat-number">{{ $totalSiswa }}</span>
</div>
```

#### Stats Bar (Sesudah)
```html
<div class="stat-item active" data-filter="all" onclick="filterSiswa('all')">
    Total: <span class="stat-number">{{ $totalSiswa }}</span>
</div>
<div class="stat-item" data-filter="punya-akun" onclick="filterSiswa('punya-akun')">
    Punya Akun: <span class="stat-number">{{ $sudahPunyaAkun }}</span>
</div>
<div class="stat-item" data-filter="belum-akun" onclick="filterSiswa('belum-akun')">
    Belum Akun: <span class="stat-number">{{ $belumPunyaAkun }}</span>
</div>
<div class="stat-item static">
    Email Valid: <span class="stat-number">{{ $emailValid }}</span>
</div>
```

#### Table Row (Sebelum)
```html
<tr>
    <td class="text-center">{{ $index + 1 }}</td>
```

#### Table Row (Sesudah)
```html
<tr class="siswa-row" 
    data-has-akun="{{ $siswa->user ? 'true' : 'false' }}"
    data-status-akun="{{ $siswa->user ? 'ada-akun' : ($siswa->email && filter_var($siswa->email, FILTER_VALIDATE_EMAIL) ? 'siap-dibuat' : 'email-invalid') }}">
    <td class="text-center">{{ $index + 1 }}</td>
```

#### Tombol Buat Akun Massal (DIHAPUS)
```html
<!-- DIHAPUS -->
@if($belumPunyaAkun > 0)
    <button type="button" class="btn-compact btn-success-compact" onclick="openBulkAkunModal()">
        <i class="fas fa-users me-1"></i>Buat Akun ({{ $belumPunyaAkun }})
    </button>
@endif
```

### 3. Perubahan JavaScript

#### Fungsi Filter Baru
```javascript
function filterSiswa(filter) {
    // Update active state pada tabs
    document.querySelectorAll('.stat-item[data-filter]').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.stat-item[data-filter="${filter}"]`).classList.add('active');
    
    // Filter rows
    const rows = document.querySelectorAll('.siswa-row');
    rows.forEach(row => {
        const hasAkun = row.getAttribute('data-has-akun');
        const statusAkun = row.getAttribute('data-status-akun');
        
        let shouldShow = false;
        
        if (filter === 'all') {
            shouldShow = true;
        } else if (filter === 'punya-akun') {
            shouldShow = hasAkun === 'true';
        } else if (filter === 'belum-akun') {
            shouldShow = hasAkun === 'false' && statusAkun === 'siap-dibuat';
        }
        
        row.style.display = shouldShow ? '' : 'none';
    });
}
```

#### Fungsi yang Dihapus
- `openBulkAkunModal()` - DIHAPUS
- `selectAllSiswa()` - DIHAPUS
- `deselectAllSiswa()` - DIHAPUS

#### Modal HTML yang Dihapus
- Seluruh `<div class="modal fade" id="bulkAkunModal">` beserta isinya - DIHAPUS

---

## 🎯 Cara Kerja Filter

### Filter Logic
1. **Total (all)**: Menampilkan semua siswa aktif
2. **Punya Akun (punya-akun)**: Menampilkan siswa dengan `data-has-akun="true"`
3. **Belum Akun (belum-akun)**: Menampilkan siswa dengan `data-has-akun="false"` DAN `data-status-akun="siap-dibuat"`

### Data Attributes pada Row
- `data-has-akun`: `"true"` atau `"false"` (apakah siswa punya user account)
- `data-status-akun`: 
  - `"ada-akun"` → Siswa sudah punya akun
  - `"siap-dibuat"` → Siswa belum punya akun tapi email valid
  - `"email-invalid"` → Siswa belum punya akun dan email tidak valid

### Visual Feedback
- Tab aktif mendapat background biru muda (`#e3f2fd`)
- Tab aktif mendapat border biru (`#2196f3`)
- Hover effect pada semua tab (kecuali "Email Valid")
- Smooth transition saat berpindah filter

---

## ✅ Checklist Perubahan

- [x] Tambah CSS untuk filter tabs (hover, active state)
- [x] Ubah stat-item menjadi clickable dengan data-filter attribute
- [x] Tambah onclick handler pada setiap filter tab
- [x] Tambah data attributes pada table rows (data-has-akun, data-status-akun)
- [x] Implementasi fungsi filterSiswa() di JavaScript
- [x] Hapus tombol "Buat Akun (12)" dari stats bar
- [x] Hapus seluruh modal "Buat Akun Massal"
- [x] Hapus fungsi openBulkAkunModal(), selectAllSiswa(), deselectAllSiswa()
- [x] Pertahankan tombol "Kelola Akun"
- [x] Pertahankan struktur tabel yang ada (tidak ada perubahan kolom)

---

## 🧪 Testing

### Skenario Testing
1. **Filter Total**: Klik "Total" → Semua siswa tampil
2. **Filter Punya Akun**: Klik "Punya Akun" → Hanya siswa dengan badge "✓ Ada Akun" yang tampil
3. **Filter Belum Akun**: Klik "Belum Akun" → Hanya siswa dengan badge "⏳ Siap Dibuat" yang tampil
4. **Visual Feedback**: Tab yang diklik mendapat highlight biru
5. **Tombol Kelola Akun**: Masih berfungsi normal
6. **Modal Buat Akun Massal**: Tidak ada lagi (sudah dihapus)

### Expected Behavior
- Filter bekerja secara client-side (tanpa reload halaman)
- Transisi smooth saat berpindah filter
- Tidak ada error di console browser
- Struktur tabel tetap sama (tidak ada perubahan kolom)

---

## 📝 Catatan Tambahan

### Fitur yang Dipertahankan
- ✅ Tombol "Kelola Akun" di pojok kanan atas
- ✅ Tombol "Buat Akun" individual per siswa (tombol hijau dengan icon +)
- ✅ Semua kolom tabel (No, Siswa, Email, Password, Kelas, Status Akun, Aksi)
- ✅ Semua action buttons per row (Edit, Akun, Edit Password, Cuti, Nonaktif, Hapus)
- ✅ Fungsi copy password

### Fitur yang Dihapus
- ❌ Tombol "Buat Akun (12)" di stats bar
- ❌ Modal "Buat Akun Massal untuk Siswa"
- ❌ Checkbox list untuk memilih siswa massal
- ❌ Tombol "Pilih Semua" dan "Batal Semua"
- ❌ Form submit untuk buat akun massal

### Alternatif untuk Buat Akun Massal
Pengguna sekarang harus membuat akun secara individual menggunakan tombol hijau "+" pada kolom Aksi untuk setiap siswa yang belum punya akun.

---

**Tanggal Perubahan:** 23 April 2026  
**Status:** ✅ Selesai  
**Tested:** Belum (perlu testing manual di browser)
