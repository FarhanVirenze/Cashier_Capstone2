<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="shadow-md rounded-lg overflow-hidden border border-white/10" style="background-image: url('{{ asset('images/card1.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;">
                <div class="p-6 text-gray-800 dark:text-gray-100 space-y-6">

                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @csrf

                        {{-- Nama Product --}}
                        <div>
                            <x-input-label for="nama" value="Nama Produk"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Produk"
                                class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-3 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        {{-- Barcode --}}
                        <div>
                            <x-input-label for="barcode" value="Barcode"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <input type="text" name="barcode" id="barcode" placeholder="Masukkan Barcode"
                                class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-3 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                          {{-- Modal / Harga Beli --}}
                        <div>
                            <x-input-label for="modal" value="Modal / Harga Beli" class="text-sm text-gray-700 dark:text-gray-200" />
                            <input type="number" name="modal" id="modal" placeholder="Masukkan Modal Produk"
                                class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-3 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required min="0" step="0.01">
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <x-input-label for="harga" value="Harga Jual" class="text-sm text-gray-700 dark:text-gray-200" />
                            <input type="number" name="harga" id="harga" placeholder="Masukkan Harga Jual"
                                class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-3 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required min="0" step="0.01">
                        </div>

                        {{-- Stok --}}
                        <div>
                            <x-input-label for="stok" value="Stok" class="text-sm text-gray-700 dark:text-gray-200" />
                            <input type="number" name="stok" id="stok" placeholder="Masukkan Jumlah Stok"
                                class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-3 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required min="0">
                        </div>

                        {{-- Foto Product --}}
                        <div>
                            <x-input-label for="foto" value="Foto Product"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <input type="file" name="foto" id="foto" accept="image/*"
                                class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-3 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="col-span-full flex justify-end gap-2 pt-2">
                            <x-produk-button>
                                Tambah Produk
                            </x-produk-button>
                            <x-cancel-button href="{{ route('product.index') }}" class="text-sm" />
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
