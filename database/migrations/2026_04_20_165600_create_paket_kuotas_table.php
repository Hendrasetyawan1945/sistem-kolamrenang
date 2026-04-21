<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_kuotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->integer('jumlah_pertemuan');
            $table->decimal('harga', 12, 2);
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_kuotas');
    }
};
