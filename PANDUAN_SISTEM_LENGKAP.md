# 📚 Panduan Sistem Lengkap - Youth Swimming Club

## 🎯 Apa itu Youth Swimming Club Management System?

Sistem ini adalah **aplikasi web** untuk mengelola klub renang, mulai dari pendaftaran siswa, pembayaran iuran, absensi, catatan waktu, hingga laporan keuangan. Sistem ini dibuat agar pengelolaan klub renang menjadi lebih mudah, terorganisir, dan efisien.

**Bayangkan seperti ini:**
- Dulu: Semua data siswa, pembayaran, dan absensi dicatat di buku atau Excel
- Sekarang: Semua data tersimpan di sistem, bisa diakses kapan saja, dari mana saja

---

## 👥 Siapa Saja yang Menggunakan Sistem Ini?

Sistem ini memiliki 3 jenis pengguna dengan akses yang berbeda:

### 1. 👨‍💼 **ADMIN** (Pengelola Klub)
**Siapa**: Pemilik klub, manajer, atau staff administrasi

**Bisa Apa**:
- Melihat semua data (siswa, keuangan, absensi, dll)
- Mengelola data siswa (tambah, edit, hapus)
- Mencatat pembayaran iuran
- Melihat laporan keuangan
- Mengelola akun login untuk siswa dan coach
- Mengatur kelas, kolam, dan pengaturan lainnya

**Analogi**: Seperti kepala sekolah yang bisa mengakses semua data dan membuat keputusan

---

### 2. 👨‍🏫 **COACH** (Pelatih)
**Siapa**: Pelatih renang yang mengajar siswa

**Bisa Apa**:
- Melihat data siswa yang diajar
- Mencatat absensi siswa
- Mencatat waktu renang siswa (catatan waktu)
- Membuat rapor siswa
- Mencatat pembayaran (jika diberi akses)

**Analogi**: Seperti guru yang bisa melihat data muridnya dan mencatat nilai

---

### 3. 👨‍🎓 **SISWA** (Anggota Klub)
**Siapa**: Siswa yang terdaftar di klub renang

**Bisa Apa**:
- Melihat data pribadi
- Melihat riwayat pembayaran iuran
- Melihat rapor dan prestasi
- Melihat catatan waktu renang
- Melihat kehadiran/absensi
- Melihat pemesanan jersey

**Analogi**: Seperti murid yang bisa melihat nilai dan rapor sendiri

---

## 🏗️ Struktur Sistem (Bagaimana Sistem Bekerja)

### Arsitektur Sederhana

```
┌─────────────────────────────────────────────────────────┐
│                    PENGGUNA                              │
│  (Admin, Coach, Siswa mengakses via Browser)            │
└────────────────────┬────────────────────────────────────┘
                     │
                     ↓
┌─────────────────────────────────────────────────────────┐
│              WEB SERVER (Laravel)                        │
│  - Menerima request dari browser                         │
│  - Memproses data                                        │
│  - Mengirim response ke browser                          │
└────────────────────┬────────────────────────────────────┘
                     │
                     ↓
┌─────────────────────────────────────────────────────────┐
│              DATABASE (SQLite)                           │
│  - Menyimpan semua data (siswa, pembayaran, dll)        │
└─────────────────────────────────────────────────────────┘
```

**Penjelasan Sederhana:**
1. **Pengguna** membuka browser dan mengakses sistem
2. **Web Server** menerima permintaan, memproses, dan mengambil data dari database
3. **Database** menyimpan semua data secara permanen
4. **Web Server** mengirim hasil ke browser pengguna

---

## 📂 Struktur Folder Sistem

Sistem ini menggunakan **Laravel Framework** (framework PHP populer untuk membuat aplikasi web).

### Struktur Folder Utama:

