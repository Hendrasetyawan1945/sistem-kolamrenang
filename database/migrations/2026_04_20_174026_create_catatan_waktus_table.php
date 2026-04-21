<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_waktus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nomor_lomba'); // 50m Freestyle, 100m Butterfly, dll
            $table->enum('jenis_kolam', ['25m', '50m'])->default('25m');
            $table->string('waktu'); // Format: MM:SS.MS (contoh: 01:23.45)
            $table->integer('waktu_detik'); // Konversi ke detik untuk sorting
            $table->string('lokasi')->nullable();
            $table->enum('jenis_event', ['latihan', 'kejuaraan', 'time_trial'])->default('latihan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_waktus');
    }
};
