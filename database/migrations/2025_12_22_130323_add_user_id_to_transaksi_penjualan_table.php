<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            // Tambahkan kolom user_id setelah nama_user
            $table->foreignId('user_id')->nullable()->after('nama_user')->constrained('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
