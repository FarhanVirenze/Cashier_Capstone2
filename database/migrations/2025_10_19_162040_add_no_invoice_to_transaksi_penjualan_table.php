<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPenjualan;

return new class extends Migration {
    public function up(): void
    {
        // 1️⃣ Tambahkan kolom tanpa unique dulu
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->string('no_invoice')->nullable()->after('id');
        });

        // 2️⃣ Isi semua data lama dengan nilai unik
        // Pastikan model TransaksiPenjualan sudah ada dan import benar
        DB::table('transaksi_penjualan')->orderBy('id')->get()->each(function ($trx) {
            DB::table('transaksi_penjualan')
                ->where('id', $trx->id)
                ->update(['no_invoice' => 'INV-' . str_pad($trx->id, 6, '0', STR_PAD_LEFT)]);
        });

        // 3️⃣ Ubah kolom menjadi not nullable dan tambahkan unique
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->string('no_invoice')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            $table->dropColumn('no_invoice');
        });
    }
};
