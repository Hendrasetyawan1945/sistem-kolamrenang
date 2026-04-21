<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jersey_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('jersey_size_id')->constrained('jersey_sizes')->onDelete('cascade');
            $table->string('nomor_punggung')->nullable();
            $table->string('nama_punggung')->nullable();
            $table->date('tanggal_pesan');
            $table->enum('status', ['dipesan', 'diproses', 'selesai', 'diambil'])->default('dipesan');
            $table->decimal('harga', 12, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jersey_orders');
    }
};
