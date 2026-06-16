<x-app-layout>
    <div
        class="relative w-screen h-[100dvh] bg-gradient-to-br from-[#0f172a] via-[#020617] to-[#020617] overflow-hidden">

        <!-- Ambient Glow -->
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-blue-600/30 rounded-full blur-[160px]"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-cyan-500/20 rounded-full blur-[140px]"></div>

        <!-- ⬅️ Back Button -->
        <a href="{{ route('pos.index') }}"
            class="absolute top-6 left-6 z-50 flex items-center gap-3
    w-auto px-6 py-3
    rounded-2xl
    bg-gradient-to-r from-blue-600 to-cyan-500
    text-white
    shadow-lg
    hover:shadow-cyan-500/50 hover:scale-105
    active:scale-95
    transition-all duration-300">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="font-semibold tracking-wide">Kembali ke POS</span>
        </a>

        <!-- 🔦 Flash Toggle -->
        <button id="flash-toggle"
            class="absolute top-6 right-6 z-50
            w-12 h-12 flex items-center justify-center
            rounded-full
            bg-gradient-to-r from-blue-600 to-cyan-500
            text-white shadow-lg
            hover:shadow-cyan-500/50 hover:scale-110
            active:scale-95
            transition-all duration-300 hidden">
            <i class="fa-solid fa-bolt text-lg"></i>
        </button>

        <!-- ================= CAMERA FULLSCREEN ================= -->
        <div class="absolute inset-0 z-10">

            <!-- VIDEO CAMERA -->
            <video id="preview" autoplay muted playsinline class="w-full h-full object-cover bg-black">
            </video>

            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/30"></div>

            <!-- Scanner Frame -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div
                    class="relative w-4/5 max-w-xl aspect-square border-3 border-blue-400/80 rounded-3xl overflow-hidden">
                    <div class="absolute -top-2 -left-2 w-8 h-8 border-t-4 border-l-4 border-blue-400 rounded-tl-lg">
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 border-t-4 border-r-4 border-blue-400 rounded-tr-lg">
                    </div>
                    <div class="absolute -bottom-2 -left-2 w-8 h-8 border-b-4 border-l-4 border-blue-400 rounded-bl-lg">
                    </div>
                    <div
                        class="absolute -bottom-2 -right-2 w-8 h-8 border-b-4 border-r-4 border-blue-400 rounded-br-lg">
                    </div>

                    <div
                        class="absolute top-0 left-0 w-full h-1
                        bg-gradient-to-r from-transparent via-blue-400 to-transparent
                        animate-scan-line">
                    </div>
                </div>
            </div>

            <!-- Scan Status -->
            <div
                class="absolute top-20 mt-2 left-6 flex items-center gap-2
                        bg-black/70 text-white px-3 py-2 rounded-full">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm font-medium">Scan Aktif</span>
            </div>
        </div>

        <!-- ================= FLOATING CART ================= -->
        <div class="fixed bottom-4 left-1/2 -translate-x-1/2 z-40
            w-[95%] sm:w-[420px] lg:w-[520px]">

            <div
                class="flex flex-col rounded-3xl
                bg-[#020617]/80 backdrop-blur-2xl
                border border-white/15 shadow-2xl overflow-hidden">

                <!-- Header -->
                <div
                    class="p-4 flex justify-between items-center
                   bg-white/5 border-b border-white/10">
                    <h3 class="text-white font-bold tracking-wide flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-cyan-400"></i>
                        Daftar Belanja
                    </h3>
                    <span id="cart-count"
                        class="px-3 py-1 rounded-full
                       bg-gradient-to-r from-blue-600 to-cyan-500
                       text-white text-sm font-bold shadow">
                        0 item
                    </span>
                </div>

                <!-- Items -->
                <div id="cart-items" class="overflow-y-auto pr-4 pb-4 pl-4 space-y-3 max-h-[84px]">
                    <div id="cart-empty" class="text-center text-white/70 text-sm py-1">
                        Belum ada barang
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-5
                   bg-white/5 border-t border-white/10">

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-white/70 text-sm">Total</span>
                        <span id="cart-total"
                            class="text-xl font-bold
                           text-transparent bg-clip-text
                           bg-gradient-to-r from-blue-400 to-cyan-400">
                            Rp 0
                        </span>
                    </div>

                    <a href="{{ route('cart.index') }}"
                        class="block w-full py-2.5 rounded-2xl text-center
                       bg-gradient-to-r from-blue-600 to-cyan-500
                       text-white font-semibold shadow-lg
                       hover:shadow-cyan-500/40 hover:scale-[1.03]
                       active:scale-95
                       transition-all duration-300">
                        <i class="fa-solid fa-wallet mr-2"></i>
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3"></div>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/js/scanner.js'])
    </div>
</x-app-layout>

<style>
    /* Scan line animation with glow effect */
    @keyframes scan-line {
        0% {
            top: 0;
            opacity: 0.3;
            box-shadow: 0 0 10px #3b82f6, 0 0 20px #3b82f6;
        }

        50% {
            opacity: 1;
            box-shadow: 0 0 20px #60a5fa, 0 0 40px #60a5fa;
        }

        100% {
            top: 100%;
            opacity: 0.3;
            box-shadow: 0 0 10px #3b82f6, 0 0 20px #3b82f6;
        }
    }

    .animate-scan-line {
        animation: scan-line 2s ease-in-out infinite;
        height: 2px;
    }

    /* Smooth toast animation */
    @keyframes slide-in-right {
        0% {
            transform: translateX(100%) scale(0.9);
            opacity: 0;
        }

        100% {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slide-in-right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    /* Custom scrollbar for cart */
    #cart-items::-webkit-scrollbar {
        width: 6px;
    }

    #cart-items::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    #cart-items::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #06b6d4);
        border-radius: 10px;
    }

    /* Camera preview glow */
    video#preview {
        filter: contrast(1.1) saturate(1.2);
        background: black;
    }
</style>

