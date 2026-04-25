# Testing Guide - Sistem Approval Pembayaran

## 🎯 Tujuan Testing
Memverifikasi bahwa sistem approval pembayaran berfungsi dengan baik dari input coach hingga approval admin.

---

## 📋 Pre-requisites

### 1. Data yang Diperlukan
```bash
# Pastikan ada data berikut di database:
- ✅ User dengan role 'admin'
- ✅ User dengan role 'coach' (linked ke Coach)
- ✅ Coach yang memiliki kelas
- ✅ Siswa yang terdaftar di kelas tersebut
```

### 2. Akses Login
```
Admin:
- Email: admin@example.com
- Password: (sesuai database)

Coach:
- Email: (email coach dari database)
- Password: (password yang di-generate)
```

---

## 🧪 Test Scenarios

### **SCENARIO 1: Coach Input Pembayaran Baru**

#### Steps:
1. **Login sebagai Coach**
   ```
   URL: /login
   Email: [coach email]
   Password: [coach password]
   ```

2. **Navigasi ke Dashboard Pembayaran**
   ```
   URL: /coach/pembayaran
   Expected: Melihat tabel pembayaran dengan nama siswa dan bulan
   ```

3. **Verifikasi Data Siswa**
   ```
   Expected:
   - Hanya siswa di kelas coach yang ditampilkan
   - Nama siswa dan kelas terlihat jelas
   - 12 kolom bulan (Jan-Des) tersedia
   ```

4. **Input Pembayaran Baru**
   ```
   Action: Klik tombol "+ Input" pada cell siswa & bulan tertentu
   
   Expected: Modal muncul dengan form:
   - Nama Siswa (read-only)
   - Bulan (read-only)
   - Jumlah Bayar (input)
   - Tanggal Bayar (date picker)
   - Metode Pembayaran (dropdown)
   - Keterangan (textarea)
   ```

5. **Submit Pembayaran**
   ```
   Input:
   - Jumlah: 500000
   - Tanggal: [hari ini]
   - Metode: Transfer
   - Keterangan: "Pembayaran iuran bulan ini"
   
   Action: Klik "Submit"
   
   Expected:
   - Success message muncul
   - Cell berubah warna orange dengan text "⏳ Pending"
   - Counter "Menunggu Approval" bertambah
   ```

#### ✅ Success Criteria:
- [ ] Modal form muncul dengan benar
- [ ] Data siswa dan bulan terisi otomatis
- [ ] Form validation berjalan (required fields)
- [ ] Pembayaran tersimpan dengan status "pending"
- [ ] UI update dengan status pending

---

### **SCENARIO 2: Admin Approve Pembayaran**

#### Steps:
1. **Login sebagai Admin**
   ```
   URL: /login
   Email: admin@example.com
   Password: [admin password]
   ```

2. **Navigasi ke Approval Dashboard**
   ```
   URL: /admin/approval/pembayaran
   Expected: Melihat list pembayaran pending
   ```

3. **Verifikasi Stats**
   ```
   Expected:
   - Counter "Pending" menunjukkan jumlah yang benar
   - Counter "Approved" dan "Rejected" terlihat
   - Filter status default ke "Pending"
   ```

4. **Review Detail Pembayaran**
   ```
   Action: Klik tombol "👁️" (eye icon) pada pembayaran
   
   Expected:
   - Redirect ke halaman detail
   - Semua informasi pembayaran terlihat:
     * Nama siswa & kelas
     * Periode (bulan & tahun)
     * Jumlah bayar
     * Tanggal bayar
     * Metode pembayaran
     * Keterangan
     * Input by (nama coach)
     * Timeline status
   ```

5. **Approve Pembayaran**
   ```
   Action: Klik tombol "✓ Approve Pembayaran"
   
   Expected:
   - Konfirmasi dialog muncul
   - Setelah confirm, success message muncul
   - Status berubah menjadi "Approved"
   - approved_by dan approved_at terisi
   ```

6. **Verifikasi di Coach Dashboard**
   ```
   Action: Login kembali sebagai coach
   URL: /coach/pembayaran
   
   Expected:
   - Cell pembayaran berubah warna hijau
   - Text berubah menjadi "✓ Lunas"
   - Tidak bisa diedit lagi
   ```

#### ✅ Success Criteria:
- [ ] Admin bisa melihat semua pembayaran pending
- [ ] Detail pembayaran lengkap dan akurat
- [ ] Approve berhasil mengubah status
- [ ] Coach melihat status approved di dashboard
- [ ] Pembayaran approved tidak bisa diedit

---

### **SCENARIO 3: Admin Reject Pembayaran**

