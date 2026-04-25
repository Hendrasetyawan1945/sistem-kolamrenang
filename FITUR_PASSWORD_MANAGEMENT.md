# 🔐 Fitur Password Management - Admin

## ✨ Fitur Baru yang Ditambahkan

Admin sekarang dapat **melihat** dan **mengganti password** akun siswa dan coach dengan mudah melalui menu **Kelola Akun**.

---

## 📋 Cara Menggunakan

### 1. **Akses Menu Kelola Akun**
- Login sebagai Admin: `admin@youthswimming.com` / `admin123`
- Masuk ke menu **Pengaturan** → **Kelola Akun**
- URL: `/admin/akun`

### 2. **Lihat Password Akun**
Di tabel daftar akun, kolom **Password** menampilkan:
- **Password tersembunyi** (••••••••) secara default
- **Tombol mata (👁️)** untuk show/hide password
- **Tombol copy (📋)** untuk copy password ke clipboard
- **Info password saat ini** di bawah tombol

**Cara lihat password:**
1. Klik tombol **mata (👁️)** di kolom Password
2. Password akan tampil dalam format plain text
3. Klik lagi untuk menyembunyikan

**Cara copy password:**
1. Klik tombol **copy (📋)** di kolom Password
2. Password otomatis ter-copy ke clipboard
3. Tombol akan berubah hijau (✓) selama 1 detik sebagai konfirmasi

### 3. **Ganti Password Akun**
**Cara 1: Dari Tabel Akun**
1. Klik tombol **kunci (🔑)** di kolom Aksi
2. Akan redirect ke halaman ganti password

**Cara 2: Dari Detail Akun**
1. Klik tombol **mata (👁️)** untuk lihat detail
2. Klik tombol **Edit Password**

### 4. **Halaman Ganti Password**
Tersedia 3 opsi password baru:

#### **A. Password Default**
- Password: `123456`
- Cocok untuk: Reset cepat, password sementara

#### **B. Password Tanggal Lahir** (khusus siswa)
- Format: `ddmmyyyy` (contoh: `15081995`)
- Otomatis menggunakan tanggal lahir siswa
- Cocok untuk: Password personal yang mudah diingat siswa

#### **C. Password Custom**
- Input manual password sesuai keinginan
- Minimal 6 karakter
- Cocok untuk: Password khusus sesuai permintaan

**Langkah ganti password:**
1. Pilih salah satu opsi password
2. Jika pilih Custom, masukkan password baru
3. Klik **Simpan Password Baru**
4. Password langsung ter-update dan bisa digunakan untuk login

---

## 🎯 Keunggulan Fitur

### **Untuk Admin:**
- ✅ **Lihat password real-time** tanpa perlu reset
- ✅ **Copy password** dengan 1 klik
- ✅ **Ganti password** dengan 3 opsi mudah
- ✅ **Interface user-friendly** dengan preview password
- ✅ **Tracking password** yang sedang aktif

### **Untuk Siswa/Coach:**
- ✅ **Password mudah diingat** (tanggal lahir atau default)
- ✅ **Tidak perlu menunggu** admin kasih tahu password baru
- ✅ **Langsung bisa login** setelah password diganti

---

## 📊 Data Password Saat Ini

### **Akun Siswa:**
| Nama | Email | Password Saat Ini |
|------|-------|-------------------|
| Fillo Navyandra | `siswa@youthswimming.com` | `siswa123` |
| Ghaisan Ghaits | `ghaisan@youthswimming.com` | `siswa123` |
| Heri Budiman | `heri@youthswimming.com` | `siswa123` |
| Iwan Setiawan | `iwan@youthswimming.com` | `siswa123` |

### **Akun Coach:**
| Nama | Email | Password Saat Ini |
|------|-------|-------------------|
| Budi Santoso | `budi@youthswimming.com` | `coach123` |
| Sari Dewi | `sari@youthswimming.com` | `coach123` |
| Ahmad Fauzi | `ahmad@youthswimming.com` | `coach123` |

---

## 🔧 Fitur Teknis

### **Database:**
- Kolom `current_password` untuk menyimpan password plain text (display admin)
- Kolom `password` tetap ter-hash untuk keamanan login
- Auto-update kedua kolom saat ganti password

### **Security:**
- Password hash tetap aman di database
- Plain text password hanya untuk kemudahan admin
- Tidak ada password yang ter-expose di log atau URL

### **UI/UX:**
- Toggle show/hide password dengan animasi
- Copy to clipboard dengan feedback visual
- Form ganti password dengan preview real-time
- Responsive design untuk mobile dan desktop

---

## 🚀 Demo Workflow

### **Scenario: Admin ingin ganti password siswa**

1. **Login Admin**
   - Email: `admin@youthswimming.com`
   - Password: `admin123`

2. **Buka Kelola Akun**
   - Menu: Pengaturan → Kelola Akun
   - Lihat daftar semua akun siswa/coach

3. **Lihat Password Saat Ini**
   - Klik tombol mata (👁️) di kolom Password
   - Password tampil: `siswa123`

4. **Ganti Password**
   - Klik tombol kunci (🔑) di kolom Aksi
   - Pilih opsi: "Password Tanggal Lahir"
   - Preview: `15081995` (contoh)
   - Klik "Simpan Password Baru"

5. **Test Login Siswa**
   - Logout dari admin
   - Login sebagai siswa dengan password baru: `15081995`
   - Berhasil masuk ke dashboard siswa

---

## 📝 Catatan Penting

### **Best Practices:**
- Gunakan **Password Tanggal Lahir** untuk siswa (mudah diingat)
- Gunakan **Password Default** untuk reset cepat
- Gunakan **Password Custom** untuk kebutuhan khusus

### **Keamanan:**
- Password plain text hanya visible untuk admin
- Siswa/coach tidak bisa lihat password orang lain
- Password tetap ter-hash di database untuk keamanan

### **Maintenance:**
- Admin bisa track password yang aktif
- Mudah reset jika siswa/coach lupa password
- Tidak perlu komunikasi manual untuk sharing password

---

**Status: ✅ SIAP DIGUNAKAN!**

Fitur password management sudah fully functional dan terintegrasi dengan sistem login yang ada.