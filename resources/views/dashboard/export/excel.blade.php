<table>
    {{-- ================= HEADER ================= --}}
    <tr>
        <td colspan="6" style="font-size:16px;font-weight:bold;">
            LAPORAN DASHBOARD PENJUALAN
        </td>
    </tr>

    <tr>
        <td>Periode</td>
        <td colspan="5">
            {{ $filter === 'hari_ini' ? 'Hari Ini' :
               ($filter === 'bulan_ini' ? 'Bulan Ini' :
               ($filter === 'tahun_ini' ? 'Tahun Ini' :
               ($filter === 'rentang' ? $tanggalMulai.' s/d '.$tanggalSelesai : 'Semua'))) }}
        </td>
    </tr>

    <tr>
        <td>Kasir</td>
        <td colspan="5">
            {{ $filterUserId === 'semua'
                ? 'Semua Kasir'
                : optional($users->firstWhere('id',$filterUserId))->name }}
        </td>
    </tr>

    <tr>
        <td>Tanggal Cetak</td>
        <td colspan="5">{{ now()->format('d F Y H:i') }}</td>
    </tr>

    <tr></tr>

    {{-- ================= RINGKASAN ================= --}}
    <tr style="font-weight:bold;">
        <td>Total Pendapatan</td>
        <td>Total Profit</td>
        <td>Persentase Profit</td>
        <td>Jumlah Transaksi</td>
        <td colspan="2">Produk Terjual</td>
    </tr>
    <tr>
        <td>{{ $totalPendapatan }}</td>
        <td>{{ $totalProfit }}</td>
        <td>{{ $persentaseProfit }}%</td>
        <td>{{ $jumlahTransaksi }}</td>
        <td colspan="2">{{ $produkTerjual }}</td>
    </tr>

    <tr></tr>

    {{-- ================= PENJUALAN HARIAN ================= --}}
    <tr style="font-weight:bold;">
        <td colspan="6">PENJUALAN HARIAN</td>
    </tr>
    <tr style="font-weight:bold;">
        <td>Tanggal</td>
        <td colspan="5">Total Penjualan</td>
    </tr>
    @foreach ($penjualanHarian as $row)
    <tr>
        <td>{{ $row->tanggal }}</td>
        <td colspan="5">{{ $row->total }}</td>
    </tr>
    @endforeach

    <tr></tr>

    {{-- ================= PROFIT HARIAN ================= --}}
    <tr style="font-weight:bold;">
        <td colspan="6">PROFIT HARIAN</td>
    </tr>
    <tr style="font-weight:bold;">
        <td>Tanggal</td>
        <td colspan="5">Total Profit</td>
    </tr>
    @foreach ($profitHarian as $row)
    <tr>
        <td>{{ $row->tanggal }}</td>
        <td colspan="5">{{ $row->total_profit }}</td>
    </tr>
    @endforeach

    <tr></tr>

    {{-- ================= PRODUK TERLARIS ================= --}}
    <tr style="font-weight:bold;">
        <td colspan="6">PRODUK TERLARIS</td>
    </tr>
    <tr style="font-weight:bold;">
        <td>No</td>
        <td colspan="4">Nama Produk</td>
        <td>Terjual</td>
    </tr>
    @foreach ($produkTerlarisTable as $i => $item)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td colspan="4">{{ $item->product->nama ?? '-' }}</td>
        <td>{{ $item->total }}</td>
    </tr>
    @endforeach

    <tr></tr>

    {{-- ================= STOK PRODUK ================= --}}
    <tr style="font-weight:bold;">
        <td colspan="6">STOK PRODUK</td>
    </tr>
    <tr style="font-weight:bold;">
        <td colspan="5">Produk</td>
        <td>Stok</td>
    </tr>
    @foreach ($stokTersediaChart as $item)
    <tr>
        <td colspan="5">{{ $item->nama }}</td>
        <td>{{ $item->stok }}</td>
    </tr>
    @endforeach

    <tr></tr>

    {{-- ================= METODE PEMBAYARAN ================= --}}
    <tr style="font-weight:bold;">
        <td colspan="6">METODE PEMBAYARAN</td>
    </tr>
    <tr style="font-weight:bold;">
        <td colspan="4">Metode</td>
        <td colspan="2">Jumlah Transaksi</td>
    </tr>
    @foreach ($metodePembayaran as $item)
    <tr>
        <td colspan="4">{{ strtoupper($item->metode_pembayaran) }}</td>
        <td colspan="2">{{ $item->total }}</td>
    </tr>
    @endforeach

    <tr></tr>

    {{-- ================= TRANSAKSI TERAKHIR ================= --}}
    <tr style="font-weight:bold;">
        <td colspan="6">TRANSAKSI TERAKHIR</td>
    </tr>
    <tr style="font-weight:bold;">
        <td>No</td>
        <td colspan="2">Tanggal</td>
        <td colspan="2">Total</td>
        <td>Metode</td>
    </tr>
    @foreach ($transaksiTerakhir as $i => $trx)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td colspan="2">{{ $trx->created_at->format('d-m-Y H:i') }}</td>
        <td colspan="2">{{ $trx->total }}</td>
        <td>{{ strtoupper($trx->metode_pembayaran) }}</td>
    </tr>
    @endforeach
</table>
