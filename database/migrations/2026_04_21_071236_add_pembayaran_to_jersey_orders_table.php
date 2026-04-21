<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jersey_orders', function (Blueprint $table) {
            $table->enum('status_bayar', ['belum_bayar', 'lunas'])->default('belum_bayar')->after('status');
            $table->date('tanggal_bayar')->nullable()->after('status_bayar');
            $table->string('metode_pembayaran')->nullable()->after('tanggal_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('jersey_orders', function (Blueprint $table) {
            $table->dropColumn(['status_bayar', 'tanggal_bayar', 'metode_pembayaran']);
        });
    }
};
