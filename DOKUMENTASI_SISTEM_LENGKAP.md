# DOKUMENTASI SISTEM YOUTH SWIMMING CLUB
## Sistem Administrasi Klub Renang - Dokumentasi Lengkap

**Versi:** 1.0  
**Tanggal:** April 2026  
**Platform:** Laravel 11 + SQLite  
**URL Demo:** http://127.0.0.1:8001

---

## DAFTAR ISI

1. [Gambaran Umum Sistem](#1-gambaran-umum-sistem)
2. [Arsitektur Sistem](#2-arsitektur-sistem)
3. [Struktur Database (ERD)](#3-struktur-database-erd)
4. [Modul dan Fitur](#4-modul-dan-fitur)
5. [Alur Kerja Sistem](#5-alur-kerja-sistem)
6. [Panduan Penggunaan per Role](#6-panduan-penggunaan-per-role)
7. [Struktur File Proyek](#7-struktur-file-proyek)
8. [Instalasi dan Konfigurasi](#8-instalasi-dan-konfigurasi)
9. [Akun Demo](#9-akun-demo)

---

## 1. GAMBARAN UMUM SISTEM

Youth Swimming Club Administration System adalah aplikasi web berbasis Laravel untuk mengelola operasional klub renang secara menyeluruh. Sistem ini mencakup manajemen siswa, keuangan, prestasi, absensi, jersey, dan laporan.

### Tujuan Sistem
- Digitalisasi administrasi klub renang
- Monitoring keuangan secara real-time
- Tracking prestasi dan perkembangan siswa
- Manajemen absensi dan jadwal latihan
- Pelaporan komprehensif untuk pengurus klub

### Pengguna Sistem
| Role | Deskripsi | Akses |
|------|-----------|-------|
| **Admin** | Pengurus/Staf klub | Akses penuh ke semua fitur |
| **Coach** | Pelatih renang | Portal khusus: absensi, rapor, catatan waktu |
| **Siswa** | Anggota/Atlet | Portal pribadi: iuran, rapor, prestasi, kehadiran |

---

## 2. ARSITEKTUR SISTEM

### Stack Teknologi
- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** SQLite (dev) / MySQL (prod)
- **Frontend:** Blade Templates + Custom CSS
- **Icons:** Font Awesome 6
- **Authentication:** Laravel Built-in Auth + Custom Role Middleware

### Pola Arsitektur
```
Browser
  └── Routes (web.php)
        └── Middleware (auth, role:admin/coach/siswa)
              └── Controller
                    ├── Model (Eloquent ORM)
                    │     └── Database (SQLite/MySQL)
                    └── View (Blade Template)
```

### Role-Based Access Control
```
/login  ──► LoginController ──► Redirect berdasarkan role
                                  ├── admin  → /admin/dashboard
                                  ├── coach  → /coach/dashboard
                                  └── siswa  → /siswa/dashboard
```

---

## 3. STRUKTUR DATABASE (ERD)

### Tabel Utama

#### users
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| name | string | Nama pengguna |
| email | string unique | Email login |
| password | hashed | Password terenkripsi |
| role | enum | admin / coach / siswa |
| siswa_id | FK nullable | Relasi ke tabel siswas |
| coach_id | FK nullable | Relasi ke tabel coaches |

#### siswas (Siswa/Anggota)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| nama | string | Nama lengkap siswa |
| tanggal_lahir | date | Tanggal lahir |
| jenis_kelamin | enum | L / P |
| kelas | string | Nama kelas (FK ke kelas.nama_kelas) |
| alamat | text | Alamat lengkap |
| nama_ortu | string | Nama orang tua/wali |
| telepon | string | Nomor telepon |
| email | string | Email siswa/ortu |
| paket | string | Paket latihan |
| catatan | text | Catatan khusus |
| status | enum | aktif / cuti / nonaktif / calon |

#### coaches (Pelatih)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| nama | string | Nama pelatih |
| email | string | Email pelatih |
| telepon | string | Nomor telepon |
| spesialisasi | string | Bidang keahlian |
| status | enum | aktif / nonaktif |

#### kelas (Kelas Latihan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| nama_kelas | string | Nama kelas |
| coach_id | FK | Pelatih yang mengajar |
| jadwal | string | Jadwal latihan |
| kapasitas | integer | Maks jumlah siswa |

#### pembayarans (Iuran/Pembayaran)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| siswa_id | FK | Siswa yang membayar |
| jenis | enum | iuran_rutin / insidentil / dll |
| jumlah | decimal | Nominal pembayaran |
| bulan | integer | Bulan pembayaran |
| tahun | integer | Tahun pembayaran |
| status | enum | pending / approved / rejected |
| bukti_bayar | string | Path file bukti |
| metode | string | Metode pembayaran |
| catatan | text | Catatan tambahan |

#### absensis (Absensi)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| siswa_id | FK | Siswa |
| kelas_id | FK | Kelas latihan |
| tanggal | date | Tanggal absensi |
| status | enum | hadir / izin / sakit / alpha |
| catatan | text | Keterangan |

#### rapors (Rapor Siswa)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| siswa_id | FK | Siswa |
| coach_id | FK | Pelatih pembuat |
| template_rapor_id | FK | Template yang digunakan |
| periode | string | Periode rapor |
| nilai | JSON | Nilai per komponen |
| catatan | text | Catatan pelatih |

#### catatan_waktu (Catatan Waktu Lomba)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| siswa_id | FK | Siswa |
| nomor | string | Nomor gaya renang |
| waktu | string | Waktu tempuh |
| tanggal | date | Tanggal pencatatan |
| event | string | Nama event/lomba |

#### jersey_orders (Pemesanan Jersey)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| siswa_id | FK | Siswa pemesan |
| ukuran | string | Ukuran jersey |
| jumlah | integer | Jumlah pesanan |
| harga | decimal | Harga per item |
| status | enum | pending / proses / selesai |
| status_bayar | enum | belum / lunas |

#### kejuaraans (Kejuaraan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| nama | string | Nama kejuaraan |
| tanggal | date | Tanggal pelaksanaan |
| lokasi | string | Tempat kejuaraan |
| biaya | decimal | Biaya pendaftaran |
| status | enum | aktif / selesai |

#### angsurans (Angsuran)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | PK | Primary key |
| siswa_id | FK | Siswa |
| total | decimal | Total angsuran |
| keterangan | text | Keterangan |
| status | enum | aktif / lunas |

### Relasi Antar Tabel
```
users (1) ──── (1) siswas
users (1) ──── (1) coaches

siswas (1) ──── (N) pembayarans
siswas (1) ──── (N) absensis
siswas (1) ──── (N) rapors
siswas (1) ──── (N) catatan_waktu
siswas (1) ──── (N) jersey_orders
siswas (1) ──── (N) angsurans
siswas (1) ──── (N) kejuaraan_pembayarans

coaches (1) ──── (N) kelas
coaches (1) ──── (N) rapors

kelas (1) ──── (N) siswas
kelas (1) ──── (N) absensis

kejuaraans (1) ──── (N) kejuaraan_pembayarans
```

---

## 4. MODUL DAN FITUR

### 4.1 Modul Admin

#### Dashboard
- Statistik ringkas: total siswa aktif, pemasukan bulan ini, siswa belum bayar, jadwal hari ini
- Quick actions: cari siswa, lihat yang belum bayar, daftar baru, kas
- Timeline aktivitas terbaru

#### Manajemen Siswa
| Fitur | Deskripsi |
|-------|-----------|
| Calon Siswa | Daftar pendaftar baru yang belum diaktivasi |
| Siswa Aktif | Daftar siswa yang sedang aktif berlatih |
| Siswa Cuti | Siswa yang sedang cuti sementara |
| Siswa Nonaktif | Siswa yang sudah tidak aktif |
| Kakak Beradik | Siswa yang memiliki saudara di klub yang sama |
| Siswa Ulang Tahun | Notifikasi ulang tahun siswa |

#### Manajemen Keuangan
| Fitur | Deskripsi |
|-------|-----------|
| Iuran Rutin | Pembayaran bulanan reguler siswa |
| Paket Kuota | Paket latihan berbasis sesi/kuota |
| Iuran Insidentil | Pembayaran tidak rutin (seragam, dll) |
| Iuran Kejuaraan | Biaya pendaftaran lomba |
| Angsuran | Cicilan pembayaran |
| Pendapatan Lain | Pemasukan di luar iuran |
| Pengeluaran | Pencatatan pengeluaran klub |

#### Approval Pembayaran
- Review bukti pembayaran yang diupload siswa/coach
- Approve / Reject dengan catatan
- Bulk approve untuk efisiensi

#### Manajemen Prestasi
| Fitur | Deskripsi |
|-------|-----------|
| Catatan Waktu | Rekam waktu tempuh di lomba resmi |
| Personal Best | Waktu terbaik per siswa per nomor |
| Catatan Waktu Latihan | Rekam waktu saat latihan |
| Progress Report | Laporan perkembangan siswa |
| Nomor Nonstandar | Nomor renang di luar standar resmi |

#### Manajemen Jersey
| Fitur | Deskripsi |
|-------|-----------|
| Jersey Map | Pemetaan distribusi jersey |
| Size Chart | Tabel ukuran jersey |
| Pemesanan | Kelola pesanan jersey siswa |
| Master Ukuran | Pengaturan ukuran yang tersedia |

#### Laporan
| Fitur | Deskripsi |
|-------|-----------|
| Rekap Transaksi | Semua transaksi keuangan |
| Rekap Pembayaran Iuran | Ringkasan pembayaran per periode |
| Rekap Jumlah Siswa | Statistik jumlah siswa per kelas/status |
| Isi Rapor | Input nilai rapor siswa |
| Template Rapor | Kelola template penilaian |

#### Pengaturan
| Fitur | Deskripsi |
|-------|-----------|
| Kelas | CRUD kelas latihan |
| Coach | CRUD data pelatih |
| Kolam | CRUD data kolam renang |
| Metode Pembayaran | Pengaturan metode bayar |
| Kelola Akun | Buat/edit akun untuk siswa & coach |
| Profil Admin | Edit profil dan password admin |

### 4.2 Modul Coach

| Fitur | Deskripsi |
|-------|-----------|
| Dashboard | Statistik kelas, jadwal hari ini, total siswa |
| Daftar Siswa | Siswa di kelas yang dipegang (read-only) |
| Absensi | Input kehadiran siswa per kelas per tanggal |
| Catatan Waktu | Input dan edit catatan waktu latihan |
| Rapor | Buat dan edit rapor siswa |
| Pembayaran | Lihat dan input pembayaran siswa |

### 4.3 Modul Siswa

| Fitur | Deskripsi |
|-------|-----------|
| Dashboard | Info pribadi, status iuran, jadwal, statistik kehadiran |
| Iuran | Riwayat dan status pembayaran |
| Rapor | Lihat rapor per periode |
| Prestasi | Personal best dan catatan waktu |
| Kehadiran | Rekap absensi dan persentase kehadiran |
| Jersey | Status dan riwayat pesanan jersey |
| Profil | Edit data pribadi dan password |

---

## 5. ALUR KERJA SISTEM

### 5.1 Alur Pendaftaran Siswa Baru

```
[Calon Siswa]
     │
     ▼
Akses /daftar ──► Isi Form Pendaftaran
     │              (nama, TTL, kelas, ortu, telepon, email)
     ▼
Status: "calon" ──► Muncul di menu "Calon Siswa"
     │
     ▼
[Admin] Review Data Calon Siswa
     │
     ├── Tolak ──► Data dihapus / diberi catatan
     │
     └── Aktivasi ──► Status berubah ke "aktif"
                        │
                        ▼
                   [Opsional] Buat Akun Login
                        │
                        ▼
                   Siswa bisa login ke portal siswa
```

### 5.2 Alur Pembayaran Iuran

```
[Admin/Coach]
     │
     ▼
Input Pembayaran Siswa
(pilih siswa, jenis iuran, bulan, jumlah, metode)
     │
     ▼
Status: "pending" ──► Menunggu verifikasi
     │
     ▼
[Admin] Approval Pembayaran
     │
     ├── Reject ──► Status: "rejected" + catatan alasan
     │
     └── Approve ──► Status: "approved"
                        │
                        ▼
                   Tercatat di rekap keuangan
                   Siswa bisa lihat di portal iuran
```

### 5.3 Alur Absensi Latihan

```
[Coach] Login ke Portal Coach
     │
     ▼
Menu Absensi ──► Pilih Kelas + Tanggal
     │
     ▼
Sistem tampilkan daftar siswa di kelas tersebut
     │
     ▼
Coach input status per siswa:
  ├── Hadir
  ├── Izin
  ├── Sakit
  └── Alpha
     │
     ▼
Simpan ──► Data tersimpan di tabel absensis
     │
     ▼
[Siswa] bisa lihat rekap kehadiran di portal siswa
[Admin] bisa lihat rekap absensi di menu Absensi
```

### 5.4 Alur Pembuatan Rapor

```
[Admin] Setup Template Rapor
(komponen penilaian, bobot nilai)
     │
     ▼
[Coach] Menu Rapor ──► Pilih Siswa + Periode
     │
     ▼
Input Nilai per Komponen
(teknik, kecepatan, kehadiran, sikap, dll)
     │
     ▼
Tambah Catatan Pelatih
     │
     ▼
Simpan Rapor
     │
     ▼
[Siswa] bisa lihat rapor di portal siswa
[Admin] bisa lihat semua rapor di menu Laporan
```

### 5.5 Alur Manajemen Kejuaraan

```
[Admin] Buat Data Kejuaraan
(nama, tanggal, lokasi, biaya)
     │
     ▼
Tambah Peserta ──► Pilih siswa yang ikut
     │
     ▼
Sistem generate tagihan per peserta
     │
     ▼
[Coach/Admin] Input Pembayaran Peserta
     │
     ▼
[Admin] Approve Pembayaran
     │
     ▼
Status Peserta: Lunas
     │
     ▼
[Admin] Input Catatan Waktu Hasil Lomba
     │
     ▼
[Siswa] bisa lihat prestasi di portal siswa
```

### 5.6 Alur Pemesanan Jersey

```
[Admin] Setup Size Chart + Master Ukuran
     │
     ▼
[Admin] Buat Pesanan Jersey untuk Siswa
(pilih siswa, ukuran, jumlah, harga)
     │
     ▼
Status: "pending"
     │
     ▼
[Admin] Update Status Pesanan:
  ├── Proses ──► Sedang diproduksi
  └── Selesai ──► Jersey sudah diterima
     │
     ▼
[Admin] Update Status Bayar:
  ├── Belum Lunas
  └── Lunas
     │
     ▼
[Siswa] bisa lihat status jersey di portal siswa
```

### 5.7 Alur Manajemen Akun Pengguna

```
[Admin] Menu Kelola Akun
     │
     ▼
Buat Akun Baru
  ├── Pilih Role: siswa / coach
  ├── Pilih data siswa/coach dari dropdown
  ├── Set email & password
  └── Simpan
     │
     ▼
Akun siap digunakan
     │
     ▼
[Admin] Bisa:
  ├── Edit akun (email, password)
  ├── Reset password
  ├── Hapus akun
  └── Bulk generate akun untuk banyak siswa sekaligus
```

---

## 6. PANDUAN PENGGUNAAN PER ROLE

### 6.1 Admin

**Login:** `/login` → email admin → redirect ke `/admin/dashboard`

**Alur Kerja Harian:**
1. Cek dashboard untuk statistik terkini
2. Review calon siswa baru (jika ada)
3. Approve pembayaran yang pending
4. Cek siswa yang belum bayar iuran bulan ini
5. Input pengeluaran jika ada

**Alur Kerja Bulanan:**
1. Rekap pembayaran iuran semua siswa
2. Generate laporan keuangan
3. Update rekap jumlah siswa
4. Review dan approve rapor dari coach

### 6.2 Coach

**Login:** `/login` → email coach → redirect ke `/coach/dashboard`

**Alur Kerja Harian:**
1. Cek dashboard untuk jadwal hari ini
2. Input absensi setelah sesi latihan
3. Input catatan waktu latihan (jika ada)

**Alur Kerja Bulanan:**
1. Buat rapor untuk semua siswa di kelas
2. Review catatan waktu dan personal best siswa
3. Input pembayaran iuran siswa (jika ditugaskan)

### 6.3 Siswa

**Login:** `/login` → email siswa → redirect ke `/siswa/dashboard`

**Yang Bisa Dilakukan:**
1. Cek status iuran bulan ini
2. Lihat riwayat pembayaran
3. Lihat rapor terbaru
4. Cek personal best dan catatan waktu
5. Lihat rekap kehadiran
6. Cek status pesanan jersey
7. Edit profil dan ganti password

---

## 7. STRUKTUR FILE PROYEK

```
youth-swimming-club/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── SiswaController.php
│   │   │   │   ├── KeuanganController.php
│   │   │   │   ├── PrestasiController.php
│   │   │   │   ├── JerseyController.php
│   │   │   │   ├── LaporanController.php
│   │   │   │   ├── PengaturanController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   ├── ApprovalController.php
│   │   │   │   └── AkunController.php
│   │   │   ├── Coach/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── SiswaController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   ├── CatatanWaktuController.php
│   │   │   │   ├── RaporController.php
│   │   │   │   └── PembayaranController.php
│   │   │   ├── Siswa/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── IuranController.php
│   │   │   │   ├── RaporController.php
│   │   │   │   ├── PrestasiController.php
│   │   │   │   ├── CatatanWaktuController.php
│   │   │   │   ├── KehadiranController.php
│   │   │   │   ├── JerseyController.php
│   │   │   │   └── ProfileController.php
│   │   │   └── Auth/
│   │   │       ├── LoginController.php
│   │   │       └── RegisterController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Siswa.php
│       ├── Coach.php
│       ├── Kelas.php
│       ├── Kolam.php
│       ├── Pembayaran.php
│       ├── Absensi.php
│       ├── Rapor.php
│       ├── TemplateRapor.php
│       ├── CatatanWaktu.php
│       ├── CatatanWaktuLatihan.php
│       ├── JerseyOrder.php
│       ├── JerseySize.php
│       ├── Kejuaraan.php
│       ├── KejuaraanPembayaran.php
│       ├── Angsuran.php
│       ├── AngsuranCicilan.php
│       ├── PendapatanLain.php
│       ├── Pengeluaran.php
│       └── PaketKuota.php
├── database/
│   ├── migrations/          # 27 file migrasi
│   └── seeders/             # 6 seeder
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── admin.blade.php
│       │   ├── coach.blade.php
│       │   └── siswa.blade.php
│       ├── admin/           # Views admin
│       ├── coach/           # Views coach
│       ├── siswa/           # Views siswa
│       └── auth/
│           └── login.blade.php
└── routes/
    └── web.php              # Semua route aplikasi
```

---

## 8. INSTALASI DAN KONFIGURASI

### Persyaratan
- PHP >= 8.2
- Composer
- SQLite (sudah tersedia di PHP)

### Langkah Instalasi

```bash
# 1. Masuk ke direktori proyek
cd youth-swimming-club

# 2. Install dependencies PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# 6. Jalankan server
php artisan serve --port=8001
```

Aplikasi berjalan di: **http://127.0.0.1:8001**

### Konfigurasi Database (MySQL untuk Production)
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=youth_swimming_club
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## 9. AKUN DEMO

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@youthswimming.club | adminutama |
| Coach | coach.ahmad@youthswimming.club | coach123 |
| Coach | coach.sarah@youthswimming.club | coach123 |
| Siswa | (dibuat via admin) | (diset admin) |

---

## RINGKASAN ALUR SISTEM (Flow Chart)

```
PENDAFTARAN
[Calon Siswa Daftar Online] → [Admin Review] → [Aktivasi] → [Siswa Aktif]
                                                    ↓
                                          [Buat Akun Login]
                                                    ↓
                                          [Siswa Bisa Login]

OPERASIONAL HARIAN
[Siswa Datang Latihan] → [Coach Input Absensi] → [Data Tersimpan]
                                                        ↓
                                          [Siswa Lihat Kehadiran]

KEUANGAN
[Admin/Coach Input Iuran] → [Status Pending] → [Admin Approve] → [Lunas]
                                                                      ↓
                                                          [Rekap Keuangan Update]

PRESTASI
[Coach Input Catatan Waktu] → [Sistem Hitung Personal Best] → [Siswa Lihat Prestasi]

RAPOR
[Admin Buat Template] → [Coach Input Nilai] → [Rapor Tersimpan] → [Siswa Lihat Rapor]

JERSEY
[Admin Buat Pesanan] → [Update Status Proses] → [Update Status Selesai] → [Siswa Lihat Status]
```

---

*Dokumentasi ini dibuat otomatis berdasarkan analisis kode sumber Youth Swimming Club Administration System.*
