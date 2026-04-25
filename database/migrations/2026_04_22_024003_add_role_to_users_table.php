<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'siswa', 'coach'])->default('admin')->after('email');
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null')->after('role');
            $table->foreignId('coach_id')->nullable()->constrained('coaches')->onDelete('set null')->after('siswa_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropForeign(['coach_id']);
            $table->dropColumn(['role', 'siswa_id', 'coach_id']);
        });
    }
};
