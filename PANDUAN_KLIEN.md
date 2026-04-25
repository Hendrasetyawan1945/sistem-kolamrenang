# 🏊‍♂️ Youth Swimming Club — Sistem Manajemen Klub Renang

> Dokumen ini berisi panduan lengkap penggunaan sistem, akun demo, fitur, dan alur kerja aplikasi.

---

## 📋 Daftar Isi

1. [Tentang Sistem](#tentang-sistem)
2. [Cara Akses & Login](#cara-akses--login)
3. [Akun Demo](#akun-demo)
4. [Fitur Per Role](#fitur-per-role)
5. [Alur Kerja Sistem](#alur-kerja-sistem)
6. [Struktur Menu](#struktur-menu)
7. [Teknologi](#teknologi)

---

## Tentang Sistem

**Youth Swimming Club Administration System** adalah aplikasi manajemen klub renang berbasis web yang dirancang untuk memudahkan pengelolaan operasional klub secara menyeluruh — mulai dari data siswa, keuangan, absensi, prestasi, hingga pemesanan jersey.

Sistem ini memiliki **3 portal terpisah** sesuai peran pengguna:

| Portal | Pengguna | Akses |
|--------|----------|-------|
| 🔴 **Admin** | Pengurus / Staf Klub | Penuh — semua fitur |
| 🟡 **Coach** | Pelatih Renang | Kelola kelas, absensi, rapor siswa |
| 🟢 **Siswa** | Anggota / Siswa | Lihat data pribadi, iuran, rapor, prestasi |

---

## Cara Akses & Login

1. Jalankan server lokal:
   ```
   php artisan serve --port=8001
   ```
2. Buka browser dan akses:
   ```
   http://127.0.0.1:8001
   ```
3. Halaman akan otomatis mengarah ke halaman **Login**
4. Masukkan email dan password sesuai role yang ingin dicoba

---

## Akun Demo

### 👑 Admin

| Field | Value |
|-------|-------|
| **Email** | `admin@youthswimming.com` |
| **Password** | `admin123` |
| **Role** | Super Admin |
| **Akses** | Full — semua fitur tersedia |

---

### 🏋️ Coach / Pelatih

| Nama | Email | Password | Spesialisasi |
|------|-------|----------|--------------|
| Budi Santoso | `budi@youthswimming.com` | `coach123` | Renang Gaya Bebas |
| Sari Dewi | `sari@youthswimming.com` | `coach123` | Renang Gaya Punggung |
| Ahmad Fauzi | `ahmad@youthswimming.com` | `coach123` | Renang Gaya Dada |

---

### 🎽 Siswa

| Nama | Email | Password | Kelas |
|------|-------|----------|-------|
| Fillo Navyandra | `siswa@youthswimming.com` | `siswa123` | KU-10 |
| Ghaisan Ghaits | `ghaisan@youthswimming.com` | `siswa123` | KU-10 |
| Heri Budiman | `heri@youthswimming.com` | `siswa123` | KU-10 |
| Iwan Setiawan | `iwan@youthswimming.com` | `siswa123` | KU-10 |

**Akses:** Data pribadi, iuran, rapor, prestasi, kehadiran, jersey

---

## Fitur Per Role

### 👑 Portal Admin (`/admin/...`)

#### Dashboard
- Statistik total siswa aktif, pemasukan bulan ini, kehadiran, dan kelas berjalan
- Quick actions untuk akses cepat
- Timeline aktivitas terbaru

#### 📁 Manajemen Siswa
| Menu | Fungsi |
|------|--------|
| Calon Siswa | Daftar pendaftar baru yang belum diaktivasi |
| Siswa Aktif | Kelola siswa yang sedang aktif berlatih |
| Siswa Cuti | Siswa yang sedang dalam status cuti |
| Siswa Nonaktif | Arsip siswa yang sudah tidak aktif |
| Kakak Beradik | Identifikasi siswa yang bersaudara |
| Siswa Ulang Tahun | Notifikasi ulang tahun siswa |

#### 💰 Manajemen Keuangan
| Menu | Fungsi |
|------|--------|
| Iuran Rutin | Catat dan pantau pembayaran iuran bulanan |
| Paket Kuota | Kelola paket latihan (8x, 12x, bulanan) |
| Iuran Insidentil | Pembayaran di luar iuran rutin |
| Iuran Kejuaraan | Biaya pendaftaran kompetisi |
| Angsuran | Cicilan pembayaran dengan tracking per cicilan |
| Pendapatan Lain | Sumber pendapatan di luar iuran |
| Pengeluaran | Catat semua pengeluaran operasional |

#### 🏆 Prestasi & Progress
| Menu | Fungsi |
|------|--------|
| Catatan Waktu | Rekam waktu renang siswa per gaya |
| Personal Best | Tampilkan rekor terbaik setiap siswa |
| Catatan Waktu Latihan | Log waktu latihan harian |
| Progress Report | Laporan perkembangan siswa |
| Nomor Nonstandar | Catatan nomor lomba di luar standar |

#### 👕 Jersey
| Menu | Fungsi |
|------|--------|
| Jersey Map | Peta distribusi jersey per siswa |
| Size Chart | Panduan ukuran jersey berdasarkan usia/tinggi/berat |
| Pemesanan | Kelola order jersey siswa |
| Master Ukuran | Atur ukuran yang tersedia (XS–XL) |

#### 📊 Laporan
| Menu | Fungsi |
|------|--------|
| Rekap Transaksi | Ringkasan semua transaksi keuangan |
| Rekap Pembayaran Iuran | Detail pembayaran per siswa per bulan |
| Rekap Jumlah Siswa | Statistik jumlah siswa per kelas/status |
| Isi Rapor | Input nilai rapor siswa |
| Template Rapor | Kelola template format rapor |

#### ⚙️ Pengaturan
| Menu | Fungsi |
|------|--------|
| Kelas | Tambah/edit kelas renang dan assign coach |
| Coach | Kelola data pelatih |
| Metode Pembayaran | Atur metode bayar yang diterima |
| Kelola Akun | Buat/reset akun login untuk siswa & coach |
| Umum | Pengaturan nama klub, logo, dll |

---

### 🏋️ Portal Coach (`/coach/...`)

| Menu | Fungsi |
|------|--------|
| Dashboard | Ringkasan kelas, jumlah siswa, jadwal hari ini |
| Daftar Siswa | Lihat siswa di kelas yang dipegang |
| Absensi | Input kehadiran siswa per sesi latihan |
| Catatan Waktu | Catat dan edit waktu renang siswa |
| Rapor | Buat dan kelola rapor perkembangan siswa |
| Pembayaran | Lihat status pembayaran siswa di kelas sendiri |

> **Catatan:** Coach hanya dapat mengakses data siswa di kelas yang menjadi tanggung jawabnya.

---

### 🎽 Portal Siswa (`/siswa/...`)

| Menu | Fungsi |
|------|--------|
| Dashboard | Info pribadi, status iuran, jadwal kelas, statistik kehadiran |
| Iuran | Riwayat dan status pembayaran iuran |
| Rapor | Lihat rapor perkembangan per periode |
| Prestasi | Personal best dan catatan waktu terbaru |
| Kehadiran | Rekap absensi bulanan dan persentase kehadiran |
| Jersey | Status dan riwayat pemesanan jersey |
| Profil | Update data diri dan ganti password |

> **Catatan:** Siswa hanya dapat melihat data milik sendiri, tidak dapat mengedit data utama.

---

## Alur Kerja Sistem

### 1. Pendaftaran Siswa Baru

```
Calon siswa mengisi form online (/daftar)
        ↓
Data masuk ke menu "Calon Siswa" di Admin
        ↓
Admin verifikasi dan aktivasi siswa
        ↓
Admin buat akun login untuk siswa (menu Kelola Akun)
        ↓
Siswa dapat login ke portal siswa
```

### 2. Alur Pembayaran Iuran

```
Admin/Coach input tagihan iuran siswa
        ↓
Siswa melakukan pembayaran (cash/transfer)
        ↓
Admin/Coach input bukti pembayaran
        ↓
Sistem approval pembayaran oleh Admin
        ↓
Status iuran siswa berubah menjadi "Lunas"
        ↓
Siswa dapat melihat status di portal siswa
```

### 3. Alur Absensi Latihan

```
Coach login ke portal coach
        ↓
Buka menu Absensi → pilih tanggal & kelas
        ↓
Tandai kehadiran setiap siswa (Hadir / Izin / Sakit / Alpha)
        ↓
Simpan data absensi
        ↓
Siswa dapat melihat rekap kehadiran di portal siswa
        ↓
Admin dapat melihat rekap absensi di menu Laporan
```

### 4. Alur Pencatatan Prestasi

```
Coach input catatan waktu renang siswa setelah latihan
        ↓
Sistem otomatis update Personal Best jika ada rekor baru
        ↓
Coach buat Progress Report / Rapor per periode
        ↓
Siswa dapat melihat perkembangan di menu Prestasi & Rapor
```

### 5. Alur Pemesanan Jersey

```
Admin input data pemesanan jersey siswa
        ↓
Pilih ukuran berdasarkan Size Chart
        ↓
Catat status pembayaran jersey
        ↓
Update status pengiriman/pengambilan
        ↓
Siswa dapat cek status pesanan di portal siswa
```

### 6. Alur Iuran Kejuaraan

```
Admin buat event kejuaraan
        ↓
Tambahkan siswa peserta kejuaraan
        ↓
Sistem generate tagihan per peserta
        ↓
Admin catat pembayaran per siswa
        ↓
Laporan peserta dan status pembayaran tersedia
```

---

## Struktur Menu (Sidebar Admin)

```
🏠 Dashboard
│
├── 🔍 Cari Siswa
├── 💸 Siapa Belum Bayar?
├── 📝 Pendaftaran Siswa
└── 🏦 Kas (Pendapatan & Pengeluaran)

💰 KEUANGAN
├── Iuran Rutin
├── Paket Kuota
├── Iuran Insidentil
├── Iuran Kejuaraan
├── Angsuran
├── Pendapatan Lain
└── Pengeluaran

👥 SISWA
├── Calon Siswa
├── Siswa Aktif
├── Siswa Cuti
├── Siswa Nonaktif
├── Kakak Beradik
└── Siswa Ulang Tahun

🏆 PRESTASI
├── Catatan Waktu
├── Personal Best
├── Catatan Waktu Latihan
├── Progress Report
└── Nomor Nonstandar

👕 JERSEY
├── Jersey Map
├── Size Chart
├── Pemesanan
└── Master Ukuran

📊 LAPORAN
├── Rekap Transaksi
├── Rekap Pembayaran Iuran
├── Rekap Jumlah Siswa
├── Isi Rapor
└── Template Rapor

⚙️ PENGATURAN
├── Kelas
├── Coach
├── Metode Pembayaran
├── Kelola Akun
└── Umum
```

---

## Teknologi

| Komponen | Detail |
|----------|--------|
| **Backend** | Laravel 11 (PHP 8.2+) |
| **Database** | SQLite (development) / MySQL (production) |
| **Frontend** | Blade Templates + Custom CSS |
| **Icons** | Font Awesome 6 |
| **Autentikasi** | Laravel Built-in Auth + Role Middleware |
| **UI Theme** | Merah (#d32f2f) — sidebar merah elegan |

---

## Kelas Renang yang Tersedia

| Kelas | Level | Kapasitas | Harga/Bulan | Coach |
|-------|-------|-----------|-------------|-------|
| Pemula A | Pemula | 15 siswa | Rp 300.000 | Budi Santoso |
| Pemula B | Pemula | 15 siswa | Rp 300.000 | Budi Santoso |
| Menengah A | Menengah | 12 siswa | Rp 400.000 | Sari Dewi |
| Menengah B | Menengah | 12 siswa | Rp 400.000 | Sari Dewi |
| Lanjut A | Lanjut | 10 siswa | Rp 500.000 | Ahmad Fauzi |
| Prestasi | Prestasi | 8 siswa | Rp 600.000 | Ahmad Fauzi |

---

## Paket Kuota Latihan

| Paket | Pertemuan | Harga |
|-------|-----------|-------|
| Paket 8x | 8 pertemuan | Rp 400.000 |
| Paket 12x | 12 pertemuan | Rp 550.000 |
| Paket Bulanan | 16 pertemuan | Rp 700.000 |

---

*Dokumen ini dibuat untuk keperluan demo dan presentasi sistem kepada klien.*
*Versi 1.0 — April 2026*
