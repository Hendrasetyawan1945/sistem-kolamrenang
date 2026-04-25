# 📍 Cara Approve Pembayaran - Lokasi Menu

## 🎯 Lokasi Menu Approval Pembayaran

### Menu berada di:

```
Dashboard Admin
  └─ 💰 Keuangan (Menu Dropdown)
      └─ ✅ Approval Pembayaran  ← DI SINI!
```

---

## 📋 Langkah-langkah Approve Pembayaran

### 1️⃣ Login sebagai Admin
- Pastikan Anda login dengan akun yang memiliki role **Admin**
- Bukan Coach atau Siswa

### 2️⃣ Buka Menu Keuangan
- Di sidebar kiri, cari menu **"Keuangan"** dengan icon 💰
- Klik menu **"Keuangan"** untuk membuka dropdown

### 3️⃣ Klik "Approval Pembayaran"
- Di dalam dropdown Keuangan, klik **"Approval Pembayaran"** (paling atas)
- Icon: ✅ (check-circle)

### 4️⃣ Review Pembayaran Pending
Anda akan melihat:
- **Stats Card:**
  - 🟠 Pending: Jumlah pembayaran yang menunggu approval
  - 🟢 Approved: Jumlah pembayaran yang sudah di-approve
  - 🔴 Rejected: Jumlah pembayaran yang di-reject

- **Filter:**
  - Status: Pending / Approved / Rejected / Semua
  - Coach: Filter berdasarkan coach tertentu
  - Cari Siswa: Cari berdasarkan nama siswa

- **Tabel Pembayaran:**
  - Siswa (nama & kelas)
  - Periode (bulan & tahun)
  - Jumlah (Rp)
  - Metode (Tunai/Transfer/QRIS)
  - Input By (nama coach yang input)
  - Status (Pending/Approved/Rejected)
  - Aksi (tombol approve/reject)

### 5️⃣ Approve atau Reject

#### Untuk Approve 1 Pembayaran:
1. Klik tombol **hijau** (icon ✓) di kolom Aksi
2. Akan muncul konfirmasi dialog dengan detail:
   ```
   APPROVE PEMBAYARAN?
   
   Siswa: Ahmad Fauzi
   Periode: Januari 2026
   Jumlah: Rp 500.000
   Metode: Transfer
   Input oleh: Coach Budi
   
   Setelah di-approve, pembayaran akan tercatat sebagai LUNAS.
   Lanjutkan?
   ```
3. Klik **OK** untuk approve
4. Pembayaran akan berubah status menjadi **APPROVED** ✅

#### Untuk Reject 1 Pembayaran:
1. Klik tombol **merah** (icon ✗) di kolom Aksi
2. Akan muncul modal "Reject Pembayaran"
3. Isi **Alasan Reject** (wajib diisi)
4. Klik tombol **Reject**
5. Pembayaran akan berubah status menjadi **REJECTED** ❌
6. Coach bisa melihat alasan reject dan bisa edit ulang

#### Untuk Bulk Approve (Approve Banyak Sekaligus):
1. Centang checkbox di kolom paling kiri untuk pembayaran yang ingin di-approve
2. Atau centang checkbox di header untuk pilih semua
3. Klik tombol **"Bulk Approve (X)"** di atas tabel
4. Konfirmasi
5. Semua pembayaran terpilih akan di-approve sekaligus

### 6️⃣ Selesai!
- Pembayaran yang sudah di-approve akan tercatat sebagai **LUNAS**
- Coach akan melihat status **"✓ Lunas"** di halaman mereka
- Log activity tersimpan untuk audit trail

---

## 🗺️ Struktur Menu Lengkap

