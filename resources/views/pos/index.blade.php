<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight">
                {{ __('Point of Sale') }}
            </h2>
            <div class="flex items-center gap-4">
                <!-- Icon Scan Barcode -->
                <a href="{{ route('scanner.index') }}" class="relative hover:scale-110 transition-transform duration-200">
                    <svg class="w-7 h-7 text-gray-800 dark:text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4a1 1 0 011-1h3M17 3h3a1 1 0 011 1v3M21 20a1 1 0 01-1 1h-3M4 21a1 1 0 01-1-1v-3M7 12h.01M11 12h.01M15 12h.01" />
                    </svg>
                </a>

                <!-- Icon Keranjang -->
                <a href="{{ route('cart.index') }}" class="relative hover:scale-110 transition-transform duration-200">
                    <svg class="w-7 h-7 text-gray-800 dark:text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8h13.2L17 13M7 13h10" />
                    </svg>
                    @if ($items->count() > 0)
                        <span
                            class="absolute -top-1 -right-2 bg-red-600 text-white text-xs rounded-full px-1.5 leading-none select-none">
                            {{ $items->sum('quantity') }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 text-white">
         <div class="max-w-[97%] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="overflow-hidden bg-blue-50 dark:bg-blue-100/30 shadow-lg sm:rounded-xl border border-blue-100">
                <div class="px-6 pt-6 mb-1 pb-4 w-full sm:w-2/3 md:w-1/2 lg:w-1/3 mx-auto">

                    {{-- Notifikasi --}}
                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="pb-1 mb-4 ml-1 text-sm font-semibold text-green-700 dark:text-green-700">
                            {{ session('success') }}
                        </p>
                    @endif

                    @if (session('error'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="pb-1 mb-4 ml-1 text-xs text-red-600 dark:text-red-600">
                            {{ session('error') }}
                        </p>
                    @endif

                    @if (request('product-search'))
                        <h2
                            class="mb-4 text-sm font-semibold leading-tight bg-blue-600 border border-blue-600 rounded px-4 py-2 text-white text-center">
                            Hasil pencarian untuk: <strong>{{ request('product-search') }}</strong>
                        </h2>
                    @endif

                    {{-- Form Pencarian Produk --}}
                    <form method="GET" action="{{ route('pos.index') }}" class="flex items-center gap-3">
                        <x-text-input id="product-search" name="product-search" type="text"
                            class="w-full text-sm py-2 px-3 text-gray-800 bg-white/70 backdrop-blur-lg border border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Cari berdasarkan nama produk..."
                            value="{{ request('product-search') }}" autofocus />

                        <x-search-button type="submit"
                            class="text-xs px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-all duration-300 whitespace-nowrap">
                            {{ __('Search') }}
                        </x-search-button>

                        @if (request('product-search'))
                            <a href="{{ route('pos.index') }}"
                                class="text-xs px-3 py-2 rounded-xl bg-gradient-to-br from-amber-600 to-amber-500
                                       text-white font-medium hover:scale-105 hover:shadow-lg transition-all duration-300 whitespace-nowrap">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                {{-- === Daftar Produk === --}}
<div class="p-6 text-gray-900 dark:text-gray-100">
    <div id="product-list"
        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
        @forelse($products as $product)
            <div class="relative group rounded-xl p-3 sm:p-4 flex flex-col items-center justify-between
                        shadow-md hover:shadow-2xl hover:scale-105 transition-all duration-300
                        cursor-pointer overflow-hidden bg-cover bg-center"
                style="background-image: url('{{ asset('images/card1.png') }}');">

                {{-- Klik seluruh kartu untuk tambah ke keranjang --}}
                <form action="{{ route('pos.add') }}" method="POST" class="absolute inset-0 z-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full h-full" title="Tambah ke keranjang"></button>
                </form>

                {{-- Gambar Produk --}}
                <div class="w-full aspect-[4/3] overflow-hidden rounded-lg mb-2 sm:mb-3">
                    <img src="{{ asset($product->foto) }}" alt="{{ $product->nama }}"
                        class="w-full h-full object-cover rounded-lg group-hover:scale-110 transition-transform duration-500" />
                </div>

                {{-- Nama & Harga Produk --}}
                <div class="text-center space-y-1 w-full px-1">
                    <h5
                        class="text-xs sm:text-sm text-white font-bold truncate drop-shadow-md 
                             transition"
                        title="{{ $product->nama }}">
                        {{ $product->nama }}
                    </h5>
                    <p class="text-[11px] sm:text-xs text-white font-semibold drop-shadow-sm">
                        Rp{{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-800 dark:text-gray-800 py-10 font-medium">
                Produk tidak ditemukan.
            </p>
        @endforelse
    </div>

                    {{-- Pagination --}}
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Fitur pencarian langsung
            const searchInput = document.getElementById('product-search');
            const productCards = document.querySelectorAll('#product-list > div');

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();
                productCards.forEach(card => {
                    const name = card.querySelector('h5').textContent.toLowerCase();
                    card.style.display = name.includes(keyword) ? '' : 'none';
                });
            });

            // Submit form saat tekan Enter
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        </script>
    @endpush
</x-app-layout>
