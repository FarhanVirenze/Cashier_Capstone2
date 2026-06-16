<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id_customer';

    protected $fillable = [
        'nama',
        'kelamin',
        'no_telepon',
        'alamat'
    ];

     public function transaksi()
    {
        return $this->hasMany(
            TransaksiPenjualan::class,
            'customer_id',
            'id_customer'
        );
    }
}
