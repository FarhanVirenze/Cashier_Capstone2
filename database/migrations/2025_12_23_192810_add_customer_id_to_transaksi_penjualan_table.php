<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')
                ->nullable()
                ->after('user_id');

            $table->foreign('customer_id')
                ->references('id_customer')
                ->on('customers')
                ->nullOnDelete(); // jika customer dihapus → transaksi tetap ada
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
