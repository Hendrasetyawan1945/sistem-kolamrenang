# Solusi: Sistem Login untuk Coach

## Masalah
Admin dapat membuat data coach baru, tetapi coach tidak dapat login ke sistem karena tidak ada pembuatan akun User yang terkait.

## Analisis Situasi
- ✅ Tabel `coaches` ada dan berfungsi
- ✅ Form pembuatan coach ada
- ❌ Tidak ada pembuatan akun User untuk login
- ❌ Tidak ada input password di form
- ❌ Coach tidak bisa mengakses sistem

## Rekomendasi Solusi

### **Opsi 1: Auto-Generate Password (RECOMMENDED)**

#### **Alur Kerja:**
1. Admin mengisi form coach (nama, email, dll)
2. Sistem otomatis generate password random
3. Sistem buat akun User dengan role 'coach'
4. Sistem kirim email dengan kredensial login
5. Coach login dengan email + password yang dikirim
6. Coach bisa ganti password setelah login pertama

#### **Keuntungan:**
- ✅ Proses cepat untuk admin
- ✅ Keamanan terjaga (password random)
- ✅ Otomatis terkirim ke coach
- ✅ Coach bisa ganti password sendiri

#### **Implementasi:**
```php
// Di Controller saat create coach
$password = Str::random(8); // Generate password random
$user = User::create([
    'name' => $request->nama,
    'email' => $request->email,
    'password' => Hash::make($password),
    'role' => 'coach'
]);

// Link coach dengan user
$coach = Coach::create($request->all());
$coach->user_id = $user->id;
$coach->save();

// Kirim email dengan kredensial
Mail::to($request->email)->send(new CoachCredentialsMail($password));
```

### **Opsi 2: Input Password Manual**

#### **Alur Kerja:**
1. Admin mengisi form coach + password
2. Sistem buat akun User dengan password yang diinput
3. Admin memberitahu coach secara manual
4. Coach login dengan kredensial tersebut

#### **Keuntungan:**
- ✅ Admin kontrol penuh password
- ✅ Bisa koordinasi langsung dengan coach

#### **Kekurangan:**
- ❌ Admin harus ingat/catat password
- ❌ Proses manual lebih ribet

### **Opsi 3: Coach Self-Registration**

#### **Alur Kerja:**
1. Admin buat data coach tanpa akun
2. Sistem generate token registrasi
3. Admin kirim link registrasi ke coach
4. Coach klik link, isi password sendiri
5. Akun otomatis terhubung dengan data coach

#### **Keuntungan:**
- ✅ Coach kontrol password sendiri
- ✅ Keamanan tinggi
- ✅ Proses terstruktur

## Implementasi Opsi 1 (Recommended)

### 1. **Update Migration User**
```php
// Tambah kolom coach_id di tabel users
Schema::table('users', function (Blueprint $table) {
    $table->foreignId('coach_id')->nullable()->constrained('coaches')->onDelete('cascade');
});
```

### 2. **Update Model User**
```php
// app/Models/User.php
public function coach()
{
    return $this->belongsTo(Coach::class);
}
```

### 3. **Update Model Coach**
```php
// app/Models/Coach.php
public function user()
{
    return $this->hasOne(User::class);
}
```

### 4. **Update Controller**
```php
// app/Http/Controllers/Admin/PengaturanController.php
public function storeCoach(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'status' => 'required|in:aktif,cuti,nonaktif',
    ]);

    // Generate password random
    $password = Str::random(8);
    
    // Buat akun User
    $user = User::create([
        'name' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($password),
        'role' => 'coach'
    ]);

    // Buat data Coach
    $coach = Coach::create($request->all());
    
    // Link coach dengan user
    $user->coach_id = $coach->id;
    $user->save();

    // Kirim email kredensial (optional)
    try {
        Mail::to($request->email)->send(new CoachCredentialsMail($coach->nama, $request->email, $password));
        $message = 'Coach berhasil ditambahkan dan kredensial login telah dikirim ke email.';
    } catch (\Exception $e) {
        $message = "Coach berhasil ditambahkan. Email: {$request->email}, Password: {$password}";
    }

    return back()->with('success', $message);
}
```

### 5. **Update Form Coach**
```blade
<!-- Tambah field email sebagai required -->
<div>
    <label>Email <span style="color:red">*</span></label>
    <input type="email" name="email" required placeholder="coach@email.com">
    <small>Email ini akan digunakan untuk login ke sistem</small>
</div>
```

### 6. **Buat Email Template**
```php
// app/Mail/CoachCredentialsMail.php
class CoachCredentialsMail extends Mailable
{
    public function __construct(
        public string $nama,
        public string $email, 
        public string $password
    ) {}

    public function build()
    {
        return $this->subject('Akun Coach - Sistem Manajemen')
                   ->view('emails.coach-credentials');
    }
}
```

### 7. **Template Email**
```blade
<!-- resources/views/emails/coach-credentials.blade.php -->
<h2>Selamat Datang, {{ $nama }}!</h2>
<p>Akun coach Anda telah dibuat. Berikut kredensial login:</p>
<ul>
    <li><strong>Email:</strong> {{ $email }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
</ul>
<p>Silakan login dan ganti password Anda setelah login pertama.</p>
<a href="{{ url('/login') }}">Login Sekarang</a>
```

## Fitur Tambahan

### **1. Reset Password untuk Coach**
```php
// Route untuk admin reset password coach
Route::post('/admin/coach/{coach}/reset-password', [PengaturanController::class, 'resetCoachPassword']);

// Method di controller
public function resetCoachPassword(Coach $coach)
{
    $newPassword = Str::random(8);
    $coach->user->update(['password' => Hash::make($newPassword)]);
    
    // Kirim email password baru
    Mail::to($coach->email)->send(new PasswordResetMail($newPassword));
    
    return back()->with('success', "Password coach berhasil direset. Password baru: {$newPassword}");
}
```

### **2. Status Akun Coach**
```php
// Tambah kolom is_active di tabel users
// Sinkronisasi dengan status coach
public function updateCoach(Request $request, Coach $coach)
{
    $coach->update($request->all());
    
    // Update status user sesuai status coach
    if ($coach->user) {
        $coach->user->update([
            'is_active' => $request->status === 'aktif'
        ]);
    }
    
    return back()->with('success', 'Data coach berhasil diperbarui.');
}
```

### **3. Middleware untuk Coach Aktif**
```php
// app/Http/Middleware/CoachActiveMiddleware.php
public function handle($request, Closure $next)
{
    if (auth()->user()->role === 'coach') {
        $coach = auth()->user()->coach;
        if (!$coach || $coach->status !== 'aktif') {
            auth()->logout();
            return redirect('/login')->with('error', 'Akun coach tidak aktif.');
        }
    }
    
    return $next($request);
}
```

## Testing Checklist

- [ ] Admin buat coach baru dengan email
- [ ] Sistem generate password otomatis
- [ ] Akun User terbuat dengan role 'coach'
- [ ] Email kredensial terkirim (jika ada mail server)
- [ ] Coach bisa login dengan kredensial
- [ ] Coach bisa ganti password
- [ ] Admin bisa reset password coach
- [ ] Status coach sinkron dengan akun User

## Kesimpulan

**Opsi 1 (Auto-Generate Password)** adalah solusi terbaik karena:
- ✅ Proses otomatis dan cepat
- ✅ Keamanan terjaga
- ✅ User experience yang baik
- ✅ Mudah di-maintain

Implementasi ini akan menyelesaikan masalah coach yang tidak bisa login sambil menjaga keamanan dan kemudahan penggunaan sistem.