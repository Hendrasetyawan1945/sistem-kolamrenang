<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('coach_id')->nullable()->constrained('coaches')->onDelete('set null')->after('deskripsi');
            $table->json('jadwal')->nullable()->after('coach_id'); // [{hari, jam_mulai, jam_selesai}]
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['coach_id']);
            $table->dropColumn(['coach_id', 'jadwal']);
        });
    }
};
