<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-6 text-gray-900 dark:text-gray-100">
         <div class="max-w-[97%] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="overflow-hidden bg-gradient-to-br from-blue-50 via-white to-blue-100 shadow-lg border border-blue-200 sm:rounded-xl">
                <div class="px-6 pt-6 w-full mr-3 sm:w-2/3 md:w-1/2 lg:w-1/3">

                    {{-- 🔍 Hasil Pencarian --}}
                    @if (request('search'))
                        <div class="bg-blue-600 text-white text-sm font-semibold rounded-md px-4 py-2 mb-4 shadow-sm">
                            Hasil pencarian untuk:
                            <strong class="font-bold">{{ request('search') }}</strong>
                        </div>
                    @endif

                    {{-- 🔎 Form Pencarian --}}
                    <form method="GET" action="{{ route('product.index') }}" class="flex items-center mb-4 gap-2">
                        <x-text-input id="search" name="search" type="text"
                            class="w-full text-sm py-2 px-3 text-gray-900 bg-white border border-blue-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm"
                            placeholder="Cari berdasarkan nama produk..." value="{{ request('search') }}" autofocus />

                        <x-search-button type="submit"
                            class="text-sm px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow-md transition-all duration-200">
                            {{ __('Search') }}
                        </x-search-button>

                        @if (request('search'))
                            <a href="{{ route('product.index') }}"
                                class="text-sm px-4 py-2 rounded-md bg-gradient-to-br from-amber-500 to-amber-400 text-white font-medium shadow-md hover:scale-105 transition-all duration-300">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                            {{-- 🌟 Mobile Only --}}
                            <div class="product-container mb-2 block md:hidden">
                                <div class="flex justify-between items-center px-3 pt-2 pb-1">
                                    <h2 class="text-lg font-bold text-gray-800">Daftar Produk</h2>
                                    <a href="{{ route('product.create') }}"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 text-xs transition-all duration-200">
                                        + Tambah Produk
                                    </a>
                                </div>
                            </div>

                            {{-- 💻 Desktop Only --}}
                            <div class="product-container mb-4 hidden md:block">
                                <h2 class="text-base font-bold mb-3 ml-4 mt-6 text-blue-800 border-b pb-2">Daftar Produk</h2>

                                {{-- ➕ Form Tambah Produk --}}
                                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data"
                                    class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-4 px-4">
                                    @csrf
                                    <div>
                                        <label for="nama" class="text-xs font-medium text-gray-700">Nama Produk</label>
                                        <input type="text" name="nama" placeholder="Masukkan Nama Produk"
                                            class="p-2 border border-blue-200 rounded-md w-full text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="barcode" class="text-xs font-medium text-gray-700">Barcode</label>
                                        <input type="text" name="barcode" placeholder="Masukkan Barcode"
                                            class="p-2 border border-blue-200 rounded-md w-full text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="modal" class="text-xs font-medium text-gray-700">Modal</label>
                                        <input type="number" name="modal" placeholder="Masukkan Modal"
                                            class="p-2 border border-blue-200 rounded-md w-full text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="harga" class="text-xs font-medium text-gray-700">Harga Jual</label>
                                        <input type="number" name="harga" placeholder="Masukkan Harga Jual"
                                            class="p-2 border border-blue-200 rounded-md w-full text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="stok" class="text-xs font-medium text-gray-700">Stok</label>
                                        <input type="number" name="stok" placeholder="Masukkan Jumlah Stok"
                                            class="p-2 border border-blue-200 rounded-md w-full text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-200"
                                            required>
                                    </div>
                                    <div>
                                        <label for="foto" class="text-xs font-medium text-gray-700">Foto Produk</label>
                                        <input type="file" name="foto" accept="image/*"
                                            class="p-2 border border-blue-200 rounded-md w-full text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition-all duration-200"
                                            required>
                                    </div>
                                    <div class="col-span-full flex justify-end">
                                        <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-600 text-white mt-3 px-4 py-2 rounded-md shadow-md hover:shadow-lg transition-all duration-300 text-sm">
                                            Tambah Produk
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- ✅ Notifikasi --}}
                            @if (session('success'))
                                <p x-data="{ show: true }" x-show="show" x-transition
                                    x-init="setTimeout(() => show = false, 4000)"
                                    class="pb-2 ml-4 text-sm font-semibold text-green-700">
                                    {{ session('success') }}
                                </p>
                            @endif

                            {{-- ❌ Notifikasi error validasi --}}
