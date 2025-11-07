<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-200 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[97%] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="bg-blue-50 dark:bg-blue-50 overflow-hidden shadow-lg sm:rounded-xl border border-blue-100">
                <div class="p-5 sm:p-6 text-gray-900 dark:text-gray-100 text-sm">

                    {{-- === Notifikasi === --}}
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            x-transition
                            class="mb-4 p-3 sm:p-4 bg-green-50 border border-green-400 text-green-700 font-semibold rounded-lg shadow">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                            x-transition
                            class="mb-4 p-3 sm:p-4 bg-red-50 border border-red-400 text-red-600 font-semibold rounded-lg shadow">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- === Isi Keranjang === --}}
                    @if($items->isEmpty())
                        <div class="py-10 text-center">
                            <p class="text-gray-800 italic text-base sm:text-lg">Keranjang masih kosong 🛒</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($items as $item)
                                <div class="relative p-4 sm:p-5 rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 group border border-gray-200 backdrop-blur-sm"
                                    style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">
                                    <div class="flex items-center justify-between gap-3 sm:gap-5">

                                        {{-- === Gambar Produk === --}}
                                        <div class="relative shrink-0">
                                            <img src="{{ asset($item->product->foto) }}" alt="{{ $item->product->nama }}"
                                                class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg border border-gray-200 bg-blue-100 p-1" />

                                            {{-- Tombol Hapus (Admin) --}}
                                            @if(Auth::user()->is_admin)
                                                <button type="button" onclick="openDeleteModal('{{ $item->id }}')"
                                                    class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 bg-white border border-blue-200 text-red-600 hover:text-red-700 hover:scale-110 transition p-1 rounded-full shadow w-5 h-5 flex items-center justify-center">
                                                    <i class="fa fa-times text-xs"></i>
                                                </button>

                                                {{-- Modal Hapus --}}
                                                <div id="deleteModal{{ $item->id }}"
                                                    class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 backdrop-blur-sm">
                                                    <div class="p-6 rounded-xl shadow-xl bg-cover bg-center relative"
                                                        style="background-image: url('{{ asset('images/card1.png') }}'); width: 90%; max-width: 340px;">
                                                        <h2 class="text-lg font-semibold text-white text-center mb-3">Konfirmasi Hapus</h2>
                                                        <p class="text-white text-sm text-center mb-5">Apakah kamu yakin ingin
                                                            menghapus item ini dari keranjang?</p>

                                                        <div class="flex justify-center space-x-3">
                                                            <button type="button" onclick="closeDeleteModal('{{ $item->id }}')"
                                                                class="px-4 py-2 bg-gray-200 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition">Batal</button>

                                                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="px-4 py-2 bg-red-600 hover:bg-red-800 text-white rounded-md font-medium transition">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- === Info Produk === --}}
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-sm sm:text-base font-semibold text-white truncate" title="{{ $item->product->nama }}">
                                                {{ $item->product->nama }}
                                            </h5>
                                            <p class="text-xs sm:text-sm font-semibold text-white opacity-90">
                                                Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                                            </p>

                                            @if(Auth::user()->is_admin)
                                                <p class="text-xs font-semibold text-white opacity-80">
                                                    <small>User: {{ $item->user->name ?? 'Unknown' }}</small>
                                                </p>
                                            @endif
                                        </div>

                                        {{-- === Kontrol Jumlah (User Biasa) === --}}
                                        @if(!Auth::user()->is_admin)
                                            <form action="{{ route('cart.update', $item) }}" method="POST"
                                                class="flex items-center gap-2 sm:gap-3 justify-center">
                                                @csrf
                                                @method('PATCH')

                                                <button type="button"
                                                    onclick="let input=this.closest('form').querySelector('input[name=quantity]');let val=parseInt(input.value);if(val>1){input.value=val-1;input.form.submit();}"
                                                    class="w-8 h-8 sm:w-9 sm:h-9 bg-red-600 text-white font-bold rounded-full flex items-center justify-center hover:bg-red-700 transition"
                                                    aria-label="Kurangi jumlah">−</button>

                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                    onchange="this.form.submit()"
                                                    class="w-14 sm:w-16 px-2 sm:px-3 py-1 text-center text-sm border border-gray-300 rounded-lg bg-white text-black focus:outline-none focus:ring focus:ring-indigo-300" />

                                                <button type="button"
                                                    onclick="let input=this.closest('form').querySelector('input[name=quantity]');input.value=parseInt(input.value)+1;input.form.submit();"
                                                    class="w-8 h-8 sm:w-9 sm:h-9 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center hover:bg-blue-700 transition"
                                                    aria-label="Tambah jumlah">+</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- === Total dan Checkout === --}}
                        @if(!Auth::user()->is_admin)
                            @php
                                $total = $items->reduce(fn($carry, $item) => $carry + ($item->product->harga * $item->quantity), 0);
                            @endphp

                            <div class="mt-5 text-right text-sm sm:text-base font-semibold text-gray-900 pt-4 border-t border-gray-200">
                                Total: <span class="text-gray-800 font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <form action="{{ route('cart.checkout') }}" method="POST" class="mt-4 space-y-4 sm:space-y-5">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800">Nama Pelanggan</label>
                                        <input type="text" name="nama_pelanggan"
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Opsional">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800">Nomor Pelanggan</label>
                                        <input type="text" name="nomor_pelanggan"
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Opsional">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-800">Metode Pembayaran</label>
                                        <select id="metode_pembayaran" name="metode_pembayaran" required
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="cash">Cash</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                    </div>

                                    <div id="jumlahBayarWrapper">
                                        <label class="block text-sm font-semibold text-gray-800">Jumlah Bayar</label>
                                        <input type="number" step="0.01" name="jumlah_bayar" required
                                            class="mt-1 block w-full rounded-md bg-gray-100 text-black border-gray-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            placeholder="Contoh: 50000">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit"
                                        class="inline-flex items-center px-5 py-2.5 bg-green-700 border border-transparent rounded-lg font-semibold text-white hover:bg-green-800 shadow-md transition ease-in-out duration-150">
                                        <i class="fa-solid fa-print mr-2"></i> Cetak Struk & Simpan
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endif

                    {{-- === POPUP QRIS === --}}
                    <div id="qrisModal"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm">
                        <div class="bg-white rounded-xl p-6 w-11/12 sm:w-96 text-center relative shadow-2xl">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Bayar dengan QRIS</h3>
                            <img src="{{ asset('images/qris.png') }}" alt="QRIS"
                                class="mx-auto mb-6 w-64 h-64 object-contain rounded-lg border border-gray-200">

                            <div class="flex justify-around gap-4">
                                <button id="cancelQris"
                                    class="px-5 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Batal</button>
                                <button id="confirmQris"
                                    class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Cetak
                                    Struk & Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === STYLE PRINT === --}}
    <style>
        .hidden-print {
            visibility: hidden;
            position: absolute;
            z-index: -9999;
        }

        @media print {
            body *:not(#print-area):not(#print-area *) {
                visibility: hidden !important;
            }
        }

        #print-area,
        #print-area * {
            visibility: visible !important;
        }

        #print-area {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
            padding: 20px;
            background: #fff;
            font-family: monospace;
            font-size: 18px;
            line-height: 1.6;
            border: 1px solid #000;
        }

        #print-area h2 {
            font-size: 22px;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }

        #print-area table {
            width: 100%;
            border-collapse: collapse;
        }

        #print-area hr {
            border: none;
            border-top: 2px dashed #000;
            margin: 12px 0;
        }

        @page {
            margin: 0;
        }
    </style>

    {{-- === AREA CETAK STRUK === --}}
    @if(session('print_transaksi'))
        <div id="print-area" class="hidden-print">
            <div style="text-align: center; margin-bottom: 10px;">
                <h2>Warung Golpal</h2>
                <small style="font-size: 16px;">{{ session('print_transaksi')->tanggal }}</small>
            </div>

            <p style="margin-bottom: 12px;">
                <strong>No. Invoice:</strong> {{ session('print_transaksi')->no_invoice ?? '-' }}<br>
                <strong>Pelanggan:</strong> {{ session('print_transaksi')->nama_pelanggan ?? '-' }}<br>
                <strong>Kasir:</strong> {{ session('print_transaksi')->nama_user }}<br>
                <strong>Pembayaran:</strong> {{ strtoupper(session('print_transaksi')->metode_pembayaran) }}
            </p>

            <table style="margin-bottom: 12px;">
                @foreach(session('print_transaksi')->details as $item)
                    <tr>
                        <td colspan="2" style="border-bottom: 1px dashed #000; padding-bottom: 3px;">
                            <strong style="text-transform: uppercase;">{{ $item->nama_product }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 60%;">{{ $item->jumlah }} x Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td style="width: 40%; text-align: right;">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>

            <hr>

            <table style="font-weight: bold;">
                <tr>
                    <td>Total</td>
                    <td style="text-align: right;">Rp{{ number_format(session('print_transaksi')->total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td style="text-align: right;">
                        Rp{{ number_format(session('print_transaksi')->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td style="text-align: right;">Rp{{ number_format(session('print_transaksi')->kembalian, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            <p style="text-align: center; margin-top: 25px; text-transform: uppercase;">*** Terima kasih ***<br>Warung
                Golpal</p>
        </div>
    @endif

    {{-- === SCRIPT INTERAKSI === --}}
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const checkoutButton = document.querySelector('form button[type="submit"]');
    const checkoutForm = checkoutButton ? checkoutButton.closest('form') : null;
    const qrisModal = document.getElementById('qrisModal');
    const cancelQris = document.getElementById('cancelQris');
    const confirmQris = document.getElementById('confirmQris');
    const metodeSelect = document.getElementById('metode_pembayaran');

    if (checkoutButton) {
        checkoutButton.addEventListener('click', function (e) {
            if (metodeSelect.value === 'qris') {
                e.preventDefault();
                qrisModal.classList.remove('hidden');
            }
        });
    }

    cancelQris.addEventListener('click', function () {
        qrisModal.classList.add('hidden');
    });

    confirmQris.addEventListener('click', function () {
        qrisModal.classList.add('hidden');
        if (checkoutForm) checkoutForm.submit();
    });

    const printArea = document.getElementById('print-area');
    if (printArea) {
        printArea.classList.remove('hidden-print');
        window.print();
        printArea.classList.add('hidden-print');

        fetch('{{ route('clear.print.session') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    }
});

function openDeleteModal(id) {
    const modal = document.getElementById('deleteModal' + id);
    if (modal) modal.classList.remove('hidden');
}

function closeDeleteModal(id) {
    const modal = document.getElementById('deleteModal' + id);
    if (modal) modal.classList.add('hidden');
}
    </script>
</x-app-layout>
