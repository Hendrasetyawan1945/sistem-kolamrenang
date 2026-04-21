<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angsurans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('jenis_tagihan'); // Iuran Rutin, Paket Kuota, Insidentil, dll
            $table->decimal('total_tagihan', 12, 2);
            $table->decimal('total_dibayar', 12, 2)->default(0);
            $table->decimal('sisa_tagihan', 12, 2);
            $table->integer('jumlah_cicilan')->default(0);
            $table->enum('status', ['aktif', 'lunas', 'batal'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_tagihan');
            $table->timestamps();
        });

        Schema::create('angsuran_cicilans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('angsuran_id')->constrained('angsurans')->onDelete('cascade');
            $table->integer('cicilan_ke');
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_bayar');
            $table->string('metode_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran_cicilans');
        Schema::dropIfExists('angsurans');
    }
};
