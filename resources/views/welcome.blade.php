<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Warung Golpal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    @vite('resources/css/app.css')

    <style>
        /* Animasi halus saat scroll */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [data-animate] {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease-out;
        }

        [data-animate].animated {
            opacity: 1;
            transform: translateY(0);
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-b from-white to-blue-50 text-gray-800">

    <!-- Navbar -->
    <header
        class="w-full bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 text-white shadow-lg fixed top-0 left-0 z-50 backdrop-blur-md bg-opacity-90">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-extrabold tracking-wide hover:scale-105 transition-transform duration-300">
                Warung Golpal
            </h1>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3 text-sm font-medium">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-5 py-2 bg-white text-blue-700 rounded-full hover:bg-blue-100 font-semibold shadow-md transition-all duration-300">
                            Login
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-grow flex flex-col justify-center items-center text-center px-6 pt-32 pb-6">
        <div class="max-w-3xl" data-animate>
            <h2
                class="text-5xl sm:text-6xl font-extrabold mb-6 bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent leading-tight">
                Sistem Kasir <br class="hidden sm:block" /> Warung Golpal
            </h2>
            <p class="text-gray-800 text-lg sm:text-xl mb-10 leading-relaxed">
                Kelola transaksi, cetak struk, pemindaian barcode kamera, laporan penjualan, dan manajemen produk.
                Dirancang untuk membantu digitalisasi usaha kecil secara mudah & efisien.
            </p>
            <a href="#fitur"
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                Jelajahi Fitur
            </a>
        </div>
    </main>

    <!-- Feature Section -->
    <section id="fitur" class="w-full bg-white py-10">
        <div class="max-w-6xl mx-auto grid sm:grid-cols-2 lg:grid-cols-3 gap-10 px-6">

            <div class="relative p-8 border border-blue-200 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500 text-center"
                data-animate style="background-image: url('{{ asset('images/card1.png') }}'); 
            background-size: cover; 
            background-position: center;">
                <div class="text-white text-5xl mb-4">
                    <i class="fas fa-barcode"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Pemindaian Barcode</h3>
                <p class="text-white text-sm leading-relaxed">
                    Scan produk langsung dengan kamera smartphone tanpa alat tambahan, cepat dan praktis.
                </p>
            </div>

            <div class="relative p-8 border border-blue-200 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500 text-center"
                data-animate style="background-image: url('{{ asset('images/card1.png') }}'); 
            background-size: cover; 
            background-position: center;">
                <div class="text-white text-5xl mb-4">
                    <i class="fas fa-print"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Cetak Struk</h3>
                <p class="text-white text-sm leading-relaxed">
                    Langsung cetak bukti transaksi dari browser, mendukung printer thermal secara instan.
                </p>
            </div>

            <div class="relative p-8 border border-blue-200 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-500 text-center"
                data-animate style="background-image: url('{{ asset('images/card1.png') }}'); 
            background-size: cover; 
            background-position: center;">
                <div class="text-white text-5xl mb-4">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Pembayaran QRIS</h3>
                <p class="text-white text-sm leading-relaxed">
                    Terima pembayaran digital dengan QRIS langsung dari sistem kasir tanpa repot.
                </p>
            </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 text-white py-6 mt-auto">
        <div class="max-w-6xl mx-auto text-center text-sm">
            &copy; {{ date('Y') }} <span class="font-semibold">Warung Golpal</span>. All rights reserved.
        </div>
    </footer>

    <script>
        // Animasi scroll saat elemen muncul
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
    </script>

</body>

</html>