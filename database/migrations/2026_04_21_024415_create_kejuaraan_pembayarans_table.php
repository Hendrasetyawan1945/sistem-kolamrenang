<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kejuaraan_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kejuaraan_id')->constrained('kejuaraaans')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->decimal('jumlah', 12, 0);
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['kejuaraan_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kejuaraan_pembayarans');
    }
};
