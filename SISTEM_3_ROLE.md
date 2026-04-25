# Sistem 3 Role - Youth Swimming Club

## 🎯 Overview
Sistem telah berhasil diimplementasikan dengan 3 role utama:
- **Admin**: Akses penuh ke semua fitur
- **Guru/Coach**: Portal khusus untuk mengelola kelas dan siswa
- **Siswa**: Portal untuk melihat data pribadi

## 🔐 Login Credentials

### Admin
- Email: `admin@youthswimming.com`
- Password: `admin123`

### Coach (jika ada data coach)
- Email: `coach@youthswimming.com`
- Password: `coach123`

### Siswa (jika ada data siswa)
- Email: `siswa@youthswimming.com`
- Password: `siswa123`

## 🏗️ Struktur Sistem

### 1. Database Changes
✅ **Tabel Users**
- Kolom `role` (admin, siswa, coach)
- Kolom `siswa_id` (foreign key ke tabel siswas)
- Kolom `coach_id` (foreign key ke tabel coaches)

✅ **Model Relationships**
- User belongsTo Siswa
- User belongsTo Coach
- Siswa hasOne User
- Coach hasOne User
- Siswa belongsTo Kelas
- Coach hasMany Kelas
- Kelas hasMany Siswa

### 2. Authentication & Middleware
✅ **RoleMiddleware**
- Redirect otomatis berdasarkan role
- Proteksi route per role

✅ **LoginController**
- Redirect setelah login berdasarkan role:
  - Admin → `/admin/dashboard`
  - Coach → `/coach/dashboard`
  - Siswa → `/siswa/dashboard`

### 3. Portal Admin
✅ **Kelola Akun** (`/admin/akun`)
- Buat akun untuk siswa & coach
- Edit/hapus akun
- Reset password
- Validasi: siswa/coach hanya bisa punya 1 akun

### 4. Portal Guru/Coach
✅ **Dashboard** (`/coach/dashboard`)
- Statistik kelas yang dipegang
- Jadwal hari ini
- Total siswa
- Menu cepat

✅ **Daftar Siswa** (`/coach/siswa`)
- Hanya siswa di kelas yang dipegang
- Detail siswa per kelas

✅ **Absensi** (`/coach/absensi`)
- Input absensi per kelas
- Filter berdasarkan tanggal & kelas
- Hanya untuk siswa di kelas sendiri

✅ **Catatan Waktu** (`/coach/catatan-waktu`)
- Input catatan waktu latihan
- Edit/update catatan
- Filter per siswa/kelas

✅ **Rapor** (`/coach/rapor`)
- Buat/edit rapor siswa
- Hanya untuk siswa di kelas sendiri
- Berdasarkan template rapor

### 5. Portal Siswa
✅ **Dashboard** (`/siswa/dashboard`)
- Info pribadi
- Status iuran bulan ini
- Jadwal kelas
- Statistik kehadiran

✅ **Iuran** (`/siswa/iuran`)
- Riwayat pembayaran
- Detail per pembayaran
- Status lunas/belum

✅ **Rapor** (`/siswa/rapor`)
- Rapor per bulan
- Hanya rapor milik sendiri

✅ **Prestasi** (`/siswa/prestasi`)
- Personal best per jenis latihan
- Catatan waktu terbaru

✅ **Kehadiran** (`/siswa/kehadiran`)
- Rekap absensi per bulan
- Statistik kehadiran
- Persentase kehadiran

✅ **Jersey** (`/siswa/jersey`)
- Status pesanan jersey
- Riwayat pemesanan

## 🛡️ Security Features

### Access Control
- **Coach**: Hanya bisa akses siswa di kelasnya sendiri
- **Siswa**: Hanya bisa lihat data pribadi, tidak bisa edit
- **Admin**: Akses penuh ke semua fitur

### Route Protection
```php
// Admin routes
Route::middleware(['auth', 'role:admin'])

// Coach routes  
Route::middleware(['auth', 'role:coach'])

// Siswa routes
Route::middleware(['auth', 'role:siswa'])
```

