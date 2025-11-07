<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight">
            {{ __('Transaksi Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[97%] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="space-y-4">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-400 font-medium">
                        {{ session('success') }}
                    </p>
                @endif

                {{-- 📱 Mobile: Card --}}
                <div class="block md:hidden space-y-4" x-data="{ openModal: false, selectedDetailId: null, selectedDetailName: '' }">
                    @forelse ($details as $detail)
                        <div class="rounded-xl shadow-md overflow-hidden p-4 relative text-white text-sm"
                            style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">
                            
                            {{-- Overlay --}}
                            <div class="absolute inset-0 rounded-xl"></div>

                            {{-- Konten Card --}}
                            <div class="relative z-10">

                                {{-- Header Produk & Qty --}}
                                <div class="flex justify-between items-center mb-3 font-bold text-lg">
                                    <span><i class="fa fa-box mr-1"></i>{{ $detail->nama_product }}</span>
                                    <span class="text-white font-semibold">Qty: {{ $detail->jumlah }}</span>
                                </div>

                                {{-- Detail Info --}}
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    <span><i class="fa fa-tag mr-1"></i>Harga: Rp{{ number_format($detail->harga,0,',','.') }}</span>
                                    <span><i class="fa fa-calculator mr-1"></i>Total: Rp{{ number_format($detail->total,0,',','.') }}</span>
                                    <span><i class="fa fa-id-card mr-1"></i>ID Transaksi: {{ $detail->transaksi_penjualan_id }}</span>
                                    <span><i class="fa fa-barcode mr-1"></i>ID Produk: {{ $detail->product_id }}</span>
                                    <span><i class="fa fa-user mr-1"></i>Pelanggan: {{ $detail->transaksi->nama_pelanggan ?? '-' }}</span>
                                    <span><i class="fa fa-calendar-alt mr-1"></i>Tanggal: {{ \Carbon\Carbon::parse($detail->transaksi->tanggal)->format('d M Y') }}</span>
                                </div>

                                {{-- Tombol Hapus --}}
                                <div class="mt-4 flex justify-end">
                                    <button @click="openModal = true; selectedDetailId = {{ $detail->id }}; selectedDetailName = '{{ $detail->nama_product }}'"
                                        class="flex items-center gap-2 bg-red-100 text-red-700 hover:bg-red-200 font-semibold text-sm px-3 py-2 rounded-md shadow transition-all">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600 text-sm font-medium">Tidak ada data detail penjualan.</p>
                    @endforelse

                    {{-- Modal Hapus Tunggal --}}
                    <div x-show="openModal" x-cloak>
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-lg p-6 w-11/12 sm:w-80 text-center">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                    <i class="fa fa-exclamation-triangle mr-1 text-yellow-500"></i>Konfirmasi Hapus
                                </h3>
                                <p class="text-gray-600 mb-6">
                                    Apakah kamu yakin ingin menghapus <strong x-text="selectedDetailName"></strong>?
                                </p>
                                <div class="flex justify-center gap-3">
                                    <button @click="openModal = false"
                                        class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold">
                                        Batal
                                    </button>
                                    <form :action="`{{ url('detailpenjualan') }}/${selectedDetailId}`" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-semibold flex items-center gap-2">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- End Mobile Card --}}

                {{-- 💻 Desktop: Table --}}
                <div class="hidden md:block overflow-x-auto shadow-lg rounded-xl border border-blue-200">
                    <table class="w-full text-sm text-left text-gray-900">
                        <thead class="bg-gradient-to-r from-blue-100 to-blue-200 border-b border-blue-300 text-blue-800 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Produk</th>
                                <th class="px-4 py-2">Qty</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">Pelanggan</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($details as $detail)
                                <tr class="odd:bg-white even:bg-blue-50 hover:bg-blue-100 border-b border-blue-100">
                                    <td class="px-4 py-2">{{ $detail->id }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $detail->nama_product }}</td>
                                    <td class="px-4 py-2">{{ $detail->jumlah }}</td>
                                    <td class="px-4 py-2">Rp{{ number_format($detail->harga,0,',','.') }}</td>
                                    <td class="px-4 py-2">Rp{{ number_format($detail->total,0,',','.') }}</td>
                                    <td class="px-4 py-2">{{ $detail->transaksi->nama_pelanggan ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($detail->transaksi->tanggal)->format('d M Y') }}</td>
                                    <td class="px-4 py-2 text-center" x-data="{ openModal: false }">
                                        <button @click="openModal = true"
                                            class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-semibold hover:bg-red-200 transition-all">
                                            Hapus
                                        </button>

                                        {{-- Modal Desktop --}}
                                        <div x-show="openModal" x-cloak>
                                            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                                <div class="bg-white rounded-lg p-6 w-96 text-center">
                                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h3>
                                                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus <strong>{{ $detail->nama_product }}</strong>?</p>
                                                    <div class="flex justify-center gap-3">
                                                        <button @click="openModal = false"
                                                            class="px-4 py-2 bg-gray-200 rounded-md text-gray-800 hover:bg-gray-300 font-semibold">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('detailpenjualan.destroy', $detail->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500 font-medium">
                                        Tidak ada data detail penjualan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Subtotal --}}
                @if ($details->count())
                    <div class="mt-6 p-4 rounded-lg shadow text-right text-blue-800 font-semibold text-base"
                        style="background: linear-gradient(to right, #e0f2ff, #ffffff);">
                        Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
