<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';

    protected $fillable = [
        'transaksi_penjualan_id',
        'product_id',
        'user_id',
        'nama_product',
        'foto_product',       // tambahan
        'harga',
        'jumlah',
        'total',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_penjualan_id', 'id');
    }

    public function product()
    {
       return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
