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
            $total_kembali = 0;
            $total_modal = 0;
            $total_profit = 0;
        @endphp

        @foreach ($transaksi as $i => $trx)
            @php
                $subtotal = $trx->subtotal ?? 0;
                $diskon = $trx->diskon ?? 0;
                $total = $trx->total ?? 0;
                $bayar = $trx->jumlah_bayar ?? 0;
                $kembali = $trx->kembalian ?? 0;
                $modal = $trx->total_modal ?? 0;
                $profit = $trx->profit ?? 0;

                $total_sub += $subtotal;
                $total_diskon += $diskon;
                $total_total += $total;
                $total_bayar += $bayar;
                $total_kembali += $kembali;
                $total_modal += $modal;
                $total_profit += $profit;
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $trx->no_invoice }}</td>
                <td class="text-center">
                    {{ $trx->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
                </td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->customer->nama ?? 'Umum' }}</td>
                <td>{{ $trx->customer->no_telepon ?? '-' }}</td>
                <td>{{ $trx->metode_pembayaran ?? '-' }}</td>
                <td class="text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($diskon, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($total, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($bayar, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($kembali, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($modal, 0, ',', '.') }}</td>
                <td class="text-right">Rp{{ number_format($profit, 0, ',', '.') }}</td>
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
            <td class="text-right">Rp{{ number_format($total_kembali, 0, ',', '.') }}</td>
            <td class="text-right">Rp{{ number_format($total_modal, 0, ',', '.') }}</td>
            <td class="text-right">Rp{{ number_format($total_profit, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