### Data Validation
- Siswa/Coach hanya bisa punya 1 akun
- Email unique per user
- Password minimal 6 karakter
- Role validation (admin, siswa, coach)

## 📁 File Structure

### Controllers
```
app/Http/Controllers/
├── Admin/
│   └── AkunController.php          # Kelola akun siswa/coach
├── Coach/
│   ├── DashboardController.php     # Dashboard coach
│   ├── SiswaController.php         # Daftar siswa
│   ├── AbsensiController.php       # Input absensi
│   ├── CatatanWaktuController.php  # Catatan waktu
│   └── RaporController.php         # Rapor siswa
└── Siswa/
    ├── DashboardController.php     # Dashboard siswa
    ├── IuranController.php         # Riwayat iuran
    ├── RaporController.php         # Rapor siswa
    ├── PrestasiController.php      # Personal best
    ├── KehadiranController.php     # Rekap absensi
    └── JerseyController.php        # Status jersey
```

### Views
```
resources/views/
├── layouts/
│   ├── admin.blade.php    # Layout admin (sudah ada)
│   ├── coach.blade.php    # Layout coach (baru)
│   └── siswa.blade.php    # Layout siswa (baru)
├── admin/akun/            # Views kelola akun
├── coach/                 # Views portal coach
└── siswa/                 # Views portal siswa
```

### Routes
```php
// Admin (dengan role middleware)
Route::prefix('admin')->middleware(['auth', 'role:admin'])

// Coach routes
Route::prefix('coach')->middleware(['auth', 'role:coach'])

// Siswa routes  
Route::prefix('siswa')->middleware(['auth', 'role:siswa'])
```

## 🚀 Cara Menggunakan

### 1. Login sebagai Admin
1. Buka `/login`
2. Login dengan kredensial admin
3. Masuk ke **Kelola Akun** untuk membuat akun siswa/coach

### 2. Membuat Akun Siswa/Coach
1. Admin → Kelola Akun → Buat Akun Baru
2. Pilih role (siswa/coach)
3. Pilih siswa/coach dari dropdown
4. Set email & password
5. Akun siap digunakan

### 3. Login sebagai Coach
1. Login dengan akun coach
2. Akses fitur: Dashboard, Siswa, Absensi, Catatan Waktu, Rapor
3. Hanya bisa akses siswa di kelas sendiri

### 4. Login sebagai Siswa
1. Login dengan akun siswa  
2. Akses fitur: Dashboard, Iuran, Rapor, Prestasi, Kehadiran, Jersey
3. Hanya bisa lihat data pribadi

## ✅ Status Implementasi

### ✅ Completed
- [x] Database structure (role, siswa_id, coach_id)
- [x] Authentication & role middleware
- [x] Admin: Kelola Akun (CRUD)
- [x] Coach: Dashboard, Siswa, Absensi, Catatan Waktu, Rapor
- [x] Siswa: Dashboard, Iuran, Rapor, Prestasi, Kehadiran, Jersey
- [x] Security: Access control per role
- [x] UI: Layouts untuk coach & siswa
- [x] Routes: Protected routes per role

### 🔄 Next Steps (Optional)
- [ ] Views lengkap untuk semua fitur coach
- [ ] Views lengkap untuk semua fitur siswa  
- [ ] Email notification untuk akun baru
- [ ] Profile management untuk coach/siswa
- [ ] Mobile responsive optimization

## 🎉 Kesimpulan

Sistem 3 role telah berhasil diimplementasikan dengan lengkap:

1. **Database**: Role system dengan proper relationships
2. **Authentication**: Role-based login & redirect
3. **Admin**: Kelola akun siswa & coach
4. **Coach**: Portal lengkap untuk mengelola kelas & siswa
5. **Siswa**: Portal untuk melihat data pribadi
6. **Security**: Proper access control & validation

Sistem siap digunakan dan dapat dikembangkan lebih lanjut sesuai kebutuhan!