<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="shadow-md rounded-lg overflow-hidden border border-white/10"
                style="background-image: url('{{ asset('images/card1.png') }}');
                       background-size: cover;
                       background-position: center;
                       background-repeat: no-repeat;">
                <div class="p-6 text-gray-800 dark:text-gray-100 space-y-6">

                    {{-- ❌ Notifikasi Error Validasi --}}
                    @if ($errors->any())
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                            class="mb-4 p-4 rounded-lg bg-red-50 border border-red-300 text-red-700">
                            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @csrf

                        {{-- Nama Product --}}
                        <div class="relative">
                            <x-input-label for="nama" value="Nama Produk"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <!-- Icon Product / Box -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7l9-4 9 4v10l-9 4-9-4V7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7l9 4 9-4M12 11v10" />
                                    </svg>
                                </div>
                                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Produk"
                                    class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-10 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>

                        {{-- Barcode Input with Icon --}}
                        <div class="relative">
                            <x-input-label for="barcode" value="Barcode"
                                class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-1.5" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                                <input type="text" name="barcode" id="barcode"
                                    placeholder="Scan atau masukkan barcode" maxlength="13" pattern="\d{12,13}" required
                                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-600
                   bg-white dark:bg-white
                   text-gray-900
                   text-sm
                   pl-10 pr-12 py-2.5
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                   placeholder:text-gray-400
                   transition-colors duration-200" />
                                <button type="button" id="scanBarcodeBtn"
                                    class="absolute inset-y-0 right-0 flex items-center justify-center w-11 rounded-r-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </button>
                            </div>
                            <p id="barcodeError" class="hidden text-sm text-red-600 mt-1.5 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="errorMessage"></span>
                            </p>
                        </div>

                        {{-- Modal / Harga Beli --}}
                        <div class="relative">
                            <x-input-label for="modal" value="Modal / Harga Beli"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z" />
                                    </svg>
                                </div>
                                <input type="number" name="modal" id="modal" placeholder="Masukkan Modal Produk"
                                    class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-10 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required min="0" step="0.01">
                            </div>
                        </div>

                        {{-- Harga Jual --}}
                        <div class="relative">
                            <x-input-label for="harga" value="Harga Jual"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.333 0-4 1-4 4s2.667 4 4 4 4-1 4-4-2.667-4-4-4z" />
                                    </svg>
                                </div>
                                <input type="number" name="harga" id="harga"
                                    placeholder="Masukkan Harga Jual"
                                    class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-10 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required min="0" step="0.01">
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div class="relative">
                            <x-input-label for="stok" value="Stok"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <input type="number" name="stok" id="stok"
                                    placeholder="Masukkan Jumlah Stok"
                                    class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-10 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required min="0">
                            </div>
                        </div>

                        {{-- Foto Product --}}
                        <div class="relative">
                            <x-input-label for="foto" value="Foto Product"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7h4l3-3h4l3 3h4v13H3V7z" />
                                    </svg>
                                </div>
                                <input type="file" name="foto" id="foto" accept="image/*"
                                    class="mt-1 block w-full border border-white dark:border-white rounded-md text-sm px-10 py-2 bg-white dark:bg-white text-black dark:text-black focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="col-span-full flex justify-end gap-2 pt-2">
                            <x-produk-button>Tambah Produk</x-produk-button>
                            <x-cancel-button href="{{ route('product.index') }}" class="text-sm" />
                        </div>
                    </form>

                    {{-- Modal Scanner --}}
                    <div id="scannerContainer"
                        class="fixed inset-0 bg-black/80 flex justify-center items-center hidden z-50">
                        <div
                            class="bg-white rounded-xl overflow-hidden relative shadow-2xl p-4 w-[400px] md:w-[500px] lg:w-[600px]">
                            <button id="closeScanner"
                                class="absolute top-2 right-2 text-red-600 font-bold text-lg hover:text-red-800 transition">✕</button>
                            <video id="scannerPreview" class="w-full h-72 rounded-md bg-gray-200"></video>
                            <p class="text-sm text-gray-600 mt-2 text-center">Arahkan kamera ke barcode produk</p>
                        </div>
                    </div>

                    {{-- Audio Scan --}}
                    <audio id="scanSound" src="{{ asset('sound/scan.mp3') }}"></audio>

                </div>
            </div>
        </div>
    </div>

    {{-- ZXing Barcode Scanner --}}
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const codeReader = new ZXing.BrowserBarcodeReader();
        const scanBtn = document.getElementById('scanBarcodeBtn');
        const scannerContainer = document.getElementById('scannerContainer');
        const scannerPreview = document.getElementById('scannerPreview');
        const closeScanner = document.getElementById('closeScanner');
        const barcodeInput = document.getElementById('barcode');
        const barcodeError = document.getElementById('barcodeError');
        const scanSound = document.getElementById('scanSound');

        function validateBarcode(code) {
            if (!/^\d{12,13}$/.test(code)) {
                return 'Barcode harus 12 atau 13 digit dan bukan QR code.';
            }
            return '';
        }

        scanBtn.addEventListener('click', async () => {
            barcodeError.classList.add('hidden');
            scannerContainer.classList.remove('hidden');

            const constraints = {
                video: {
                    facingMode: "environment",
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    focusMode: "continuous"
                },
                audio: false
            };

            try {
                // Delay kecil supaya autofocus jalan
                await new Promise(r => setTimeout(r, 300));

                await codeReader.decodeFromConstraints(constraints, scannerPreview, (result, err) => {
                    if (result) {
                        const code = result.text;
                        const validationMessage = validateBarcode(code);
                        if (validationMessage) {
                            barcodeError.textContent = validationMessage;
                            barcodeError.classList.remove('hidden');
                            return;
                        }
                        barcodeInput.value = code;
                        scanSound.play();
                        codeReader.reset();
                        scannerContainer.classList.add('hidden');
                    }
                });
            } catch (e) {
                console.error(e);
            }
        });

        closeScanner.addEventListener('click', () => {
            codeReader.reset();
            scannerContainer.classList.add('hidden');
        });

        barcodeInput.addEventListener('input', () => {
            const validationMessage = validateBarcode(barcodeInput.value);
            if (validationMessage) {
                barcodeError.textContent = validationMessage;
                barcodeError.classList.remove('hidden');
            } else {
                barcodeError.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