```
📊 Dashboard Admin
│
├─ 👥 Data Siswa
│   ├─ Siswa Aktif
│   ├─ Siswa Non-Aktif
│   └─ Tambah Siswa
│
├─ 👨‍🏫 Data Coach
│   ├─ Daftar Coach
│   └─ Tambah Coach
│
├─ 🏊 Data Kelas
│   ├─ Daftar Kelas
│   └─ Tambah Kelas
│
├─ 💰 Keuangan ← BUKA INI
│   ├─ ✅ Approval Pembayaran ← KLIK INI UNTUK APPROVE
│   ├─ 📅 Iuran Rutin
│   ├─ 📦 Paket Kuota
│   ├─ ⚠️ Iuran Insidentil
│   ├─ 🏆 Iuran Kejuaraan
│   ├─ 💳 Angsuran
│   ├─ ➕ Pendapatan Lain
│   ├─ 👕 Pendapatan Jersey
│   ├─ ➖ Pengeluaran
│   └─ 📊 Rekap Keuangan
│
├─ 👕 Jersey
│   ├─ Daftar Jersey
│   ├─ Jersey Map
│   └─ Tambah Jersey
│
├─ 📝 Rapor
│   ├─ Daftar Rapor
│   └─ Buat Rapor
│
├─ 👤 Kelola Akun
│   ├─ Daftar Akun
│   ├─ Generate Massal
│   └─ Buat Akun Baru
│
└─ ⚙️ Pengaturan
    └─ Profil Admin
```

---

## 🎨 Screenshot Lokasi (Deskripsi)

### Sidebar Menu:
```
┌─────────────────────────┐
│ 📊 Dashboard            │
│ 👥 Data Siswa           │
│ 👨‍🏫 Data Coach          │
│ 🏊 Data Kelas           │
│ 💰 Keuangan         ▼   │ ← KLIK INI
│   ├─ ✅ Approval...     │ ← MUNCUL SUBMENU INI
│   ├─ 📅 Iuran Rutin     │
│   ├─ 📦 Paket Kuota     │
│   └─ ...                │
│ 👕 Jersey               │
│ 📝 Rapor                │
│ 👤 Kelola Akun          │
│ ⚙️ Pengaturan           │
└─────────────────────────┘
```

### Halaman Approval Pembayaran:
```
┌────────────────────────────────────────────────────┐
│ ✅ Approval Pembayaran                             │
├────────────────────────────────────────────────────┤
│                                                     │
│ [🟠 2 Pending] [🟢 45 Approved] [🔴 3 Rejected]   │
│                                                     │
│ Filter: [Status ▼] [Coach ▼] [Cari Siswa...] [🔍] │
│                                                     │
│ ☑ Pilih Semua  [✅ Bulk Approve]                   │
│                                                     │
│ ┌──────────────────────────────────────────────┐  │
│ │ Siswa │ Periode │ Jumlah │ Status │ Aksi    │  │
│ ├──────────────────────────────────────────────┤  │
│ │ Ahmad │ Jan 26  │ 500K   │ Pending│ [👁][✓][✗]│  │
│ │ Budi  │ Jan 26  │ 500K   │ Pending│ [👁][✓][✗]│  │
│ └──────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────┘
```

---

## ⚡ Quick Access

### URL Langsung:
```
http://your-domain.com/admin/approval/pembayaran
```

### Route Name:
```php
route('admin.approval.pembayaran')
```

---

## 🔐 Akses & Permission

### Siapa yang bisa akses menu ini?
- ✅ **Admin** (role: admin)
- ✅ **Super Admin**
- ❌ **Coach** (tidak bisa akses)
- ❌ **Siswa** (tidak bisa akses)

### Apa yang bisa dilakukan?
- ✅ Melihat daftar pembayaran pending
- ✅ Approve pembayaran
- ✅ Reject pembayaran dengan alasan
- ✅ Bulk approve (approve banyak sekaligus)
- ✅ Filter berdasarkan status/coach/siswa
- ✅ Lihat detail pembayaran

### Apa yang TIDAK bisa dilakukan?
- ❌ Approve pembayaran yang diinput sendiri
- ❌ Edit pembayaran yang sudah approved
- ❌ Hapus pembayaran yang sudah approved

---

## 📞 Troubleshooting