#### Steps:
1. **Coach Input Pembayaran Baru**
   ```
   (Ulangi Scenario 1 untuk membuat pembayaran baru)
   ```

2. **Admin Review & Reject**
   ```
   URL: /admin/approval/pembayaran
   Action: Klik tombol "✗" (reject) pada pembayaran
   
   Expected: Modal reject muncul dengan textarea untuk alasan
   ```

3. **Submit Rejection**
   ```
   Input: "Jumlah pembayaran tidak sesuai. Harusnya Rp 600.000"
   Action: Klik "Reject"
   
   Expected:
   - Success message muncul
   - Status berubah menjadi "Rejected"
   - Alasan penolakan tersimpan
   ```

4. **Coach Melihat Rejection**
   ```
   Action: Login sebagai coach
   URL: /coach/pembayaran
   
   Expected:
   - Cell berubah warna merah
   - Text "✗ Rejected"
   - Klik cell untuk lihat detail
   ```

5. **Coach Lihat Detail Rejection**
   ```
   Action: Klik cell rejected atau klik "View Detail"
   
   Expected:
   - Status badge "Ditolak" terlihat
   - Alasan penolakan ditampilkan dalam box kuning
   - Tombol "Edit Pembayaran" tersedia
   ```

#### ✅ Success Criteria:
- [ ] Modal reject muncul dengan form alasan
- [ ] Rejection reason wajib diisi (validation)
- [ ] Status berubah menjadi rejected
- [ ] Coach melihat alasan penolakan
- [ ] Tombol edit tersedia untuk pembayaran rejected

---

### **SCENARIO 4: Coach Edit & Resubmit Pembayaran Rejected**

#### Steps:
1. **Coach Akses Edit Form**
   ```
   URL: /coach/pembayaran/{id}/edit
   (atau klik tombol "Edit Pembayaran" dari detail)
   
   Expected:
   - Form edit muncul
   - Data pembayaran sebelumnya terisi
   - Warning box menampilkan alasan rejection
   - Info: "Status akan kembali pending setelah update"
   ```

2. **Update Data Pembayaran**
   ```
   Action: Ubah jumlah dari 500000 menjadi 600000
   
   Expected:
   - Form validation berjalan
   - Semua field bisa diedit kecuali siswa & periode
   ```

3. **Submit Update**
   ```
   Action: Klik "Update & Submit untuk Approval"
   
   Expected:
   - Success message muncul
   - Redirect ke dashboard pembayaran
   - Status kembali menjadi "Pending"
   - rejection_reason di-clear
   ```

4. **Admin Review Ulang**
   ```
   Action: Login sebagai admin
   URL: /admin/approval/pembayaran
   
   Expected:
   - Pembayaran muncul kembali di list pending
   - Jumlah sudah berubah menjadi 600000
   - Bisa di-approve lagi
   ```

5. **Admin Approve**
   ```
   Action: Approve pembayaran yang sudah diperbaiki
   
   Expected:
   - Status menjadi "Approved"
   - Workflow selesai
   ```

#### ✅ Success Criteria:
- [ ] Form edit menampilkan data sebelumnya
- [ ] Alasan rejection terlihat jelas
- [ ] Update berhasil dan status kembali pending
- [ ] Admin bisa review dan approve ulang
- [ ] Workflow edit-resubmit berjalan lancar

---

### **SCENARIO 5: Bulk Approve**

#### Steps:
1. **Coach Input Multiple Pembayaran**
   ```
   Action: Input 3-5 pembayaran untuk siswa berbeda
   Expected: Semua pembayaran berstatus pending
   ```

2. **Admin Bulk Approve**
   ```
   URL: /admin/approval/pembayaran
   
   Action:
   1. Centang checkbox "Pilih Semua" atau pilih beberapa
   2. Klik tombol "Bulk Approve"
   3. Confirm dialog
   
   Expected:
   - Success message: "X pembayaran berhasil diapprove"
   - Semua pembayaran terpilih berubah status menjadi approved
   - Counter stats update
   ```

3. **Verifikasi**
   ```
   Action: Filter status ke "Approved"
   
   Expected:
   - Semua pembayaran yang di-bulk approve muncul
   - Status dan approved_at terisi
   ```

#### ✅ Success Criteria:
- [ ] Checkbox select all berfungsi
- [ ] Bulk approve berhasil untuk multiple items
- [ ] Stats counter update dengan benar
- [ ] Semua pembayaran terpilih ter-approve

---

### **SCENARIO 6: Filter & Search**

#### Steps:
1. **Filter by Status**
   ```
   URL: /admin/approval/pembayaran
   
   Action: Ubah dropdown status ke:
   - Pending
   - Approved
   - Rejected
   - All
   
   Expected: List pembayaran berubah sesuai filter
   ```

