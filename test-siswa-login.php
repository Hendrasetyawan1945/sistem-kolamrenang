<?php

// Test script untuk login siswa dan cek redirect
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "🧪 TESTING SISWA LOGIN REDIRECT\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Clear any existing session
Auth::logout();

// Test siswa login
$credentials = ['email' => 'siswa@youthswimming.com', 'password' => 'siswa123'];
echo "Testing login: {$credentials['email']}\n";

if (Auth::attempt($credentials)) {
    $user = Auth::user();
    echo "✅ Login berhasil!\n";
    echo "   User: {$user->name}\n";
    echo "   Role: {$user->role}\n";
    echo "   Siswa ID: {$user->siswa_id}\n";
    
    // Test redirect logic dari LoginController
    $redirectRoute = match($user->role) {
        'siswa' => 'siswa.dashboard',
        'coach' => 'coach.dashboard',
        default => 'admin.dashboard'
    };
    
    echo "   Should redirect to: {$redirectRoute}\n";
    
    // Test route URL
    try {
        $url = route($redirectRoute);
        echo "   Route URL: {$url}\n";
        
        // Test if controller exists
        $controller = app()->make('App\Http\Controllers\Siswa\DashboardController');
        echo "   ✅ Controller exists\n";
        
        // Test siswa relation
        if ($user->siswa) {
            echo "   ✅ Siswa relation: {$user->siswa->nama}\n";
            echo "   ✅ Kelas: {$user->siswa->kelas}\n";
        } else {
            echo "   ❌ Siswa relation missing\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Route error: {$e->getMessage()}\n";
    }
    
    Auth::logout();
    
} else {
    echo "❌ Login gagal!\n";
    
    // Debug password
    $user = User::where('email', $credentials['email'])->first();
    if ($user) {
        echo "   User found: {$user->name}\n";
        echo "   Role: {$user->role}\n";
        echo "   Password check: " . (Hash::check($credentials['password'], $user->password) ? 'OK' : 'FAIL') . "\n";
    } else {
        echo "   User not found\n";
    }
}

echo "\n🎯 EXPECTED BEHAVIOR:\n";
echo "1. Login dengan siswa@youthswimming.com / siswa123\n";
echo "2. Redirect ke /siswa/dashboard\n";
echo "3. Tampil dashboard siswa (bukan admin)\n";

echo "\n📝 TROUBLESHOOTING:\n";
echo "- Clear browser cache/cookies\n";
echo "- Logout dari admin dulu\n";
echo "- Gunakan incognito/private browsing\n";
echo "- Cek URL setelah login: harus /siswa/dashboard\n";

echo "\n✅ Test selesai!\n";