### ❓ Menu "Keuangan" tidak muncul?
**Solusi:**
- Pastikan Anda login sebagai **Admin**
- Cek role Anda di database (harus `role = 'admin'`)
- Logout dan login ulang

### ❓ Menu "Approval Pembayaran" tidak bisa diklik?
**Solusi:**
- Cek route di `routes/web.php`
- Pastikan route `admin.approval.pembayaran` sudah terdaftar
- Cek permission/middleware

### ❓ Tidak ada pembayaran yang muncul?
**Solusi:**
- Pastikan Coach sudah input pembayaran
- Cek filter status (pastikan pilih "Pending")
- Cek database tabel `pembayarans`

### ❓ Tombol Approve tidak berfungsi?
**Solusi:**
- Cek console browser (F12) untuk error JavaScript
- Pastikan CSRF token valid
- Cek controller `ApprovalController.php`

### ❓ Error "Unauthorized" saat approve?
**Solusi:**
- Pastikan role Anda adalah `admin`
- Pastikan Anda tidak approve pembayaran yang Anda input sendiri
- Cek validasi di controller

---

## 📊 Statistik & Monitoring

### Yang bisa Anda monitor di halaman ini:

1. **Pending Count** 🟠
   - Berapa pembayaran yang menunggu approval?
   - Jika terlalu banyak, segera approve

2. **Approved Count** 🟢
   - Berapa pembayaran yang sudah di-approve?
   - Indikator produktivitas

3. **Rejected Count** 🔴
   - Berapa pembayaran yang di-reject?
   - Jika tinggi, mungkin perlu training untuk Coach

4. **Input By**
   - Siapa Coach yang paling banyak input pembayaran?
   - Siapa yang perlu diingatkan?

---

## ✅ Checklist untuk Admin

Sebelum Approve, pastikan:
- [ ] Nama siswa sudah benar
- [ ] Periode (bulan & tahun) sudah benar
- [ ] Jumlah pembayaran sesuai
- [ ] Metode pembayaran jelas
- [ ] Coach yang input sudah benar
- [ ] Tidak ada duplikasi pembayaran

Setelah Approve:
- [ ] Status berubah menjadi "Approved"
- [ ] Nama Anda tercatat sebagai approver
- [ ] Log activity tersimpan
- [ ] Coach bisa melihat status "Lunas"

---

## 🎯 Tips & Best Practice

### 1. Approve Secara Berkala
- Jangan biarkan pembayaran pending terlalu lama
- Ideal: approve dalam 24 jam setelah input
- Gunakan bulk approve untuk efisiensi

### 2. Review dengan Teliti
- Cek detail pembayaran sebelum approve
- Pastikan tidak ada kesalahan input
- Jika ragu, tanya Coach yang input

### 3. Gunakan Filter
- Filter berdasarkan Coach untuk review per coach
- Filter berdasarkan status untuk fokus ke pending
- Gunakan search untuk cari siswa tertentu

### 4. Komunikasi dengan Coach
- Jika reject, berikan alasan yang jelas
- Koordinasi dengan Coach untuk pembayaran yang bermasalah
- Berikan feedback untuk improvement

---

## 📱 Akses dari Mobile

Menu ini juga bisa diakses dari mobile browser:
1. Buka browser di HP
2. Login sebagai Admin
3. Klik icon hamburger (☰) untuk buka sidebar
4. Klik "Keuangan" → "Approval Pembayaran"
5. Scroll horizontal untuk lihat semua kolom tabel

---

## 🎉 Kesimpulan

**Lokasi Menu Approval Pembayaran:**
```
Sidebar → Keuangan → Approval Pembayaran
```

**Siapa yang bisa approve:**
→ **ADMIN** saja

**Kapan pembayaran selesai:**
→ Setelah **ADMIN APPROVE**

**Apakah aman:**
→ **YA**, sudah ada validasi lengkap

---

**Dibuat oleh:** Kiro AI Assistant  
**Tanggal:** 23 April 2026  
**Status:** ✅ Siap Digunakan
