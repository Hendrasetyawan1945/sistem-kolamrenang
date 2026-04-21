<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_waktu_latihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jenis_latihan'); // teknik, speed, endurance, test_set
            $table->string('nomor_lomba')->nullable(); // 50m Freestyle, dll
            $table->string('set_jarak')->nullable(); // "4x50m", "200m", dll
            $table->string('waktu')->nullable(); // MM:SS.MS
            $table->decimal('waktu_detik', 8, 2)->nullable();
            $table->string('kelas')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_waktu_latihans');
    }
};
