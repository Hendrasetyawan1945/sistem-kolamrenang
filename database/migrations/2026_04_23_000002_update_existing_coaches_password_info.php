<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Coach;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing coaches that have user accounts but no password info
        $coaches = Coach::whereHas('user')->whereNull('current_password')->get();
        
        foreach ($coaches as $coach) {
            if ($coach->user) {
                // Generate new password for existing coaches
                $newPassword = \Str::random(8);
                
                // Update user password
                $coach->user->update(['password' => \Hash::make($newPassword)]);
                
                // Update coach password info
                $coach->update([
                    'current_password' => $newPassword,
                    'password_updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        // Reset password info for coaches
        Coach::whereNotNull('current_password')->update([
            'current_password' => null,
            'password_updated_at' => null
        ]);
    }
};