2. **Filter by Coach**
   ```
   Action: Pilih coach tertentu dari dropdown
   
   Expected: Hanya pembayaran dari coach tersebut yang muncul
   ```

3. **Search by Siswa Name**
   ```
   Action: Ketik nama siswa di search box
   
   Expected: List filter berdasarkan nama siswa
   ```

4. **Combined Filters**
   ```
   Action: Gunakan status + coach + search bersamaan
   
   Expected: Filter bekerja secara kombinasi
   ```

#### ✅ Success Criteria:
- [ ] Filter status berfungsi
- [ ] Filter coach berfungsi
- [ ] Search siswa berfungsi
- [ ] Combined filter berfungsi
- [ ] URL query parameters ter-update

---

### **SCENARIO 7: Authorization & Security**

#### Steps:
1. **Coach Access Restriction**
   ```
   Test: Coach coba akses pembayaran siswa bukan di kelasnya
   
   Expected: 403 Forbidden atau tidak muncul di list
   ```

2. **Edit Approved Payment**
   ```
   Test: Coach coba edit pembayaran yang sudah approved
   
   Expected: Error message atau tombol edit tidak tersedia
   ```

3. **Admin Access**
   ```
   Test: Admin coba approve pembayaran non-pending
   
   Expected: Error message "Hanya pembayaran pending yang bisa diapprove"
   ```

4. **Direct URL Access**
   ```
   Test: Coach coba akses /admin/approval/pembayaran
   
   Expected: 403 Forbidden atau redirect
   ```

#### ✅ Success Criteria:
- [ ] Coach hanya akses siswa di kelasnya
- [ ] Approved payment tidak bisa diedit
- [ ] Admin tidak bisa approve non-pending
- [ ] Role-based access berfungsi

---

## 🐛 Common Issues & Solutions

### Issue 1: View Not Found Error
```
Error: View [coach.pembayaran.edit] not found
Solution: File sudah dibuat di resources/views/coach/pembayaran/edit.blade.php
```

### Issue 2: Carbon Parsing Error
```
Error: Could not parse '4-01'
Solution: Sudah diperbaiki dengan parseBulanInput() helper
```

### Issue 3: Unauthorized Access
```
Error: 403 Forbidden
Solution: Pastikan user memiliki role yang benar (coach/admin)
```

### Issue 4: Migration Not Run
```
Error: Column 'status' not found
Solution: Run: php artisan migrate
```

---

## 📊 Test Results Template

```
┌─────────────────────────────────────────────────────────────┐
│                    TEST RESULTS                             │
└─────────────────────────────────────────────────────────────┘

Date: _______________
Tester: _______________

Scenario 1: Coach Input Pembayaran
  [ ] Login berhasil
  [ ] Dashboard tampil
  [ ] Modal form berfungsi
  [ ] Submit berhasil
  [ ] Status pending terlihat

Scenario 2: Admin Approve
  [ ] Login berhasil
  [ ] List pembayaran tampil
  [ ] Detail lengkap
  [ ] Approve berhasil
  [ ] Coach lihat status approved

Scenario 3: Admin Reject
  [ ] Reject form muncul
  [ ] Alasan tersimpan
  [ ] Coach lihat rejection
  [ ] Alasan terlihat

Scenario 4: Edit & Resubmit
  [ ] Form edit tampil
  [ ] Update berhasil
  [ ] Status kembali pending
  [ ] Admin bisa approve ulang

Scenario 5: Bulk Approve
  [ ] Checkbox berfungsi
  [ ] Bulk approve berhasil
  [ ] Stats update

Scenario 6: Filter & Search
  [ ] Filter status OK
  [ ] Filter coach OK
  [ ] Search siswa OK
  [ ] Combined filter OK

Scenario 7: Authorization
  [ ] Coach restriction OK
  [ ] Edit approved blocked
  [ ] Admin restriction OK
  [ ] Role-based access OK

Overall Status: [ ] PASS  [ ] FAIL

Notes:
_____________________________________________________________
_____________________________________________________________
_____________________________________________________________
```

---

## ✅ Final Checklist

Sebelum deploy ke production:

- [ ] Semua test scenarios PASS
- [ ] No console errors di browser
- [ ] No PHP errors di log
- [ ] Database migration berjalan
- [ ] Authorization berfungsi dengan benar
- [ ] UI responsive di mobile
- [ ] Success/error messages jelas
- [ ] Data validation berfungsi
- [ ] Timeline status akurat
- [ ] Stats counter real-time

---

## 🚀 Ready for Production!

Jika semua test PASS, sistem siap digunakan! 🎉
