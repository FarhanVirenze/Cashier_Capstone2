<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiPenjualanExport implements FromView
{
    protected $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function view(): View
    {
        return view('transaksi.excel', [
            'transaksi' => $this->transaksi
        ]);
    }
}
