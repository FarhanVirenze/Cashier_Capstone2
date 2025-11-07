<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    // Gunakan nama tabel khusus
    protected $table = 'products';

    // timestamps aktif (default true)
    public $timestamps = true;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama',
        'barcode',
        'harga',
        'modal', // tambahkan ini
        'foto',
        'stok',
    ];

    // Relasi dengan Pos
    public function posItems()
    {
        return $this->hasMany(Pos::class);
    }

    // Cast kolom ke tipe data yang sesuai
    protected $casts = [
        'harga' => 'float',
        'modal' => 'float', // tambahkan ini
        'stok' => 'integer',
    ];

     // Relasi ke detail penjualan (optional)
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'product_id', 'id');
    }
}
