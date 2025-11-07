<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan Warung Golpal</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            color: #2c3e50;
            margin: 25px;
            background-color: #ffffff;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid #bbb;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #555;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background: #f2f2f2;
            color: #000;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }

        th {
            text-transform: uppercase;
            font-size: 10.5px;
            text-align: center;
            letter-spacing: 0.3px;
        }

        td {
            font-size: 10.5px;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* FOOTER & SIGNATURE */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .footer {
            font-size: 10px;
            color: #555;
        }

        .signature {
            text-align: right;
            font-size: 11px;
            color: #000;
        }

        .signature p {
            margin: 3px 0;
        }

        .signature .line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 180px;
            margin-left: auto;
        }

        /* TFOOT TOTAL */
        tfoot td {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Transaksi Penjualan Warung Golpal</h2>
        <p>Periode: {{ $start }} s/d {{ $end }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Nomor</th>
                <th>Metode</th>
                <th>Sub Total</th>
                <th>Total Bayar</th>
                <th>Kembalian</th>
                <th>Total Modal</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $i => $trx)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $trx->no_invoice }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $trx->nama_user }}</td>
                    <td>{{ $trx->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $trx->nomor_pelanggan ?? '-' }}</td>
                    <td>{{ $trx->metode_pembayaran }}</td>
                    <td class="text-right">Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->profit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="bottom-section">
        <div class="footer">
            <p><strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <div class="signature">
            <p>Mengetahui,</p>
            <p><strong>Manajer Warung Golpal</strong></p>
            <div class="line"></div>
        </div>
    </div>
</body>
</html>
