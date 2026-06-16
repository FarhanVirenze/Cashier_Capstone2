<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        header h2 {
            margin: 0;
            font-size: 18px;
            color: #111827;
        }

        header h4 {
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #6b7280;
        }

        .filters {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 6px;
            font-size: 12px;
        }

        .filters span {
            display: inline-block;
            margin-right: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #9ca3af;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #e5e7eb;
            color: #111827;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tfoot td {
            font-weight: bold;
            background-color: #e5e7eb;
        }

        tfoot tr td:first-child {
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <header>
        <div>
            <h2>Detail Penjualan</h2>
            <h4>Dicetak Pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</h4>
        </div>
    </header>

    {{-- Filter Aktif --}}
    <div class="filters">
        @php
            $customerLabel = match(request()->customer_id) {
                '' => 'Semua Pelanggan',
                null => 'Semua Pelanggan',
                default => (optional(\App\Models\Customer::find(request()->customer_id))->nama ?? 'Umum')
            };
        @endphp

        <span><strong>Periode:</strong> 
            {{ request()->start_date ?? 'Semua' }} 
            @if(request()->start_date && request()->end_date) s/d {{ request()->end_date }} @endif
        </span>
        <span><strong>Produk:</strong> {{ optional(\App\Models\Product::find(request()->product_id))->nama ?? 'Semua Produk' }}</span>
        <span><strong>Pelanggan:</strong> {{ $customerLabel }}</span>
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->product->nama ?? $detail->nama_product }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp{{ number_format($detail->harga,0,',','.') }}</td>
                <td>Rp{{ number_format($detail->total,0,',','.') }}</td>
                <td>{{ $detail->transaksi->customer->nama ?? 'Umum' }}</td>
                <td>{{ $detail->transaksi->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Subtotal:</td>
                <td colspan="3">Rp{{ number_format($details->sum('total'),0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
