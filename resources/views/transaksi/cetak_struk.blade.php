<h3>Struk Transaksi #{{ $transaksi->no_invoice }}</h3>
<p>
    Tanggal: {{ $transaksi->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}
</p>
<p>Pelanggan: {{ $transaksi->nama_pelanggan }}</p>

<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaksi->details as $detail)
            <tr>
                <td>{{ $detail->product->nama ?? $detail->nama_product }}</td>
                <td>Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp{{ number_format($detail->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p><strong>Total:</strong> Rp{{ number_format($transaksi->total, 0, ',', '.') }}</p>
