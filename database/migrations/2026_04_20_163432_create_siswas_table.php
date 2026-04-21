<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('kelas');
            $table->text('alamat');
            $table->string('nama_ortu');
            $table->string('telepon', 20);
            $table->string('email')->nullable();
            $table->string('paket');
            $table->text('catatan')->nullable();
            $table->enum('status', ['calon', 'aktif', 'cuti', 'nonaktif'])->default('calon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
