<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        h2 {
            text-align: center;
        }
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <h2>Laporan Transaksi Penjualan</h2>
    @if ($start && $end)
        <p><strong>Periode:</strong> {{ $start }} s/d {{ $end }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Modal</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->no_invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $trx->nama_user }}</td>
                    <td>{{ $trx->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $trx->metode_pembayaran }}</td>
                    <td>Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($trx->profit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
