<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\DetailPenjualan;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class DetailPenjualanExport implements FromCollection, WithEvents, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DetailPenjualan::with('transaksi.customer', 'product');

        // Filter tanggal
        if ($this->request->start_date) {
            $query->whereHas('transaksi', function ($q) {
                $q->whereDate('created_at', '>=', $this->request->start_date);
            });
        }
        if ($this->request->end_date) {
            $query->whereHas('transaksi', function ($q) {
                $q->whereDate('created_at', '<=', $this->request->end_date);
            });
        }

        // Filter produk
        if ($this->request->product_id) {
            $query->where('product_id', $this->request->product_id);
        }

        // Filter pelanggan: Semua / Umum / Customer tertentu
        if ($this->request->filled('customer_id')) {
            if ($this->request->customer_id === 'umum') {
                $query->whereHas('transaksi', function ($q) {
                    $q->whereNull('customer_id');
                });
            } else {
                $query->whereHas('transaksi', function ($q) {
                    $q->where('customer_id', $this->request->customer_id);
                });
            }
        }

        $details = $query->orderBy('created_at', 'desc')->get();

        // Map data untuk Excel + No
        return $details->map(function ($detail, $index) {
            return [
                'No' => $index + 1,
                'Produk' => $detail->product->nama ?? $detail->nama_product,
                'Qty' => $detail->jumlah,
                'Harga' => $detail->harga,
                'Total' => $detail->total,
                'Pelanggan' => $detail->transaksi->customer->nama ?? 'Umum',
                'Tanggal' => $detail->transaksi->created_at->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Produk', 'Qty', 'Harga', 'Total', 'Pelanggan', 'Tanggal'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Style header
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1E3A8A'], // biru gelap
                    ],
                ]);

                // Auto width kolom
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Tambahkan filter aktif di atas header
                $filters = [];
                if ($this->request->start_date) {
                    $filters[] = 'Tanggal Mulai: '.$this->request->start_date;
                }
                if ($this->request->end_date) {
                    $filters[] = 'Tanggal Akhir: '.$this->request->end_date;
                }
                if ($this->request->product_id) {
                    $filters[] = 'Produk: '.optional(Product::find($this->request->product_id))->nama;
                }
                if ($this->request->customer_id) {
                    $filters[] = 'Pelanggan: '.optional(Customer::find($this->request->customer_id))->nama;
                }
                if (empty($filters)) {
                    $filters[] = 'Filter: Semua Data';
                }

                // Gabungkan filter jadi string
                $filterText = implode(' | ', $filters);

                // Sisipkan di baris 1 (geser data ke bawah)
                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', $filterText);
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '1E3A8A']],
                ]);
            },
        ];
    }
}
