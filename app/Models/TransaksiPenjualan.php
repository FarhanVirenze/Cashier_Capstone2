<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    protected $fillable = [
        'no_invoice',
        'metode_pembayaran',
        'status_pembayaran', // ✅ WAJIB
        'subtotal',          // ✅ WAJIB
        'diskon',
        'total',
        'jumlah_bayar',
        'kembalian',
        'customer_id',
        'user_id',
        'total_modal',
        'profit',
        'cetak_struk',
    ];

    // Relasi ke detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'transaksi_penjualan_id');
    }

    // Alias relasi
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'transaksi_penjualan_id', 'id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customer')
            ->withDefault([
                'nama' => 'Umum',
            ]);
    }
}
