# Test Form Registrasi - Perbaikan Pilihan Kelas

## 🔧 Masalah yang Diperbaiki

**Problem**: Form registrasi tidak menampilkan pilihan kelas karena tidak ada data kelas di database.

**Solution**: 
1. ✅ Membuat KelasSeeder dengan 6 kelas dan 3 coach
2. ✅ Memperbaiki query di RegisterController
3. ✅ Update form registrasi untuk menampilkan harga
4. ✅ Menambah accessor `nama` di model Kelas

## 📊 Data Kelas yang Dibuat

### **Kelas Tersedia:**
1. **Pemula A** - Level: Pemula - Rp 300.000
2. **Pemula B** - Level: Pemula - Rp 300.000  
3. **Menengah A** - Level: Menengah - Rp 400.000
4. **Menengah B** - Level: Menengah - Rp 400.000
5. **Lanjut A** - Level: Lanjut - Rp 500.000
6. **Prestasi** - Level: Prestasi - Rp 600.000

### **Coach yang Dibuat:**
1. **Budi Santoso** - Spesialisasi: Renang Gaya Bebas
2. **Sari Dewi** - Spesialisasi: Renang Gaya Punggung  
3. **Ahmad Fauzi** - Spesialisasi: Renang Gaya Dada

## 🚀 Cara Test Form Registrasi

### **URL**: `/daftar`

### **Test Steps:**
1. **Buka halaman registrasi** → `/daftar`
2. **Isi form lengkap**:
   - Nama: Test Siswa
   - Tanggal Lahir: 01/01/2010
   - Jenis Kelamin: Laki-laki
   - **Kelas**: Pilih salah satu (sekarang sudah ada 6 pilihan)
   - Alamat: Jl. Test No. 123
   - Nama Orang Tua: Bapak Test
   - Telepon: 081234567890
   - Email: test@example.com
3. **Submit form** → Redirect ke halaman sukses
4. **Cek database** → Siswa baru dengan status "calon"

### **Expected Result:**
- ✅ Dropdown kelas menampilkan 6 pilihan
- ✅ Setiap pilihan menampilkan nama, level, dan harga
- ✅ Form bisa disubmit tanpa error
- ✅ Data tersimpan dengan status "calon"

## 🔧 Perbaikan yang Dilakukan

### **1. KelasSeeder.php**
```php
// Membuat 6 kelas dengan coach
$kelasList = [
    ['nama_kelas' => 'Pemula A', 'level' => 'pemula', 'harga' => 300000],
    ['nama_kelas' => 'Pemula B', 'level' => 'pemula', 'harga' => 300000],
    // ... dst
];
```

### **2. RegisterController.php**
```php
public function showForm()
{
    // Query yang lebih robust
    $kelasList = Kelas::where(function($query) {
        $query->where('aktif', true)->orWhereNull('aktif');
    })->get();
    
    // Fallback jika masih kosong
    if ($kelasList->isEmpty()) {
        $kelasList = Kelas::all();
    }
}
```

### **3. register.blade.php**
```html
<!-- Dropdown dengan harga -->
<option value="{{ $kelas->id }}">
    {{ $kelas->nama_kelas }} - {{ $kelas->level_label }} 
    (Rp {{ number_format($kelas->harga, 0, ',', '.') }})
</option>
```

### **4. Model Kelas**
```php
// Accessor untuk backward compatibility
public function getNamaAttribute(): string
{
    return $this->nama_kelas;
}
```

## 🎯 Hasil Akhir

### **Form Registrasi Sekarang:**
- ✅ **6 Pilihan Kelas** tersedia
- ✅ **Informasi Lengkap** (nama, level, harga)
- ✅ **Validasi Berfungsi** 
- ✅ **Data Tersimpan** dengan benar

### **Dropdown Kelas Menampilkan:**
```
Pemula A - Pemula (Rp 300.000)
Pemula B - Pemula (Rp 300.000)
Menengah A - Menengah (Rp 400.000)
Menengah B - Menengah (Rp 400.000)
Lanjut A - Lanjut (Rp 500.000)
Prestasi - Prestasi (Rp 600.000)
```

## 🔄 Next Steps

### **Untuk Admin:**
1. **Cek calon siswa** → Menu Siswa → Calon Siswa
2. **Aktivasi siswa** → Ubah status dari "calon" ke "aktif"
3. **Generate akun** → Buat akun login untuk siswa

### **Untuk Development:**
- ✅ Form registrasi sudah berfungsi
- ✅ Data kelas sudah tersedia
- ✅ Seeder bisa dijalankan ulang jika diperlukan
- ✅ Sistem siap untuk production

## 🎉 Status

**✅ FIXED**: Form registrasi sekarang bisa memilih kelas dengan normal!

**Test URL**: `/daftar`
**Admin URL**: `/admin/calon-siswa` (untuk melihat hasil registrasi)