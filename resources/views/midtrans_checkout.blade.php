<x-app-layout>
    <x-slot name="header"><h2>Bayar dengan Midtrans</h2></x-slot>

    <div class="p-4">
        <p>No Invoice: {{ $transaksi->no_invoice }}</p>
        <p>Total: Rp {{ number_format($transaksi->total,0,',','.') }}</p>
        <div id="midtrans-button"></div>
    </div>

    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){ alert('Pembayaran sukses!'); window.location='{{ route("cart.index") }}'; },
            onPending: function(result){ alert('Pembayaran pending.'); window.location='{{ route("cart.index") }}'; },
            onError: function(result){ alert('Pembayaran gagal!'); window.location='{{ route("cart.index") }}'; },
        });
    </script>
</x-app-layout>
