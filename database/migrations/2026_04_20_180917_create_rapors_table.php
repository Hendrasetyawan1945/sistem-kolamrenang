<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('template_rapor_id')->nullable()->constrained('template_rapors')->onDelete('set null');
            $table->string('periode'); // "April 2026"
            $table->integer('bulan');
            $table->integer('tahun');
            $table->json('nilai')->nullable(); // [{komponen, nilai, keterangan}]
            $table->integer('kehadiran')->default(0);
            $table->integer('total_pertemuan')->default(0);
            $table->text('catatan_coach')->nullable();
            $table->text('catatan_umum')->nullable();
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->timestamps();

            $table->unique(['siswa_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapors');
    }
};
