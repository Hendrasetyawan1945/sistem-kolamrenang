<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_rapors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->string('kelas'); // pemula, menengah, lanjut, prestasi
            $table->text('deskripsi')->nullable();
            $table->json('komponen'); // [{nama, bobot}]
            $table->text('template_catatan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_rapors');
    }
};