```
youth-swimming-club/
│
├── app/                          # Logika aplikasi
│   ├── Http/
│   │   ├── Controllers/          # Pengendali (otak aplikasi)
│   │   │   ├── Admin/           # Controller untuk Admin
│   │   │   ├── Coach/           # Controller untuk Coach
│   │   │   ├── Siswa/           # Controller untuk Siswa
│   │   │   └── Auth/            # Controller untuk Login
│   │   └── Middleware/          # Penjaga akses (keamanan)
│   │
│   └── Models/                   # Model data (representasi tabel database)
│       ├── User.php             # Model untuk akun login
│       ├── Siswa.php            # Model untuk data siswa
│       ├── Pembayaran.php       # Model untuk pembayaran
│       ├── Absensi.php          # Model untuk absensi
│       └── ...
│
├── database/                     # Database
│   ├── migrations/              # Struktur tabel database
│   ├── seeders/                 # Data awal (dummy data)
│   └── database.sqlite          # File database (semua data tersimpan di sini)
│
├── resources/                    # Tampilan (UI)
│   └── views/                   # File tampilan HTML
│       ├── admin/               # Tampilan untuk Admin
│       ├── coach/               # Tampilan untuk Coach
│       ├── siswa/               # Tampilan untuk Siswa
│       └── auth/                # Tampilan login
│
├── routes/                       # Routing (peta jalan aplikasi)
│   └── web.php                  # Daftar semua URL dan fungsinya
│
├── public/                       # File publik (CSS, JS, gambar)
│
└── .env                          # Konfigurasi aplikasi
```

---

## 🔄 Alur Kerja Sistem (Flow)

### Contoh 1: Admin Mencatat Pembayaran Siswa

```
1. Admin login ke sistem
   ↓
2. Admin buka menu "Iuran Rutin"
   ↓
3. Admin pilih siswa yang bayar
   ↓
4. Admin isi form pembayaran (jumlah, tanggal, metode)
   ↓
5. Admin klik "Simpan"
   ↓
6. Sistem simpan data ke database
   ↓
7. Sistem tampilkan pesan "Pembayaran berhasil dicatat"
   ↓
8. Data pembayaran muncul di tabel
```

**Yang Terjadi di Belakang Layar:**
- Browser kirim data ke: `POST /admin/pembayaran`
- Laravel routing arahkan ke: `KeuanganController@storePembayaran`
- Controller validasi data, simpan ke database
- Controller kirim response sukses
- Browser tampilkan pesan sukses

---

### Contoh 2: Siswa Melihat Rapor

```
1. Siswa login ke sistem
   ↓
2. Siswa buka menu "Rapor"
   ↓
3. Sistem ambil data rapor siswa dari database
   ↓
4. Sistem tampilkan daftar rapor
   ↓
5. Siswa klik salah satu rapor
   ↓
6. Sistem tampilkan detail rapor
```

**Yang Terjadi di Belakang Layar:**
- Browser kirim request ke: `GET /siswa/rapor`
- Laravel routing arahkan ke: `Siswa\RaporController@index`
- Controller ambil data rapor dari database
- Controller kirim data ke view
- View tampilkan data dalam bentuk HTML
- Browser tampilkan halaman rapor

---

## 🗄️ Struktur Database (Tabel-Tabel)

Database adalah tempat penyimpanan semua data. Berikut tabel-tabel utama:

### 1. **users** - Tabel Akun Login
Menyimpan data akun untuk login (admin, coach, siswa)

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik akun |
| name | Nama lengkap |
| email | Email untuk login |
| password | Password (terenkripsi) |
| current_password | Password asli (untuk admin lihat) |
| role | Peran (admin/coach/siswa) |
| siswa_id | ID siswa (jika role siswa) |
| coach_id | ID coach (jika role coach) |

---

### 2. **siswas** - Tabel Data Siswa
Menyimpan data lengkap siswa

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik siswa |
| nama | Nama lengkap siswa |
| tanggal_lahir | Tanggal lahir |
| jenis_kelamin | L atau P |
| email | Email siswa |
| telepon | Nomor telepon |
| alamat | Alamat lengkap |
| nama_ortu | Nama orang tua |
| telepon_ortu | Telepon orang tua |
| kelas_id | ID kelas yang diikuti |
| status | aktif/cuti/nonaktif/calon |

---

### 3. **pembayarans** - Tabel Pembayaran
Menyimpan riwayat pembayaran siswa

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik pembayaran |
| siswa_id | ID siswa yang bayar |
| jenis_pembayaran | iuran_rutin/pendaftaran/insidentil/dll |
| bulan | Bulan pembayaran |
| tahun | Tahun pembayaran |
| jumlah | Jumlah yang harus dibayar |
| jumlah_bayar | Jumlah yang sudah dibayar |
| sisa_bayar | Sisa yang belum dibayar |
| status | lunas/belum_lunas/cicilan |
| tanggal_bayar | Tanggal pembayaran |
| metode_pembayaran | cash/transfer/dll |

---

