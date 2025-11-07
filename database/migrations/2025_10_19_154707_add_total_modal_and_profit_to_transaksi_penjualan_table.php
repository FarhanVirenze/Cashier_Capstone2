<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->decimal('total_modal', 15, 2)->default(0)->after('total');
            $table->decimal('profit', 15, 2)->default(0)->after('total_modal');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropColumn(['total_modal', 'profit']);
        });
    }
};
