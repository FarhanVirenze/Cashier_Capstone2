<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    protected $fillable = [
        'no_invoice',          // tambahan
        'tanggal',
        'nama_pelanggan',
        'nomor_pelanggan',
        'nama_user',
        'metode_pembayaran',
        'jumlah_bayar',
        'total',
        'kembalian',
        'total_modal',         // ditambahkan
        'profit',              // ditambahkan
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
}
