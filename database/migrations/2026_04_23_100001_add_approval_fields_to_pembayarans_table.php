<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('keterangan');
            $table->foreignId('input_by')->nullable()->constrained('users')->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('input_by');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->string('bukti_bayar')->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['input_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'status', 'input_by', 'approved_by', 'approved_at', 
                'rejection_reason', 'bukti_bayar'
            ]);
        });
    }
};