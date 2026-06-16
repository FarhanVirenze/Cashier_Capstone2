<!DOCTYPE html>
<html>
<head>
    <title>Laporan Dashboard Penjualan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
            margin: 20px;
        }

        /* ====== TYPOGRAPHY ====== */
        h2 {
            text-align: center;
            margin-bottom: 4px;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        h3 {
            margin: 18px 0 6px;
            font-size: 13px;
            border-left: 4px solid #2563eb;
            padding-left: 8px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 16px;
        }

        /* ====== TABLE ====== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        td {
            vertical-align: middle;
        }

        /* ====== UTIL ====== */
        .right { text-align: right; }
        .center { text-align: center; }

        .no-border td {
            border: none;
            padding: 2px 0;
        }

        .info-table td:first-child {
            width: 120px;
            font-weight: bold;
        }

        .summary td {
            font-weight: bold;
            background: #f9fafb;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #555;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<h2>LAPORAN DASHBOARD PENJUALAN</h2>
<div class="subtitle">GoScan Kasir Modern</div>

<table class="no-border info-table">
    <tr>
        <td>Periode</td><td>:</td>
        <td>
            {{ $filter === 'hari_ini' ? 'Hari Ini' :
               ($filter === 'bulan_ini' ? 'Bulan Ini' :
               ($filter === 'tahun_ini' ? 'Tahun Ini' :
               ($filter === 'rentang' ? $tanggalMulai.' s/d '.$tanggalSelesai : 'Semua'))) }}
        </td>
    </tr>
    <tr>
        <td>Kasir</td><td>:</td>
        <td>
            {{ $filterUserId === 'semua'
                ? 'Semua Kasir'
                : optional($users->firstWhere('id',$filterUserId))->name }}
        </td>
    </tr>
    <tr>
        <td>Tanggal Cetak</td><td>:</td>
        <td>{{ now()->format('d F Y H:i') }}</td>
    </tr>
</table>

{{-- ================= RINGKASAN ================= --}}
<h3>Ringkasan Utama</h3>
<table class="summary">
    <tr>
        <th>Total Pendapatan</th>
        <th>Total Profit</th>
        <th>Persentase Profit</th>
        <th>Jumlah Transaksi</th>
        <th>Produk Terjual</th>
    </tr>
    <tr>
        <td class="right">Rp {{ number_format($totalPendapatan,0,',','.') }}</td>
        <td class="right">Rp {{ number_format($totalProfit,0,',','.') }}</td>
        <td class="center">{{ $persentaseProfit }}%</td>
        <td class="center">{{ $jumlahTransaksi }}</td>
        <td class="center">{{ $produkTerjual }}</td>
    </tr>
</table>

{{-- ================= PENJUALAN HARIAN ================= --}}
<h3>Penjualan Harian</h3>
<table>
    <tr>
        <th width="40%">Tanggal</th>
        <th>Total Penjualan</th>
    </tr>
    @foreach ($penjualanHarian as $row)
    <tr>
        <td>{{ $row->tanggal }}</td>
        <td class="right">Rp {{ number_format($row->total,0,',','.') }}</td>
    </tr>
    @endforeach
</table>

{{-- ================= PROFIT HARIAN ================= --}}
<h3>Profit Harian</h3>
<table>
    <tr>
        <th width="40%">Tanggal</th>
        <th>Total Profit</th>
    </tr>
    @foreach ($profitHarian as $row)
    <tr>
        <td>{{ $row->tanggal }}</td>
        <td class="right">Rp {{ number_format($row->total_profit,0,',','.') }}</td>
    </tr>
    @endforeach
</table>

{{-- ================= PRODUK TERLARIS ================= --}}
<h3>Produk Terlaris</h3>
<table>
    <tr>
        <th width="40">No</th>
        <th>Nama Produk</th>
        <th width="80">Terjual</th>
    </tr>
    @foreach ($produkTerlarisTable as $i => $item)
    <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td>{{ $item->product->nama ?? '-' }}</td>
        <td class="center">{{ $item->total }}</td>
    </tr>
    @endforeach
</table>

{{-- ================= STOK PRODUK ================= --}}
<h3>Stok Produk</h3>
<table>
    <tr>
        <th>Nama Produk</th>
        <th width="80">Stok</th>
    </tr>
    @foreach ($stokTersediaChart as $item)
    <tr>
        <td>{{ $item->nama }}</td>
        <td class="center">{{ $item->stok }}</td>
    </tr>
    @endforeach
</table>

{{-- ================= METODE PEMBAYARAN ================= --}}
<h3>Metode Pembayaran</h3>
<table>
    <tr>
        <th>Metode</th>
        <th width="140">Jumlah Transaksi</th>
    </tr>
    @foreach ($metodePembayaran as $item)
    <tr>
        <td>{{ strtoupper($item->metode_pembayaran) }}</td>
        <td class="center">{{ $item->total }}</td>
    </tr>
    @endforeach
</table>

{{-- ================= TRANSAKSI TERAKHIR ================= --}}
<h3>Transaksi Terakhir</h3>
<table>
    <tr>
        <th width="40">No</th>
        <th>Tanggal</th>
        <th>Total</th>
        <th width="120">Metode</th>
    </tr>
    @foreach ($transaksiTerakhir as $i => $trx)
    <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td>{{ $trx->created_at->format('d-m-Y H:i') }}</td>
        <td class="right">Rp {{ number_format($trx->total,0,',','.') }}</td>
        <td class="center">{{ strtoupper($trx->metode_pembayaran) }}</td>
    </tr>
    @endforeach
</table>

<div class="footer">
    Dicetak oleh sistem • {{ config('app.name') }}
</div>

</body>
</html>