### 4. **absensis** - Tabel Absensi
Menyimpan data kehadiran siswa

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik absensi |
| siswa_id | ID siswa |
| tanggal | Tanggal latihan |
| status | hadir/izin/sakit/alpha |
| keterangan | Catatan tambahan |

---

### 5. **catatan_waktus** - Tabel Catatan Waktu Renang
Menyimpan waktu renang siswa (prestasi)

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik catatan |
| siswa_id | ID siswa |
| nomor_lomba | Jenis gaya (freestyle/backstroke/dll) |
| jarak | Jarak (50m/100m/dll) |
| waktu | Waktu yang dicapai (detik) |
| tanggal | Tanggal pencatatan |
| jenis | lomba/latihan |
| tempat | Tempat lomba/latihan |

---

### 6. **kelas** - Tabel Kelas
Menyimpan data kelas/tingkatan

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik kelas |
| nama_kelas | Nama kelas (Pemula A, Menengah B, dll) |
| deskripsi | Deskripsi kelas |
| harga | Harga iuran per bulan |
| aktif | Status aktif/nonaktif |

---

### 7. **coaches** - Tabel Pelatih
Menyimpan data pelatih

| Kolom | Penjelasan |
|-------|------------|
| id | ID unik coach |
| nama | Nama lengkap coach |
| email | Email coach |
| telepon | Nomor telepon |
| spesialisasi | Keahlian khusus |
| pengalaman | Lama pengalaman |

---

## 🔐 Sistem Keamanan

### 1. **Autentikasi (Login)**
- Setiap pengguna harus login dengan email dan password
- Password disimpan dalam bentuk terenkripsi (hash)
- Session digunakan untuk menjaga status login

### 2. **Otorisasi (Hak Akses)**
- Setiap role memiliki akses berbeda
- Middleware menjaga agar pengguna hanya bisa akses menu sesuai role-nya
- Contoh: Siswa tidak bisa akses menu admin

### 3. **Validasi Data**
- Semua input dari pengguna divalidasi
- Mencegah data tidak valid masuk ke database
- Contoh: Email harus format email, nomor telepon harus angka

---

## 📊 Fitur-Fitur Utama Sistem

### Untuk Admin:

#### 1. **Manajemen Siswa**
- **Calon Siswa**: Daftar siswa yang baru mendaftar, belum aktif
- **Siswa Aktif**: Siswa yang sedang aktif latihan
- **Siswa Cuti**: Siswa yang sedang cuti sementara
- **Siswa Nonaktif**: Siswa yang sudah tidak aktif

**Alur Pendaftaran Siswa:**
```
Calon Siswa → Aktivasi (bayar pendaftaran) → Siswa Aktif
```

---

#### 2. **Manajemen Keuangan**
- **Iuran Rutin**: Pembayaran bulanan siswa
- **Iuran Insidentil**: Pembayaran tidak terduga (perbaikan, dll)
- **Iuran Kejuaraan**: Pembayaran untuk ikut lomba
- **Paket Kuota**: Paket latihan dengan jumlah pertemuan tertentu
- **Angsuran**: Pembayaran dicicil
- **Pendapatan Lain**: Pendapatan dari sumber lain
- **Pengeluaran**: Biaya operasional klub

**Alur Pembayaran Iuran:**
```
1. Admin pilih siswa
2. Admin isi jumlah bayar
3. Sistem hitung sisa bayar
4. Status: Lunas/Belum Lunas/Cicilan
```

---

#### 3. **Manajemen Absensi**
- Catat kehadiran siswa setiap latihan
- Lihat rekap absensi per bulan
- Filter berdasarkan kelas atau tanggal

---

#### 4. **Manajemen Prestasi**
- **Catatan Waktu**: Catat waktu renang siswa (lomba/latihan)
- **Personal Best**: Waktu terbaik setiap siswa
- **Progress Report**: Grafik perkembangan siswa
- **Rapor**: Rapor penilaian siswa

---

#### 5. **Manajemen Jersey**
- **Jersey Map**: Pemetaan nomor jersey ke siswa
- **Size Chart**: Daftar ukuran jersey
- **Pemesanan**: Kelola pemesanan jersey siswa

---

#### 6. **Laporan**
- **Rekap Keuangan**: Laporan pemasukan dan pengeluaran
- **Rekap Transaksi**: Riwayat semua transaksi
- **Rekap Pembayaran Iuran**: Laporan pembayaran per bulan
- **Rekap Jumlah Siswa**: Statistik jumlah siswa

