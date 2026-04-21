<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('jenis_pembayaran'); // iuran_rutin, paket_kuota, insidentil, kejuaraan
            $table->integer('tahun');
            $table->integer('bulan'); // 1-12
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_bayar');
            $table->string('metode_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->unique(['siswa_id', 'jenis_pembayaran', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
