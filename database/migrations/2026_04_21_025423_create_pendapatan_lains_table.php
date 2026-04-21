<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendapatan_lains', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kategori'); // penjualan_jersey, sewa_kolam, sponsor, lainnya
            $table->string('deskripsi');
            $table->string('sumber')->nullable();
            $table->decimal('jumlah', 12, 0);
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['diterima', 'pending'])->default('diterima');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendapatan_lains');
    }
};
