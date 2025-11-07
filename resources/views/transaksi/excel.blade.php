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
            @php
                $total_sub = 0;
                $total_bayar = 0;
                $total_modal = 0;
                $total_profit = 0;
            @endphp

            @foreach ($transaksi as $i => $trx)
                @php
                    $total_sub += $trx->total;
                    $total_bayar += $trx->jumlah_bayar;
                    $total_modal += $trx->total_modal;
                    $total_profit += $trx->profit;
                @endphp
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

        <tfoot>
            <tr>
                <td colspan="7" class="text-right">Total Keseluruhan:</td>
                <td class="text-right">Rp{{ number_format($total_sub, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-right">Rp{{ number_format($total_modal, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total_profit, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>