# 🔑 Tombol Edit Password di Tabel Siswa Aktif

## ✨ Fitur Baru yang Ditambahkan

Admin sekarang dapat **langsung edit password** siswa dari tabel **Siswa Aktif** tanpa perlu masuk ke menu **Kelola Akun** terlebih dahulu.

---

## 📍 Lokasi Tombol

### **Menu:** Admin → Siswa → Siswa Aktif
### **URL:** `/admin/siswa-aktif`

Di tabel **Siswa Aktif**, kolom **AKSI** sekarang memiliki tombol tambahan:

| Tombol | Icon | Warna | Fungsi |
|--------|------|-------|--------|
| Edit | ✏️ | Abu-abu | Edit data siswa |
| Akun | 👤 | Biru | Lihat detail akun |
| **Edit Password** | 🔑 | **Kuning** | **Ganti password** |
| Cuti | ⏸️ | Kuning | Ubah status cuti |
| Nonaktif | ⏹️ | Merah | Ubah status nonaktif |
| Hapus | 🗑️ | Merah | Hapus data siswa |

---

## 🎯 Cara Menggunakan

### **Step 1: Akses Tabel Siswa Aktif**
1. Login sebagai Admin: `admin@youthswimming.com` / `admin123`
2. Menu: **Siswa** → **Siswa Aktif**
3. Lihat tabel daftar siswa aktif

### **Step 2: Klik Tombol Edit Password**
1. Cari siswa yang ingin diganti passwordnya
2. Di kolom **AKSI**, klik tombol **🔑** (kuning)
3. Akan redirect ke halaman **Edit Password**

### **Step 3: Ganti Password**
1. Pilih jenis password baru:
   - **Password Default**: `123456`
   - **Password Tanggal Lahir**: Format `ddmmyyyy`
   - **Password Custom**: Input manual
2. Klik **Simpan Password Baru**
3. Password langsung ter-update

### **Step 4: Test Login**
1. Logout dari admin
2. Login dengan akun siswa menggunakan password baru
3. Harus berhasil masuk ke dashboard siswa

---

## 🔍 Kondisi Tombol

### **Tombol Muncul Jika:**
- ✅ Siswa sudah punya akun login
- ✅ Status siswa: **Ada Akun** (hijau)

### **Tombol Tidak Muncul Jika:**
- ❌ Siswa belum punya akun login
- ❌ Status siswa: **Siap Dibuat** (kuning) atau **Email Invalid** (merah)

### **Cara Buat Akun Dulu:**
1. Jika siswa belum punya akun, klik tombol **➕** (hijau) untuk buat akun
2. Setelah akun dibuat, tombol **🔑** akan muncul

---

## 🎨 Visual Design

### **Tombol Edit Password:**
- **Icon**: `fas fa-key` (🔑)
- **Warna**: Kuning (`#ffc107`)
- **Hover**: Background kuning, text hitam
- **Tooltip**: "Edit Password"
- **Ukuran**: 24x24px (sama dengan tombol lain)

### **CSS Style:**
```css
.btn-icon.btn-warning:hover {
    background: #ffc107;
    border-color: #ffc107;
    color: #212529;
}
```

---

## 📊 Workflow Lengkap

### **Scenario: Admin ingin ganti password siswa**

```
1. Admin → Siswa → Siswa Aktif
2. Cari siswa di tabel
3. Klik tombol 🔑 (Edit Password)
4. Pilih jenis password
5. Simpan password baru
6. Test login siswa
```

### **Alternatif Workflow:**
```
1. Admin → Pengaturan → Kelola Akun
2. Cari user siswa
3. Klik tombol 🔑 (Ganti Password)
4. Pilih jenis password
5. Simpan password baru
```

---

## 🔧 Technical Details

### **Route:**
```php
Route::get('/akun/{user}/edit-password', [AkunController::class, 'editPassword'])
     ->name('admin.akun.edit-password');
```

### **Controller:**
```php
public function editPassword(User $user)
{
    return view('admin.akun.edit-password', compact('user'));
}
```

### **View Integration:**
```php
@if($siswa->user)
    <a href="{{ route('admin.akun.edit-password', $siswa->user) }}" 
       class="btn-icon btn-warning" 
       title="Edit Password">
        <i class="fas fa-key"></i>
    </a>
@endif
```

---

## ✅ Testing

### **Test Cases:**

#### **1. Siswa dengan Akun:**
- **Siswa**: Fillo Navyandra (`siswa@youthswimming.com`)
- **Status**: ✅ Ada Akun
- **Expected**: Tombol 🔑 muncul
- **Action**: Klik → Redirect ke edit password

#### **2. Siswa tanpa Akun:**
- **Siswa**: Siswa baru tanpa email
- **Status**: ❌ Email Invalid
- **Expected**: Tombol 🔑 tidak muncul
- **Action**: Buat akun dulu dengan tombol ➕

#### **3. Edit Password:**
- **Input**: Password custom "newpass123"
- **Expected**: Password ter-update di database
- **Test**: Login dengan password baru berhasil

---

## 📱 Responsive Design

### **Desktop:**
- Tombol berjajar horizontal
- Tooltip muncul saat hover
- Icon jelas terlihat

### **Mobile:**
- Tombol tetap accessible
- Touch-friendly size (24px minimum)
- Tooltip diganti dengan label

---

## 🎉 Benefits

### **Untuk Admin:**
- ✅ **Akses cepat** dari tabel siswa
- ✅ **Tidak perlu pindah menu** ke Kelola Akun
- ✅ **Workflow lebih efisien**
- ✅ **Visual feedback** dengan warna kuning

### **Untuk User Experience:**
- ✅ **Konsisten** dengan tombol lain
- ✅ **Intuitive** dengan icon kunci
- ✅ **Accessible** dengan tooltip
- ✅ **Responsive** di semua device

---

**Status: ✅ IMPLEMENTED & READY TO USE!**

Tombol Edit Password sudah terintegrasi dengan sempurna di tabel Siswa Aktif dan siap digunakan oleh admin.