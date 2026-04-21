<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kejuaraaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kejuaraan');
            $table->string('penyelenggara');
            $table->date('tanggal_kejuaraan');
            $table->string('lokasi');
            $table->decimal('biaya_pendaftaran', 12, 0);
            $table->date('batas_pendaftaran');
            $table->text('deskripsi');
            $table->json('kategori'); // Array of categories
            $table->enum('status', ['akan_datang', 'pendaftaran', 'pendaftaran_tutup', 'berlangsung', 'selesai', 'dibatalkan'])->default('akan_datang');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kejuaraaans');
    }
};
