# Youth Swimming Club - Club Administration System

Sistem administrasi klub renang yang dibuat dengan Laravel, persis seperti desain swimdemo.sportkit.club dengan sidebar merah dan menu lengkap.

## 🏊‍♂️ Fitur Utama

### Dashboard Utama
- **Cari Siswa** - Pencarian siswa berdasarkan nama, kelas, atau status
- **Siapa Belum Bayar?** - Monitoring pembayaran siswa per kelas
- **Pendaftaran Siswa** - Manajemen pendaftar online dan pendaftar ulang
- **Kas** - Input pendapatan dan pengeluaran klub

### Menu Keuangan
- Iuran Rutin
- Paket Kuota
- Iuran Insidentil
- Iuran Kejuaraan
- Angsuran
- Pendapatan Lain
- Pengeluaran

### Menu Siswa
- Calon Siswa (dengan counter)
- Siswa Aktif
- Siswa Cuti
- Siswa Nonaktif
- Kakak Beradik
- Siswa Ulang Tahun

### Menu Prestasi
- Catatan Waktu
- Personal Best
- Catatan Waktu Latihan
- Progress Report
- Nomor Nonstandar

### Menu Jersey
- Jersey Map
- Size Chart
- Pemesanan
- Master Ukuran

### Menu Laporan
- Rekap Transaksi
- Rekap Pembayaran Iuran
- Rekap Jumlah Siswa
- Isi Rapor
- Template Rapor

### Menu Pengaturan
- Kelas
- Coach
- Metode Pembayaran
- User
- Form Pendaftaran
- Item Kas
- Umum

## 🎨 Desain UI

- **Sidebar Merah** dengan gradient yang elegan
- **Logo Club Administration** di header sidebar
- **Menu Dropdown** dengan animasi smooth
- **Icons Font Awesome** untuk setiap menu
- **Layout Responsive** untuk mobile dan desktop
- **Color Scheme** merah (#d32f2f) sesuai tema klub renang

## 🚀 Teknologi

- **Backend**: Laravel 11
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Database**: SQLite (development)
- **Icons**: Font Awesome 6
- **Authentication**: Laravel Auth

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- SQLite/MySQL/PostgreSQL

## 🛠️ Instalasi

1. **Clone repository**
```bash
git clone <repository-url>
cd youth-swimming-club
```

2. **Install dependencies**
```bash
composer install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Setup database**
```bash
# Untuk SQLite (default)
touch database/database.sqlite
```

5. **Jalankan migration dan seeder**
```bash
php artisan migrate:fresh --seed
```

6. **Jalankan server**
```bash
php artisan serve --port=8001
```

Aplikasi akan berjalan di `http://localhost:8001`

## 👥 Akun Demo

### Admin
- **Email**: admin@youthswimming.club
- **Password**: adminutama

## 📱 Fitur UI/UX

### Sidebar Navigation
- **Collapsible Menu** - Menu dapat dibuka/tutup dengan animasi
- **Active State** - Menu aktif ditandai dengan highlight
- **Dropdown Submenu** - Submenu dengan animasi slide
- **Icon Integration** - Setiap menu memiliki icon yang relevan

### Dashboard Cards
- **Grid Layout** - Layout 2 kolom yang responsive
- **Action Buttons** - Tombol dengan gradient dan hover effect
- **Search Forms** - Form pencarian dengan dropdown
- **Counter Badges** - Badge merah untuk notifikasi (seperti "Calon Siswa (3)")

### Data Tables
- **Responsive Design** - Tabel yang dapat di-scroll horizontal
- **Search Functionality** - Pencarian real-time
- **Empty State** - Pesan "Tidak ada data" ketika kosong

## 🔧 Struktur Aplikasi

### Controllers
```
app/Http/Controllers/Admin/
├── DashboardController.php    # Dashboard utama
├── SiswaController.php        # Manajemen siswa
├── KeuanganController.php     # Manajemen keuangan
├── PrestasiController.php     # Manajemen prestasi
├── JerseyController.php       # Manajemen jersey
├── LaporanController.php      # Laporan
└── PengaturanController.php   # Pengaturan sistem
```

### Views
```
resources/views/
├── layouts/
│   └── admin.blade.php        # Layout utama dengan sidebar
├── admin/
│   ├── dashboard.blade.php    # Dashboard utama
│   └── siswa/                 # Views untuk menu siswa
└── auth/
    └── login.blade.php        # Halaman login
```

### Routes
- Semua routes menggunakan prefix `/admin`
- Grouped routes untuk setiap modul
- Named routes untuk kemudahan navigasi

## 🎯 Roadmap

- [ ] Implementasi CRUD untuk semua modul
- [ ] Sistem notifikasi real-time
- [ ] Export laporan ke PDF/Excel
- [ ] Integration dengan payment gateway
- [ ] Mobile app companion
- [ ] Multi-language support
- [ ] Advanced reporting dashboard

## 🔒 Security Features

- Laravel Authentication
- CSRF Protection
- Input Validation
- SQL Injection Prevention
- XSS Protection

## 📞 Kontak

- **Email**: admin@youthswimming.club
- **Website**: https://youthswimming.club

---

**Youth Swimming Club Administration System** - Sistem manajemen klub renang modern dengan desain yang elegan dan fungsionalitas lengkap 🏊‍♂️