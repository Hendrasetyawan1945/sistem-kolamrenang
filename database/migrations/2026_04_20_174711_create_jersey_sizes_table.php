<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jersey_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_size'); // XS, S, M, L, XL, XXL
            $table->integer('lebar_dada')->nullable(); // cm
            $table->integer('panjang_badan')->nullable(); // cm
            $table->integer('panjang_lengan')->nullable(); // cm
            $table->integer('tinggi_badan_min')->nullable(); // cm
            $table->integer('tinggi_badan_max')->nullable(); // cm
            $table->integer('berat_badan_min')->nullable(); // kg
            $table->integer('berat_badan_max')->nullable(); // kg
            $table->integer('umur_min')->nullable();
            $table->integer('umur_max')->nullable();
            $table->integer('stok')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jersey_sizes');
    }
};