---

#### 7. **Pengaturan**
- **Kelas**: Kelola kelas/tingkatan
- **Coach**: Kelola data pelatih
- **Kolam**: Kelola data kolam renang
- **Metode Pembayaran**: Atur metode pembayaran
- **Kelola Akun**: Kelola akun login siswa dan coach

---

### Untuk Coach:

#### 1. **Dashboard**
- Ringkasan data siswa yang diajar
- Jadwal latihan
- Statistik kehadiran

#### 2. **Data Siswa**
- Lihat daftar siswa
- Lihat detail siswa

#### 3. **Absensi**
- Catat kehadiran siswa
- Lihat riwayat absensi

#### 4. **Catatan Waktu**
- Catat waktu renang siswa
- Edit catatan waktu

#### 5. **Rapor**
- Buat rapor siswa
- Edit rapor

#### 6. **Pembayaran** (opsional)
- Catat pembayaran siswa

---

### Untuk Siswa:

#### 1. **Dashboard**
- Ringkasan data pribadi
- Status iuran bulan ini
- Jadwal kelas
- Kehadiran bulan ini

#### 2. **Iuran**
- Lihat riwayat pembayaran
- Lihat status pembayaran (lunas/belum)

#### 3. **Rapor**
- Lihat rapor penilaian

#### 4. **Prestasi**
- Lihat catatan waktu renang
- Lihat personal best

#### 5. **Kehadiran**
- Lihat riwayat kehadiran

#### 6. **Jersey**
- Lihat pemesanan jersey

#### 7. **Profile**
- Lihat dan edit data pribadi
- Ubah password

---

## 🚀 Cara Menjalankan Sistem

### Untuk Pengguna (Admin/Coach/Siswa):

1. **Buka Browser** (Chrome, Firefox, Safari, Edge)
2. **Ketik URL**: http://localhost:8001/login
3. **Login** dengan email dan password
4. **Gunakan menu** sesuai kebutuhan

---

### Untuk Developer/IT:

#### 1. **Start Server**
```bash
cd youth-swimming-club
php artisan serve --host=0.0.0.0 --port=8001
```

#### 2. **Akses Sistem**
- Lokal: http://localhost:8001
- Dari jaringan: http://[IP-SERVER]:8001

#### 3. **Stop Server**
```bash
# Cari process ID
ps aux | grep php

# Kill process
kill [PID]
```

---

## 🔧 Troubleshooting (Pemecahan Masalah)

### Masalah 1: Tidak Bisa Login
**Gejala**: Muncul pesan "Email atau password salah"

**Solusi**:
1. Pastikan email dan password benar (case-sensitive)
2. Cek di tabel siswa aktif untuk lihat password
3. Hubungi admin untuk reset password

---

### Masalah 2: Halaman Tidak Muncul
**Gejala**: Halaman blank atau error 404

**Solusi**:
1. Pastikan server sudah jalan
2. Cek URL sudah benar
3. Clear browser cache (Ctrl+Shift+Delete)
4. Coba browser lain

---

### Masalah 3: Data Tidak Tersimpan
**Gejala**: Setelah klik simpan, data tidak muncul

**Solusi**:
1. Cek koneksi internet
2. Cek apakah ada pesan error
3. Coba refresh halaman (F5)
4. Cek log error di server

---

## 📖 Istilah-Istilah Penting

| Istilah | Penjelasan |
|---------|------------|
| **Framework** | Kerangka kerja untuk membuat aplikasi (Laravel) |
| **Database** | Tempat penyimpanan data |
| **Controller** | Pengendali logika aplikasi |
| **Model** | Representasi tabel database |
| **View** | Tampilan yang dilihat pengguna |
| **Route** | Peta jalan URL ke fungsi |
| **Middleware** | Penjaga akses (keamanan) |
| **Migration** | Struktur tabel database |
| **Seeder** | Data awal untuk testing |
| **Session** | Penyimpanan sementara data login |
| **Hash** | Enkripsi password |
| **Validation** | Pengecekan data input |

---

## 📞 Kontak & Dukungan

Jika ada pertanyaan atau masalah:

**Email**: admin@youthswimming.com  
**Dokumentasi**: Baca file-file .md di folder project

---

**Dibuat**: 23 April 2026  
**Versi**: 1.0  
**Framework**: Laravel 11  
**Database**: SQLite
