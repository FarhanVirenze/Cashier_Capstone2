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

        th,
        td {
            border: 1px solid #aaa;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
            text-align: center;
        }

        td.numeric {
            text-align: right;
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
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>No. Telp</th>
                <th>Metode</th>
                <th>Subtotal</th>
                <th>Diskon</th>
                <th>Jumlah Bayar</th>
                <th>Kembalian</th>
                <th>Total</th>
                <th>Modal</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $i => $trx)
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}</td>
                    <td>{{ $trx->no_invoice ?? '-' }}</td>
                    <td>{{ $trx->created_at ? $trx->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') : '-' }}</td>
                    <td>{{ $trx->user->name ?? ($trx->nama_user ?? '-') }}</td>
                    <td>{{ $trx->customer->nama ?? ($trx->nama_pelanggan ?? '-') }}</td>
                    <td>{{ $trx->customer->no_telepon ?? '-' }}</td>
                    <td>{{ ucfirst($trx->metode_pembayaran ?? '-') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->subtotal ?? 0, 0, ',', '.') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->diskon ?? 0, 0, ',', '.') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->kembalian ?? 0, 0, ',', '.') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->total ?? 0, 0, ',', '.') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->total_modal ?? 0, 0, ',', '.') }}</td>
                    <td class="numeric">Rp{{ number_format($trx->profit ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="text-align:center;">Tidak ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