@if ($errors->any())
    <div x-data="{ show: true }"
         x-show="show"
         x-transition
         x-init="setTimeout(() => show = false, 4000)" 
         class="mb-4 mx-4 p-4 bg-red-50 border border-red-400 text-red-700 rounded-lg shadow-sm text-sm">
        <strong class="font-semibold">Terjadi Kesalahan!</strong>
        <ul class="mt-2 list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                            {{-- 📊 Tabel Produk (Desktop Only) --}}
<div class="hidden md:block overflow-x-auto bg-white shadow-md rounded-xl mb-4 border border-blue-100">
    <table class="w-full text-sm text-left text-gray-900">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gradient-to-r from-blue-100 to-blue-200 border-b border-blue-300">
                                        <tr>
                                            <th class="px-4 py-2">No</th>
                                            <th class="px-4 py-2">Foto</th>
                                            <th class="px-4 py-2">Nama</th>
                                            <th class="px-4 py-2">Barcode</th>
                                            <th class="px-4 py-2">Modal</th>
                                            <th class="px-4 py-2">Harga Jual</th>
                                            <th class="px-4 py-2">Stok</th>
                                            <th class="px-4 py-2 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($product as $index => $item)
                                            <tr
                                                class="odd:bg-white even:bg-blue-50 hover:bg-blue-100 transition-all duration-150 border-b border-blue-100">
                                                <td class="px-4 py-2">{{ $product->firstItem() + $loop->index }}</td>
                                                <td class="px-4 py-2">
                                                    <img src="{{ asset($item->foto) }}" alt="Product"
                                                        class="w-10 h-10 object-cover rounded-md shadow-sm">
                                                </td>
                                                <td class="px-4 py-2 font-medium text-gray-800">{{ $item->nama }}</td>
                                                <td class="px-4 py-2 text-gray-700">{{ $item->barcode }}</td>
                                                <td class="px-4 py-2 text-gray-700">
                        Rp{{ number_format($item->modal, 0, ',', '.') }}
                    </td>
                                                <td class="px-4 py-2 font-semibold text-blue-700">
                                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-2 text-gray-700">{{ $item->stok }}</td>
                                                <td class="px-4 py-2 text-center">
                                                    <div class="flex justify-center space-x-2">
                                                        <button data-modal-target="editModal{{ $item->id }}"
                                                            class="text-blue-600 font-semibold hover:underline">Edit</button>
                                                        <button data-modal-target="deleteModal{{ $item->id }}"
                                                            class="text-red-600 font-semibold hover:underline">Hapus</button>
                                                        <button onclick="printBarcode('{{ $item->barcode }}')"
                                                            class="text-green-600 font-semibold hover:underline">Cetak</button>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- ✏️ Edit Modal --}}
                                            <div id="editModal{{ $item->id }}"
                                                class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm">
                                                <div
                                                    class="modal-content bg-white p-6 rounded-xl w-96 shadow-lg border border-blue-200">
                                                    <h3 class="text-lg font-bold text-blue-700 mb-3">Edit Produk</h3>
                                                    <form action="{{ route('product.update', $item->id) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-2">
                                                            <label class="text-xs font-medium text-gray-700">Nama</label>
                                                            <input type="text" name="nama" value="{{ $item->nama }}"
                                                                class="w-full p-2 border border-blue-200 rounded-md text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="text-xs font-medium text-gray-700">Barcode</label>
                                                            <input type="text" name="barcode" value="{{ $item->barcode }}"
                                                                class="w-full p-2 border border-blue-200 rounded-md text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                                                        </div>

                                                        <div class="mb-2">
                                <label class="text-xs font-medium text-gray-700">Modal</label>
                                <input type="number" name="modal" value="{{ $item->modal }}"
                                    class="w-full p-2 border border-blue-200 rounded-md text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                            </div>

                                                        <div class="mb-2">
                                                            <label class="text-xs font-medium text-gray-700">Harga Jual</label>
                                                            <input type="number" name="harga" value="{{ $item->harga }}"
                                                                class="w-full p-2 border border-blue-200 rounded-md text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="text-xs font-medium text-gray-700">Stok</label>
                                                            <input type="number" name="stok" value="{{ $item->stok }}"
                                                                class="w-full p-2 border border-blue-200 rounded-md text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="text-xs font-medium text-gray-700">Foto Baru</label>
                                                            <input type="file" name="foto"
                                                                class="w-full p-2 border border-blue-200 rounded-md text-xs text-black focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
                                                        </div>

                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button"
                                                                class="bg-gray-300 text-gray-800 px-3 py-1 rounded-md text-xs"
                                                                data-modal-hide="editModal{{ $item->id }}">Batal</button>
                                                            <button type="submit"
                                                                class="bg-blue-600 text-white px-3 py-1 rounded-md text-xs hover:bg-blue-700">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- 🗑️ Delete Modal --}}
                                            <div id="deleteModal{{ $item->id }}"
                                                class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm">
                                                <div class="modal-content bg-white p-5 rounded-xl w-80 border border-blue-200 shadow-lg">
                                                    <h3 class="text-sm font-bold text-blue-700 mb-1">Konfirmasi Hapus</h3>
                                                    <p class="text-xs text-gray-700 mb-3">Apakah Anda yakin ingin menghapus produk ini?</p>
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button"
                                                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-md text-xs"
                                                            data-modal-hide="deleteModal{{ $item->id }}">Batal</button>
                                                        <form action="{{ route('product.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-600 text-white px-3 py-1 rounded-md text-xs hover:bg-red-700">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-3 text-gray-500 font-medium">
                                                    Tidak ada produk.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Card Product (Mobile) --}}
                            <div class="block md:hidden space-y-3 px-3">
                                @forelse ($product as $item)
                                                        <div class="p-2 rounded-lg shadow border text-sm"
                                                            style="background-image: url('{{ asset('images/card1.png') }}'); 
                                                                                                                                    background-size: cover; 
                                                                                                                                    background-position: center;">
                                                            <div class="flex items-center space-x-2">
                                            <img src="{{ $item->foto }}" alt="Product" class="w-8 h-8 object-cover rounded-sm">
                                            <div>
                                                <h4 class="font-semibold text-white dark:text-white">{{ $item->nama }}</h4>
                                                <p class="font-semibold text-white dark:text-white">Barcode: {{ $item->barcode }}</p>
                                                <p class="font-semibold text-white dark:text-white">Modal: Rp {{ number_format($item->modal, 0, ',', '.') }}</p>
                                                <p class="font-semibold text-white dark:text-white">Harga Jual: Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                                <p class="font-semibold text-white dark:text-white">Stok: {{ $item->stok }}</p>
                                            </div>
                                                            </div>
                                                            <div class="flex justify-end mt-2 space-x-2">
                                                                <button data-modal-target="edittModal{{ $item->id }}"
                                                                    class="text-blue-700 bg-gray-200 px-2 py-1 rounded text-xs font-semibold hover:bg-gray-300 transition-colors duration-200">
                                                                    Edit
                                                                </button>
                                                                <button data-modal-target="deleteeModal{{ $item->id }}"
                                                                    class="text-red-700 bg-gray-200 px-2 py-1 rounded text-xs font-semibold hover:bg-gray-300 transition-colors duration-200">
                                                                    Hapus
                                                                </button>
                                                                <button onclick="printBarcode('{{ $item->barcode }}')"
                                                                    class="text-green-700 bg-gray-200 px-2 py-1 rounded text-xs font-semibold hover:bg-gray-300 transition-colors duration-200">
                                                                    Cetak Barcode
                                                                </button>
                                                            </div>

                                                            {{-- Modal Edit --}}
                                                            <div id="edittModal{{ $item->id }}"
                                                                class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
                                                                <div class="modal-content relative w-11/12 sm:w-2/3 lg:w-1/2 rounded-lg shadow-2xl border border-white/20 
                                        p-6 text-white" style="background-image: url('{{ asset('images/card1.png') }}');
                                       background-size: cover;
                                       background-position: center;
                                       background-repeat: no-repeat;">

                                                                    <!-- Header -->
                                                                    <h3 class="text-lg font-semibold text-white text-center mb-4 drop-shadow">
                                                                        Edit Produk
                                                                    </h3>

                                                                    <form action="{{ route('product.update', $item->id) }}" method="POST"
                                                                        enctype="multipart/form-data" class="space-y-3">
                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div>
                                                                            <label for="name"
                                                                                class="block text-sm font-medium text-white mb-1">Nama
                                                                                Produk</label>
                                                                            <input type="text" name="nama" id="name" value="{{ $item->nama }}"
                                                                                class="w-full p-2 rounded-md text-gray-800 bg-white/90 border border-gray-300 focus:ring-2 
                                                   focus:ring-blue-400 focus:outline-none text-sm" required>
                                                                        </div>

                                                                        <div>
                                                                            <label for="barcode"
                                                                                class="block text-sm font-medium text-white mb-1">Barcode</label>
                                                                            <input type="text" name="barcode" id="barcode"
                                                                                value="{{ $item->barcode }}" class="w-full p-2 rounded-md text-gray-800 bg-white/90 border border-gray-300 focus:ring-2 
                                                   focus:ring-blue-400 focus:outline-none text-sm" required>
                                                                        </div>

                                                                         <div>
                                                                            <label for="modal"
                                                                                class="block text-sm font-medium text-white mb-1">Modal</label>
                                                                            <input type="number" name="modal" id="modal"
                                                                                value="{{ $item->modal }}" class="w-full p-2 rounded-md text-gray-800 bg-white/90 border border-gray-300 focus:ring-2 
                                                   focus:ring-blue-400 focus:outline-none text-sm" required>
                                                                        </div>

                                                                        <div>
                                                                            <label for="harga"
                                                                                class="block text-sm font-medium text-white mb-1">Harga Jual</label>
                                                                            <input type="number" name="harga" id="harga"
                                                                                value="{{ $item->harga }}" class="w-full p-2 rounded-md text-gray-800 bg-white/90 border border-gray-300 focus:ring-2 
                                                   focus:ring-blue-400 focus:outline-none text-sm" required>
                                                                        </div>

                                                                        <div>
                                                                            <label for="stok"
                                                                                class="block text-sm font-medium text-white mb-1">Stok</label>
                                                                            <input type="number" name="stok" id="stok" value="{{ $item->stok }}"
                                                                                class="w-full p-2 rounded-md text-gray-800 bg-white/90 border border-gray-300 focus:ring-2 
                                                   focus:ring-blue-400 focus:outline-none text-sm" required>
                                                                        </div>

                                                                        <div>
                                                                            <label for="foto"
                                                                                class="block text-sm font-medium text-white mb-1">Foto Baru
                                                                                (Opsional)</label>
                                                                            <input type="file" name="foto" id="foto" class="w-full p-2 rounded-md text-gray-800 bg-white/90 border border-gray-300 focus:ring-2 
                                                   focus:ring-blue-400 focus:outline-none text-sm">
                                                                        </div>

                                                                        <!-- Tombol Aksi -->
                                                                        <div class="flex justify-end space-x-3 pt-2">
                                                                            <button type="button" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-400 
                                                   hover:shadow-md transition-all duration-300" data-modal-hide="edittModal{{ $item->id }}">
                                                                                Batal
                                                                            </button>

                                                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 
                                                   hover:shadow-md transition-all duration-300">
                                                                                Simpan Perubahan
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            {{-- Modal Hapus --}}
                                                            <div id="deleteeModal{{ $item->id }}"
                                                                class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm">

                                                                <div class="modal-content p-4 rounded-md w-11/12 sm:w-1/2 shadow-xl border border-blue-200"
                                                                    style="background-image: url('{{ asset('images/card1.png') }}');
                                                            background-size: cover;
                                                            background-position: center;">

                                                                    <h3 class="text-sm font-semibold text-white mb-2">Konfirmasi Penghapusan
                                                                    </h3>
                                                                    <p class="text-xs text-gray-100 mb-3">
                                                                        Apakah Anda yakin ingin menghapus produk ini?
                                                                    </p>

                                                                    <div class="flex justify-end space-x-2">
                                                                        <button type="button" class="bg-gray-300 text-gray-800 px-3 py-1 rounded-sm text-xs 
                                                                   hover:bg-gray-400 transition-all duration-300"
                                                                            data-modal-hide="deleteeModal{{ $item->id }}">
                                                                            Batal
                                                                        </button>

                                                                        <form action="{{ route('product.destroy', $item->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-sm text-xs 
                                                                       hover:bg-red-700 hover:shadow-md transition-all duration-300">
                                                                                Hapus
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                @empty
                                    <p class="text-center text-xs text-black dark:text-black">Tidak ada product.</p>
                                @endforelse
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-1">
                                {{ $product->links('vendor.pagination.tailwind') }}
                            </div>

                        </div>
                    </div>
                </div>

                <style>
                    @media print {
                        body * {
                            visibility: hidden;
                        }

                        #print-area,
                        #print-area * {
                            visibility: visible;
                        }

                        #print-area {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                        }
                    }
                </style>
                {{-- Modal Script --}}
                <script>
                    document.querySelectorAll('[data-modal-target]').forEach(button => {
                        button.addEventListener('click', function () {
                            const modalId = this.getAttribute('data-modal-target');
                            const modal = document.getElementById(modalId);
                            if (modal) {
                                modal.classList.remove('hidden');
                                modal.classList.add('block');
                            }
                        });
                    });

                    document.querySelectorAll('[data-modal-hide]').forEach(button => {
                        button.addEventListener('click', function () {
                            const modalId = this.getAttribute('data-modal-hide');
                            const modal = document.getElementById(modalId);
                            if (modal) {
                                modal.classList.add('hidden');
                                modal.classList.remove('block');
                            }
                        });
                    });
                </script>

                {{-- Barcode Generator --}}
                <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

                {{-- Canvas Tempat Barcode --}}
                <canvas id="barcode-canvas" style="display: none;"></canvas>

                {{-- JavaScript --}}
                <script>
                    const barcodeRef = document.getElementById('barcode-canvas');

                    const generateBarcode = (barcodeValue) => {
                        let formatType;

                        if (barcodeValue.length === 12) {
                            formatType = "UPC";
                        } else if (barcodeValue.length === 13) {
                            formatType = "EAN13";
                        } else {
                            formatType = "CODE128";
                        }

                        JsBarcode(barcodeRef, barcodeValue, {
                            format: formatType,
                            width: 2,
                            height: 40,
                            displayValue: true,
                        });
                    };

                    const printBarcode = (barcodeValue) => {
                        generateBarcode(barcodeValue);

                        const imgData = barcodeRef.toDataURL("image/png");
                        const printArea = document.createElement("div");
                        printArea.id = "print-area";
                        printArea.style.top = "0";
                        printArea.style.left = "0";
                        printArea.style.width = "100%";
                        printArea.style.height = "100%";
                        printArea.style.display = "flex";
                        printArea.style.justifyContent = "center";
                        printArea.style.alignItems = "center";

                        const img = new Image();
                        img.src = imgData;
                        img.style.height = "100px";

                        img.onload = () => {
                            printArea.appendChild(img);
                            document.body.appendChild(printArea);

                            const style = document.createElement("style");
                            style.innerHTML = `
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    #print-area, #print-area * {
                        visibility: visible;
                    }
                    #print-area {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                    }
                }
            `;
                            document.head.appendChild(style);

                            window.print();

                            // Cleanup setelah print
                            document.body.removeChild(printArea);
                            document.head.removeChild(style);
                        };
                    };
                </script>
</x-app-layout>