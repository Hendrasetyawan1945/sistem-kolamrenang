# 🔐 Panduan Login - Youth Swimming Club

## 📍 URL Login

**Lokal/Development**: http://localhost:8001/login  
**Production**: http://[IP-VPS-ANDA]:8001/login

---

## 👥 Akun Login yang Tersedia

### 🔑 ADMIN (Super User)

**Email**: `admin@youthswimming.com`  
**Password**: `123456`

**Akses**:
- Dashboard Admin
- Kelola Siswa (Calon, Aktif, Cuti, Nonaktif)
- Kelola Keuangan (Iuran, Pembayaran, Pengeluaran)
- Kelola Absensi
- Kelola Prestasi & Catatan Waktu
- Kelola Jersey
- Kelola Laporan
- Kelola Pengaturan (Kelas, Coach, Kolam)
- Kelola Akun User

---

### 👨‍🏫 COACH (Pelatih)

| Nama | Email | Password |
|------|-------|----------|
| Budi Santoso | budi@youthswimming.com | 123456 |
| Sari Wijaya | sari@youthswimming.com | 123456 |
| Ahmad Hidayat | ahmad@youthswimming.com | 123456 |
| Wowow | wowow@gmail.com | 123456 |

**Akses**:
- Dashboard Coach
- Lihat Data Siswa
- Input Absensi
- Input Catatan Waktu
- Buat Rapor Siswa
- Input Pembayaran

---

### 👨‍🎓 SISWA

| No | Nama | Email | Password |
|----|------|-------|----------|
| 1 | Fillo Navyandra Bintang Irawan | siswa@youthswimming.com | **15052014** |
| 2 | Ghaisan Ghaits Fatih | ghaisan@youthswimming.com | **qwerty** |
| 3 | Heri Budiman | heri@youthswimming.com | **10032015** |
| 4 | Iwan Setiawan | iwan@youthswimming.com | **05112014** |
| 5 | Bella Anastasia | robert.anastasia@email.com | **08042015** |
| 6 | Citra Dewi Lestari | eko.lestari@email.com | **15102013** |

**Akses**:
- Dashboard Siswa
- Lihat Iuran & Pembayaran
- Lihat Rapor
- Lihat Prestasi & Catatan Waktu
- Lihat Kehadiran
- Lihat Pemesanan Jersey
- Update Profile

---

## 📝 Cara Login

1. **Buka browser** (Chrome, Firefox, Safari, Edge)
2. **Ketik URL**: http://localhost:8001/login
3. **Masukkan Email dan Password** sesuai tabel di atas
4. **Klik tombol "Masuk"**
5. **Sistem akan redirect otomatis** ke dashboard sesuai role:
   - Admin → Dashboard Admin
   - Coach → Dashboard Coach
   - Siswa → Dashboard Siswa

---

## 🔧 Troubleshooting

### ❌ "Email atau password salah"

**Solusi**:
1. Pastikan email ditulis dengan benar (huruf kecil semua)
2. Pastikan password ditulis persis seperti di tabel (case-sensitive)
3. Cek apakah ada spasi di awal/akhir email atau password
4. Coba copy-paste email dan password dari tabel ini

### ❌ Halaman tidak bisa dibuka

**Solusi**:
1. Pastikan server Laravel sudah berjalan
2. Cek dengan command: `ps aux | grep php`
3. Jika belum jalan, start server: `cd youth-swimming-club && php artisan serve --host=0.0.0.0 --port=8001`

### ❌ Redirect ke halaman yang salah

**Solusi**:
1. Logout dulu dari sistem
2. Clear browser cache (Ctrl+Shift+Delete)
3. Login ulang dengan akun yang benar

---

## 🎯 Tips untuk Admin

### Cara Melihat Password Siswa

1. Login sebagai Admin
2. Buka menu **"Siswa Aktif"**
3. Lihat kolom **"Password"** di tabel
4. Klik tombol **copy** (📋) untuk copy password
5. Bagikan password ke siswa/orang tua

### Cara Mengubah Password Siswa

1. Login sebagai Admin
2. Buka menu **"Siswa Aktif"**
3. Klik tombol **🔑** (Edit Password) di kolom Aksi
4. Pilih jenis password:
   - **Default**: 123456
   - **Tanggal Lahir**: Format ddmmyyyy (contoh: 15052014)
   - **Custom**: Buat password sendiri
5. Klik **"Simpan Password Baru"**
6. Password baru akan langsung aktif

### Cara Membuat Akun untuk Siswa Baru

**Opsi 1: Otomatis dari Tabel Siswa Aktif**
1. Buka menu **"Siswa Aktif"**
2. Klik tombol **"Buat Akun"** di bagian atas
3. Pilih siswa yang akan dibuatkan akun
4. Klik **"Buat Akun (Password Default)"**
5. Akun akan dibuat dengan password default 123456

**Opsi 2: Manual dari Menu Kelola Akun**
1. Buka menu **"Kelola Akun"**
2. Klik **"Tambah Akun"**
3. Isi form dengan data siswa
4. Pilih password
5. Klik **"Simpan"**

---

## 📱 Akses dari HP/Tablet

Sistem ini **responsive** dan bisa diakses dari HP atau tablet:

1. Pastikan HP/tablet terhubung ke **jaringan yang sama** dengan server
2. Buka browser di HP
3. Ketik URL: http://[IP-SERVER]:8001/login
4. Login seperti biasa

---

## 🔒 Keamanan

### Untuk Admin:
- ⚠️ **Jangan bagikan password admin** ke orang lain
- 🔄 **Ganti password admin** secara berkala
- 📝 **Catat perubahan password** yang dilakukan

### Untuk Siswa/Coach:
- 🔐 **Jangan bagikan password** ke teman
- 📧 **Jangan gunakan password yang sama** dengan email pribadi
- 💬 **Hubungi admin** jika lupa password

---

## 📞 Bantuan

Jika mengalami masalah login atau akses sistem, hubungi:

**Administrator Sistem**  
Email: admin@youthswimming.com

---

**Terakhir diupdate**: 23 April 2026  
**Versi Sistem**: 1.0
