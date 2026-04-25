# Kredensial Login Coach - UPDATED

## Status: ✅ SELESAI - Semua Coach Sudah Bisa Login

Semua coach yang ada di sistem sekarang sudah memiliki akun login yang berfungsi.

## 👥 **Daftar Kredensial Login Coach:**

### 1. **Budi Santoso**
- **Email:** budi@youthswimming.com
- **Password:** eELfx8Tr
- **Status:** ✅ Aktif - Bisa Login
- **Spesialisasi:** Renang Gaya Bebas

### 2. **Sari Dewi**
- **Email:** sari@youthswimming.com
- **Password:** S9tsKgVp
- **Status:** ✅ Aktif - Bisa Login
- **Spesialisasi:** Renang Gaya Punggung

### 3. **Ahmad Fauzi**
- **Email:** ahmad@youthswimming.com
- **Password:** kqMv9Rpw
- **Status:** ✅ Aktif - Bisa Login
- **Spesialisasi:** Renang Gaya Dada

### 4. **Prabowo**
- **Email:** wowow@gmail.com
- **Password:** GLd83zgO
- **Status:** ✅ Aktif - Bisa Login
- **Spesialisasi:** Freestyle, Backstroke

## 🔐 **Cara Login untuk Coach:**

1. Buka halaman login: `/login`
2. Masukkan email dan password sesuai daftar di atas
3. Sistem akan redirect ke dashboard coach: `/coach/dashboard`
4. Coach bisa akses semua fitur coach (rapor, absensi, catatan waktu, dll)

## 🎯 **Fitur yang Tersedia untuk Coach:**

### **Dashboard Coach** (`/coach/dashboard`)
- Overview data siswa
- Statistik kehadiran
- Ringkasan rapor

### **Manajemen Siswa** (`/coach/siswa`)
- Lihat daftar siswa
- Detail profil siswa
- Riwayat perkembangan

### **Absensi** (`/coach/absensi`)
- Input kehadiran siswa
- Lihat riwayat absensi
- Generate laporan kehadiran

### **Catatan Waktu** (`/coach/catatan-waktu`)
- Input waktu renang siswa
- Track progress waktu
- Analisis performa

### **Rapor** (`/coach/rapor`)
- Buat rapor perkembangan siswa
- Edit rapor existing
- Lihat riwayat rapor

## 🔧 **Untuk Admin:**

### **Manajemen Password Coach:**
1. Buka `/admin/coach`
2. Lihat kolom "Password Login" untuk melihat password aktual
3. Gunakan tombol show/hide (👁️) untuk melihat password
4. Gunakan tombol copy (📋) untuk copy password
5. Gunakan tombol reset (🔑) untuk generate password baru

### **Edit Coach:**
1. Klik tombol edit pada coach yang ingin diubah
2. Password lama akan ditampilkan di bagian atas form
3. Pilih opsi:
   - **Tetap gunakan password saat ini** (tidak berubah)
   - **Set password manual** (input password baru)

## 🧪 **Testing Login:**

Untuk test apakah login berfungsi:

```bash
# Test via browser
1. Buka /login
2. Input email: budi@youthswimming.com
3. Input password: eELfx8Tr
4. Klik Login
5. Harus redirect ke /coach/dashboard

# Test via tinker
php artisan tinker
Auth::attempt(['email' => 'budi@youthswimming.com', 'password' => 'eELfx8Tr'])
# Harus return true
```

## 📋 **Troubleshooting:**

### **Jika Coach Tidak Bisa Login:**
1. Cek email dan password di dashboard admin
2. Pastikan status coach "Aktif"
3. Cek apakah ada akun User terkait
4. Reset password jika perlu

### **Jika Password Tidak Muncul di Dashboard:**
1. Pastikan coach punya akun User
2. Cek kolom `current_password` di database
3. Reset password untuk generate ulang

## 🔒 **Keamanan:**

- Password di-hash dengan bcrypt di database users
- Plain password hanya disimpan untuk referensi admin
- Session management otomatis handle logout
- Role-based access control untuk coach routes

## 📞 **Support:**

Jika ada masalah dengan login coach:
1. Cek dashboard admin untuk kredensial terbaru
2. Reset password jika diperlukan
3. Pastikan email coach sudah benar
4. Verify role user adalah 'coach'

**Semua coach sekarang sudah bisa login dan mengakses sistem!** ✅