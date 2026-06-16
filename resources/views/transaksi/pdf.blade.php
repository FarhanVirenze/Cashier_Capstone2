<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan Warung Golpal</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px; /* lebih kecil */
            color: #2c3e50;
            margin: 15px; /* margin lebih kecil */
            background-color: #ffffff;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #bbb;
        }

        .header h2 {
            margin: 0;
            font-size: 16px; /* font header lebih kecil */
            font-weight: bold;
            color: #000;
        }

        .header p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #555;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed; /* agar kolom muat di halaman */
        }

        thead {
            background: #f2f2f2;
            color: #000;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px 6px; /* padding lebih kecil */
            font-size: 9.5px; /* font lebih kecil */
            word-wrap: break-word; /* agar teks tidak melebar */
        }

        th {
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.2px;
        }

        td {
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* FOOTER & SIGNATURE */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .footer {
            font-size: 9px;
            color: #555;
        }

        .signature {
            text-align: right;
            font-size: 10px;
            color: #000;
        }

        .signature p {
            margin: 2px 0;
        }

        .signature .line {
            margin-top: 30px;
            border-top: 1px solid #000;
            width: 160px;
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
                <th>Subtotal</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Jumlah Bayar</th>
                <th>Kembalian</th>
                <th>Total Modal</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_sub = 0;
                $total_diskon = 0;
                $total_total = 0;
                $total_bayar = 0;
                $total_kembalian = 0;
                $total_modal = 0;
                $total_profit = 0;
            @endphp

            @foreach ($transaksi as $i => $trx)
                @php
                    $total_sub += $trx->subtotal;
                    $total_diskon += $trx->diskon;
                    $total_total += $trx->total;
                    $total_bayar += $trx->jumlah_bayar;
                    $total_kembalian += $trx->kembalian;
                    $total_modal += $trx->total_modal;
                    $total_profit += $trx->profit;
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $trx->no_invoice }}</td>
                    <td class="text-center">{{ $trx->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>{{ $trx->customer->nama ?? 'Umum' }}</td>
                    <td>{{ $trx->customer->no_telepon ?? '-' }}</td>
                    <td>{{ $trx->metode_pembayaran }}</td>
                    <td class="text-right">Rp{{ number_format($trx->subtotal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->diskon, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($trx->profit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right">Total Keseluruhan:</td>
                <td class="text-right">Rp{{ number_format($total_sub, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_diskon, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_total, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_kembalian, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_modal, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_profit, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="bottom-section">
        <div class="footer">
            <p><strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <div class="signature">
            <p>Mengetahui,</p>
            <p><strong>Manajer Warung Golpal</strong></p>
            <div class="line"></div>
        </div>
    </div>
</body>

</html>
