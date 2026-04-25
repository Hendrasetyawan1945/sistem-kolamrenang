# Fix: Error Undefined Variable $templateRapor

## Masalah
Error `Undefined variable $templateRapor` terjadi di file `resources/views/coach/rapor/create.blade.php` pada baris 36.

## Penyebab
File `create.blade.php` masih menggunakan struktur lama yang mencoba mengakses variable `$templateRapor` yang tidak dikirim dari controller, dan tidak konsisten dengan sistem baru yang telah dibuat.

## Solusi yang Diterapkan

### 1. **Update View Structure**
File `resources/views/coach/rapor/create.blade.php` telah diubah dari:
- Form input rapor langsung (struktur lama)
- Menjadi halaman pemilihan siswa (struktur baru yang konsisten)

### 2. **New Features Added**
- **Card-based layout** untuk setiap siswa
- **Status indicator** - menunjukkan apakah rapor sudah dibuat atau belum
- **Action buttons** - berbeda untuk siswa yang sudah/belum punya rapor
- **Template information** - menampilkan template yang tersedia
- **Consistent navigation** - tombol kembali yang sesuai dengan filter

### 3. **Improved User Experience**
- **Visual indicators**: 
  - Border hijau untuk siswa yang sudah punya rapor
  - Border biru untuk siswa yang belum punya rapor
- **Clear actions**:
  - "Buat Rapor" untuk siswa baru
  - "Lihat Rapor" dan "Edit Rapor" untuk siswa yang sudah ada
- **Status badges**: Aktif/Tidak Aktif untuk siswa
- **Template preview**: Informasi template yang tersedia

### 4. **Data Flow**
Controller `create()` method sudah mengirim semua variable yang diperlukan:
```php
return view('coach.rapor.create', compact(
    'siswaList', 'kelasSaya', 'bulan', 'kelasId', 
    'templates', 'bulanInt', 'tahunInt', 'namaBulan'
));
```

### 5. **Integration with New System**
- Menggunakan route `coach.rapor.siswa` untuk form input rapor per siswa
- Konsisten dengan component-based architecture yang telah dibuat
- Terintegrasi dengan sistem filter bulan/tahun

## Hasil Perbaikan

### Before (Error):
```
ErrorException: Undefined variable $templateRapor
```

### After (Working):
- ✅ Halaman pemilihan siswa yang user-friendly
- ✅ Status rapor yang jelas untuk setiap siswa  
- ✅ Navigation yang konsisten
- ✅ Integration dengan sistem baru
- ✅ No more undefined variable errors

## Testing
1. **Access**: `/coach/rapor/create` - Halaman pemilihan siswa
2. **Select student**: Klik "Buat Rapor" untuk siswa baru
3. **Redirect**: Akan diarahkan ke form input rapor menggunakan component
4. **Existing rapor**: Siswa yang sudah punya rapor menampilkan tombol "Lihat" dan "Edit"

## Files Modified
- `resources/views/coach/rapor/create.blade.php` - Complete rewrite
- `app/Http/Controllers/Coach/RaporController.php` - Already updated in previous fix

## Conclusion
Error `$templateRapor` undefined telah diperbaiki dengan mengubah struktur halaman create menjadi halaman pemilihan siswa yang konsisten dengan sistem baru. Sekarang coach dapat dengan mudah melihat status rapor setiap siswa dan mengambil tindakan yang sesuai.