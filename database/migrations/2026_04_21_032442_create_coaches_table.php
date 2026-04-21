<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('spesialisasi')->nullable();
            $table->string('pengalaman')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('bio')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
