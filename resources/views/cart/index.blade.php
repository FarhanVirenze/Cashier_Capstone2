<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base sm:text-lg text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-blue-50 overflow-hidden shadow-lg rounded-xl border border-blue-100">
                <div class="p-4 sm:p-6 text-gray-900 text-sm">

                    {{-- Tombol Kembali --}}
                    <div class="mb-4">
                        <a href="{{ route('scanner.index') }}"
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm sm:text-base
                                   bg-blue-600 text-white rounded-lg
                                   hover:bg-blue-700 shadow transition">
                            <i class="fa fa-arrow-left"></i>
                            <span class="font-semibold">Kembali ke Scanner</span>
                        </a>
                    </div>

                    {{-- Cek keranjang --}}
                    @if ($items->isEmpty())
                        <div class="py-10 text-center">
                            <p class="text-gray-500 italic text-base sm:text-lg">
                                Keranjang masih kosong 🛒
                            </p>
                        </div>
                    @else
                        @php
                            $total = $items->reduce(
                                fn($carry, $item) => $carry + $item->product->harga * $item->quantity,
                                0,
                            );
                        @endphp

                        <form id="formPembayaran" action="{{ route('cart.checkout') }}" method="POST"
                            class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- Customer --}}
                                <div class="col-span-2 sm:col-span-2 lg:col-span-2">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700">Customer</label>
                                    <select id="customerSelect" name="customer_id"
                                        class="mt-1 w-full rounded-md bg-white text-black border-gray-300 shadow-sm
                                               focus:border-indigo-400 focus:ring focus:ring-indigo-200">
                                        <option value="">Umum</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id_customer }}">
                                                {{ $customer->nama }} — {{ $customer->no_telepon }} —
                                                {{ $customer->alamat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- TomSelect --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        new TomSelect("#customerSelect", {
                                            placeholder: "Pilih Customer...",
                                            allowEmptyOption: true,
                                            maxOptions: null,
                                            create: false,
                                            searchField: ['text'],
                                        });
                                    });
                                </script>

                                <style>
                                    .ts-dropdown {
                                        max-height: 260px;
                                        overflow-y: auto;
                                    }
                                </style>

                                {{-- Metode Pembayaran --}}
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700">
                                        Metode Pembayaran
                                    </label>
                                    <select id="metode_pembayaran" name="metode_pembayaran" required
                                        class="mt-1 w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm
                                               focus:border-indigo-400 focus:ring focus:ring-indigo-200">
                                        <option value="cash">Cash</option>
                                        <option value="midtrans">Midtrans</option>
                                    </select>
                                </div>

                                {{-- Jumlah Bayar --}}
                                <div id="jumlahBayarWrapper">
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700">Jumlah
                                        Bayar</label>
                                    <input type="number" step="0.01" name="jumlah_bayar" required
                                        class="mt-1 w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm
                                               focus:border-indigo-400 focus:ring focus:ring-indigo-200"
                                        placeholder="Contoh: 50000">
                                </div>
                            </div>

                            {{-- Total & Action --}}
                            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-4 sm:p-5">
                                <div class="grid grid-cols-2 gap-4 items-center">
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-700 tracking-wide">Subtotal
                                        </p>
                                        <p id="subtotalText" class="text-xl sm:text-2xl font-bold text-black">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-700 tracking-wide">Diskon</p>
                                        <p id="diskonText" class="text-xl sm:text-2xl font-bold text-red-600">Rp 0</p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-700 tracking-wide">Total</p>
                                        <p id="totalText" class="text-2xl sm:text-3xl font-bold text-black">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-700 tracking-wide">Kembalian
                                        </p>
                                        <p id="kembalianText" class="text-2xl sm:text-2xl font-bold text-black">Rp 0</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-3
                                               px-6 py-3 text-base font-semibold
                                               bg-green-700 text-white rounded-xl
                                               hover:bg-green-800 active:scale-[0.98]
                                               shadow-lg transition-all">
                                        <i class="fa-solid fa-print text-lg"></i> Cetak Struk & Simpan
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Notifikasi --}}
                        @if (session('success'))
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                                class="mb-4 p-3 sm:p-4 bg-green-50 border border-green-400 text-green-700 font-semibold rounded-lg shadow">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                                class="mb-4 p-3 sm:p-4 bg-red-50 border border-red-400 text-red-600 font-semibold rounded-lg shadow">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Isi Keranjang --}}
                        <div class="mt-6 overflow-x-auto">
                            <table class="w-full border border-gray-200 rounded-xl text-xs sm:text-sm">
                                <thead class="bg-gray-100 text-gray-700 tracking-wide">
                                    <tr>
                                        <th class="px-2 sm:px-4 py-2 text-left w-[45%]">Nama Produk</th>
                                        <th class="px-2 sm:px-4 py-2 text-right w-[25%]">Harga</th>
                                        <th class="px-2 sm:px-4 py-2 text-center w-[20%]">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($items as $item)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-2 sm:px-4 py-2 font-semibold text-gray-800">
                                                {{ \Illuminate\Support\Str::limit($item->product->nama, 15, '...') }}
                                            </td>
                                            <td class="px-2 sm:px-4 py-2 text-right font-medium">
                                                Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="px-2 sm:px-4 py-2 text-center">
                                                <form action="{{ route('cart.update', $item) }}" method="POST"
                                                    class="inline-flex items-center gap-1 sm:gap-2 justify-center">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="button"
                                                        onclick="let i=this.form.quantity;i.value=Math.max(0,i.value-1);this.form.submit();"
                                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-red-600 text-white font-bold flex items-center justify-center hover:bg-red-700 transition">
                                                        −
                                                    </button>

                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        min="0" onchange="this.form.submit()"
                                                        class="w-12 sm:w-14 min-w-[3.5rem] text-center border border-gray-300 rounded-md text-xs sm:text-sm">

                                                    <button type="button"
                                                        onclick="let i=this.form.quantity;i.value++;this.form.submit();"
                                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center hover:bg-blue-700 transition">
                                                        +
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Midtrans Snap --}}
                        @if (isset($snapToken))
                            <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
                            <script>
                                const snapToken = "{{ $snapToken }}";
                            </script>
                        @endif

                </div>
            </div>
        </div>
    </div>

    <style>
        /* ================= SCREEN MODE ================= */
        #print-area {
            display: none;
        }

        /* ================= PRINT MODE ================= */
        @media print {
            @page {
                size: auto;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                display: block;
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);

                width: 320px;
                padding: 12px;

                font-family: monospace;
                font-size: 14px;
                line-height: 1.4;
                background: #fff;
                color: #000;
            }

            h2 {
                margin: 0;
                text-align: center;
            }

            .item {
                margin-bottom: 6px;
            }

            .item strong {
                display: block;
                text-transform: uppercase;
            }

            .item-row {
                display: flex;
                justify-content: space-between;
                font-size: 13px;
            }

            hr {
                border: none;
                border-top: 2px dashed #000;
                margin: 10px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                padding: 2px 0;
            }
        }
    </style>

    {{-- ================= AREA CETAK STRUK ================= --}}
    @if (session('print_transaksi'))
        @php
            $trx = session('print_transaksi');
        @endphp

        <div id="print-area">

            {{-- HEADER --}}
            <h2>Warung Golpal</h2>
            <p style="text-align:center;">
                {{ $trx->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}
            </p>

            {{-- INFO TRANSAKSI --}}
            <p>
                <strong>No. Invoice:</strong> {{ $trx->no_invoice }}<br>
                <strong>Kasir:</strong> {{ $trx->user->name ?? '-' }}<br>
                <strong>Pelanggan:</strong> {{ optional($trx->customer)->nama ?? 'Umum' }}<br>
                <strong>Nomor:</strong> {{ optional($trx->customer)->no_telepon ?? '-' }}<br>
                <strong>Metode:</strong> {{ strtoupper($trx->metode_pembayaran ?? '-') }}
            </p>

            <hr>

            {{-- ITEM LIST --}}
            @foreach ($trx->details as $item)
                <div class="item">
                    <strong>{{ $item->product->nama_product ?? $item->nama_product }}</strong>
                    <div class="item-row">
                        <span>
                            {{ $item->jumlah }} x Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </span>
                        <span>
                            Rp{{ number_format($item->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach

            <hr>

            {{-- RINGKASAN --}}
            <table>
                <tr>
                    <td>Sub Total</td>
                    <td style="text-align:right;">
                        Rp{{ number_format($trx->total + ($trx->diskon ?? 0), 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Diskon</td>
                    <td style="text-align:right;">
                        Rp{{ number_format($trx->diskon ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td style="text-align:right;">
                        Rp{{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td style="text-align:right;">
                        Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td style="text-align:right;">
                        Rp{{ number_format($trx->kembalian, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            <p style="text-align:center; margin-top:12px;">
                *** TERIMA KASIH ***
            </p>

        </div>

        {{-- Auto print --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.print();
            });
        </script>
    @endif

    {{-- Script JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSelect = document.getElementById('customerSelect');
            const metodeSelect = document.getElementById('metode_pembayaran');
            const jumlahBayarInput = document.querySelector('input[name="jumlah_bayar"]');
            const subtotalText = document.getElementById('subtotalText');
            const diskonText = document.getElementById('diskonText');
            const totalText = document.getElementById('totalText');
            const kembalianText = document.getElementById('kembalianText');
            const form = document.getElementById('formPembayaran');

            const subtotal = Number('{{ $total }}');
            let diskon = 0;
            let total = subtotal;

            function rupiah(angka) {
                return 'Rp ' + Number(angka).toLocaleString('id-ID');
            }

            function updateTotal() {
                diskon = customerSelect && customerSelect.value ? Math.round(subtotal * 0.05) : 0;
                total = subtotal - diskon;

                subtotalText.textContent = rupiah(subtotal);
                diskonText.textContent = rupiah(diskon);
                totalText.textContent = rupiah(total);

                if (metodeSelect.value === 'midtrans') {
                    jumlahBayarInput.value = total;
                    jumlahBayarInput.setAttribute('readonly', true);
                    jumlahBayarInput.removeAttribute('required');
                    kembalianText.textContent = rupiah(0);
                    kembalianText.classList.remove('text-red-600', 'text-green-700');
                    kembalianText.classList.add('text-gray-800');
                } else {
                    jumlahBayarInput.removeAttribute('readonly');
                    jumlahBayarInput.setAttribute('required', true);
                    hitungKembalian();
                }
            }

            function hitungKembalian() {
                const bayar = Number(jumlahBayarInput.value) || 0;
                const kembali = Math.max(bayar - total, 0);
                kembalianText.textContent = rupiah(kembali);

                if (bayar < total) {
                    kembalianText.classList.remove('text-green-700');
                    kembalianText.classList.add('text-red-600');
                } else {
                    kembalianText.classList.remove('text-red-600');
                    kembalianText.classList.add('text-green-700');
                }
            }

            customerSelect?.addEventListener('change', updateTotal);
            metodeSelect.addEventListener('change', updateTotal);
            jumlahBayarInput?.addEventListener('input', hitungKembalian);

            updateTotal();

            form.addEventListener('submit', function(e) {
                if (metodeSelect.value === 'midtrans') {
                    e.preventDefault();
                    if (!snapToken) {
                        alert('Token pembayaran tidak tersedia!');
                        return;
                    }

                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            console.log('MIDTRANS SUCCESS', result);
                            form.submit();
                            setTimeout(() => window.print(), 500);
                        },
                        onPending: function(result) {
                            console.log('MIDTRANS PENDING', result);
                            alert(
                                'Pembayaran pending, silakan selesaikan di aplikasi pembayaran.'
                                );
                        },
                        onError: function(result) {
                            console.log('MIDTRANS ERROR', result);
                            alert('Pembayaran gagal.');
                        },
                        onClose: function() {
                            alert('Popup pembayaran ditutup.');
                        }
                    });
                }
            });

        });
    </script>
    @endif

</x-app-layout>
