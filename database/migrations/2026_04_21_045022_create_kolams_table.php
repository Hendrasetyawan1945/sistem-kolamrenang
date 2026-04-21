<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kolams', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                          // "Kolam Utama", "Kolam GBK"
            $table->enum('ukuran', ['25m', '50m']);          // Short Course / Long Course
            $table->string('lokasi')->nullable();            // Alamat / gedung
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kolams');
    }
};
