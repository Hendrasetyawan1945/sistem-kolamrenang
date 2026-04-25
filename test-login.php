<?php

// Test script untuk cek login semua role
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🧪 TESTING LOGIN SEMUA ROLE\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// Test accounts
$accounts = [
    ['email' => 'admin@youthswimming.com', 'password' => 'admin123', 'expected_role' => 'admin'],
    ['email' => 'budi@youthswimming.com', 'password' => 'coach123', 'expected_role' => 'coach'],
    ['email' => 'siswa@youthswimming.com', 'password' => 'siswa123', 'expected_role' => 'siswa'],
    ['email' => 'ghaisan@youthswimming.com', 'password' => 'siswa123', 'expected_role' => 'siswa'],
];

foreach ($accounts as $account) {
    echo "Testing: {$account['email']}\n";
    
    // Cek user ada di database
    $user = User::where('email', $account['email'])->first();
    if (!$user) {
        echo "❌ User tidak ditemukan di database\n\n";
        continue;
    }
    
    // Cek password
    if (Hash::check($account['password'], $user->password)) {
        echo "✅ Password: BENAR\n";
    } else {
        echo "❌ Password: SALAH\n";
    }
    
    // Cek role
    if ($user->role === $account['expected_role']) {
        echo "✅ Role: {$user->role} (sesuai)\n";
    } else {
        echo "❌ Role: {$user->role} (expected: {$account['expected_role']})\n";
    }
    
    // Cek relasi untuk siswa dan coach
    if ($user->role === 'siswa') {
        $siswa = $user->siswa;
        if ($siswa) {
            echo "✅ Relasi Siswa: {$siswa->nama} (ID: {$siswa->id})\n";
        } else {
            echo "❌ Relasi Siswa: TIDAK ADA\n";
        }
    } elseif ($user->role === 'coach') {
        $coach = $user->coach;
        if ($coach) {
            echo "✅ Relasi Coach: {$coach->nama} (ID: {$coach->id})\n";
        } else {
            echo "❌ Relasi Coach: TIDAK ADA\n";
        }
    }
    
    echo "\n";
}

echo "🎯 REDIRECT ROUTES:\n";
echo "- Admin: /admin/dashboard\n";
echo "- Coach: /coach/dashboard\n";
echo "- Siswa: /siswa/dashboard\n\n";

echo "✅ Test selesai!\n";