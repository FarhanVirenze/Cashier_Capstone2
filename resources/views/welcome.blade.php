<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoScan - Solusi Kasir Digital Modern untuk UMKM</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite('resources/css/app.css')

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #0ea5e9;
            --accent: #3b82f6;
        }

        [data-animate] {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-animate].animated {
            opacity: 1;
            transform: translateY(0);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }

        .card-hover {
            transition: all 0.4s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .pulse-ring {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                opacity: 0.7;
            }

            70% {
                transform: scale(1.05);
                opacity: 0.3;
            }

            100% {
                transform: scale(0.95);
                opacity: 0.7;
            }
        }

        .service-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin: 0 auto 24px;
            font-size: 28px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-image {
            width: 100%;
            height: 200px;
            border-radius: 16px;
            object-fit: cover;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
        }

        .toast-show {
            display: flex;
            animation: toastIn 0.5s ease forwards;
        }

        .toast-hide {
            animation: toastOut 0.4s ease forwards;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col gradient-bg text-gray-800 font-instrument-sans">

    <!-- Navigation -->
    <header class="w-full bg-white/90 backdrop-blur-md shadow-lg fixed top-0 left-0 z-50 border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                    <!-- Logo Container -->
                    <div class="relative">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg overflow-hidden bg-white">
                            <img src="{{ asset('images/logo.png') }}" alt="GoScan Logo"
                                class="w-full h-full object-contain p-1"
                                onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=&quot;fas fa-barcode text-blue-600 text-lg&quot;></i>';">
                        </div>
                    </div>

                    <!-- Title & Subtitle -->
                    <div class="flex flex-col">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent">
                            GoScan
                        </h1>
                        <p class="text-xs text-gray-500 -mt-1">
                            Sistem Kasir Modern
                        </p>
                    </div>
                </a>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                            <i class="fas fa-sign-in-alt mr-2 group-hover:rotate-12 transition-transform"></i>Login
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-grow pt-28 pb-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="relative" data-animate>
                    <div class="absolute -top-6 -left-6 w-24 h-24 bg-blue-200 rounded-full opacity-30 blur-xl"></div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-blue-100 rounded-full opacity-30 blur-xl">
                    </div>

                    <div class="relative">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm mb-6">
                            <i class="fas fa-bolt mr-2 text-yellow-500"></i>Solusi Digital Terdepan
                        </span>

                        <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                            Kelola Usaha Anda Dengan
                            <span
                                class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 bg-clip-text text-transparent">
                                Sistem Kasir Modern
                            </span>
                        </h1>

                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            GoScan menyediakan <strong class="text-blue-700">layanan sistem kasir modern</strong> yang
                            lengkap dan terintegrasi.
                            Dari scan barcode, cetak struk otomatis, hingga analisis laporan real-time – semua dalam
                            satu platform.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Primary Button - Lihat Fitur -->
                            <a href="#fitur"
                                class="group relative px-8 py-3.5 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500 overflow-hidden transform hover:scale-105 text-center">
                                <div
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-blue-700 to-blue-900 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                <div class="relative flex items-center justify-center">
                                    <i
                                        class="fas fa-star mr-3 group-hover:rotate-12 transition-transform duration-500"></i>
                                    <span>Lihat Fitur</span>
                                </div>
                                <div
                                    class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-1 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full group-hover:w-3/4 transition-all duration-500">
                                </div>
                            </a>

                            <!-- Secondary Button - Lihat Layanan -->
                            <a href="#layanan"
                                class="group relative px-8 py-3.5 bg-white border-2 border-blue-600 text-blue-700 rounded-xl font-semibold shadow-md hover:shadow-xl hover:shadow-blue-200 transition-all duration-500 overflow-hidden transform hover:scale-105 text-center">
                                <div
                                    class="absolute inset-0 w-0 h-full bg-gradient-to-r from-blue-50 to-blue-100 transition-all duration-500 group-hover:w-full">
                                </div>
                                <div class="relative flex items-center justify-center">
                                    <i
                                        class="fas fa-handshake mr-3 group-hover:scale-110 transition-transform duration-300"></i>
                                    <span class="font-semibold">Lihat Layanan</span>
                                </div>
                                <div
                                    class="absolute top-2 right-2 w-2 h-2 bg-blue-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                <div
                                    class="absolute bottom-2 left-2 w-2 h-2 bg-blue-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                            </a>

                            <!-- Accent Button - Demo Gratis -->
                            <a href="#demo"
                                class="group relative px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl font-bold shadow-lg hover:shadow-2xl hover:shadow-cyan-500/40 transition-all duration-500 overflow-hidden transform hover:scale-105 text-center">
                                <div
                                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <div class="relative flex items-center justify-center">
                                    <i class="fas fa-play-circle mr-3 group-hover:animate-pulse"></i>
                                    <span class="drop-shadow-sm">Demo Gratis</span>
                                </div>
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-xl blur opacity-0 group-hover:opacity-30 transition-opacity duration-500 -z-10">
                                </div>
                                <div
                                    class="absolute -bottom-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-400 to-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                                </div>
                            </a>
                        </div>

                        <div class="flex items-center gap-6 mt-2 pt-6 border-t border-blue-100">

                        </div>
                    </div>
                </div>

                <div class="relative" data-animate>
                    <div class="relative floating">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-blue-100">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden shadow-lg">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                                        class="w-full h-full object-contain">
                                </div>

                                <div>
                                    <h3 class="font-bold text-lg">GoScan</h3>
                                    <p class="text-sm text-gray-500">Transaksi Real-time</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                    <span>Barcode Scanner</span>
                                    <span
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">✓
                                        Aktif</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                    <span>Struk Thermal</span>
                                    <span
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">✓
                                        Siap</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                    <span>Laporan Harian</span>
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">📊
                                        Live</span>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Elements -->
                        <div
                            class="absolute -top-4 -right-4 w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl">
                            <i class="fas fa-qrcode text-white text-2xl"></i>
                        </div>
                        <div
                            class="absolute -bottom-4 -left-4 w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-xl">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Fitur Kami Section -->
    <section id="fitur" class="py-20 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-animate>
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 font-semibold text-sm mb-4">
                    <i class="fas fa-cogs mr-2"></i>FITUR UNGGULAN
                </span>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 text-gray-800">
                    Segala Kebutuhan Kasir Dalam
                    <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Satu
                        Platform</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Nikmati kemudahan mengelola bisnis dengan fitur-fitur lengkap yang dirancang khusus untuk UMKM
                    Indonesia.
                </p>
            </div>

            <div class="feature-grid">
                <!-- Fitur 1: Manajemen Stok -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-blue-100 relative"
                    data-animate>
                    <div class="feature-badge">Real-time</div>
                    <img src="{{ asset('images/manajemen produk.jpg') }}" alt="Manajemen Stok" class="feature-image"
                        onerror="this.src='https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-blue-600"></i>
                        </div>
                        Manajemen Stok
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Pantau stok barang secara real-time, dapatkan notifikasi saat stok menipis, dan kelola inventori
                        dengan mudah.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Update stok
                            otomatis</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Notifikasi stok
                            menipis</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Riwayat stok
                            lengkap</li>
                    </ul>
                </div>

                <!-- Fitur 2: Point of Sale (POS) -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-blue-100 relative"
                    data-animate>
                    <div class="feature-badge">Modern</div>
                    <img src="{{ asset('images/pos.jpg') }}" alt="POS System" class="feature-image"
                        onerror="this.src='https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w-800&q=80'">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cash-register text-green-600"></i>
                        </div>
                        POS System
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Sistem kasir modern dengan interface intuitif, mendukung berbagai metode pembayaran dan
                        transaksi cepat.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Multi metode
                            pembayaran</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Interface
                            user-friendly</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Input Manual
                            support</li>
                    </ul>
                </div>

                <!-- Fitur 3: Scan Barcode -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-blue-100 relative"
                    data-animate>
                    <div class="feature-badge">Cepat</div>
                    <img src="{{ asset('images/scanner.jpg') }}" alt="Scan Barcode" class="feature-image"
                        onerror="this.src='https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-barcode text-purple-600"></i>
                        </div>
                        Scan Barcode
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Scan barcode langsung dengan kamera smartphone, tanpa alat tambahan. Cepat, akurat, dan praktis.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Gunakan kamera
                            HP</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Multi format
                            barcode</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Scan batch
                            produk</li>
                    </ul>
                </div>

                <!-- Fitur 4: Keranjang Belanja -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-blue-100 relative"
                    data-animate>
                    <div class="feature-badge">Flexible</div>
                    <img src="{{ asset('images/keranjang.jpg') }}" alt="Keranjang Belanja" class="feature-image"
                        onerror="this.src='https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-yellow-600"></i>
                        </div>
                        Keranjang Belanja
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Kelola multiple transaksi, simpan keranjang sementara, dan proses checkout dengan beberapa item
                        sekaligus.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Multiple
                            keranjang</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Simpan
                            sementara</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Edit quantity
                            mudah</li>
                    </ul>
                </div>

                <!-- Fitur 5: QRIS Payment -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-blue-100 relative"
                    data-animate>
                    <div class="feature-badge">Digital</div>
                    <img src="{{ asset('images/qriss.jpg') }}" alt="QRIS Payment" class="feature-image"
                        onerror="this.src='https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-qrcode text-indigo-600"></i>
                        </div>
                        QRIS Payment
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Terima pembayaran digital via QRIS.
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> QRIS standar
                            nasional</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Mudah Digunakan
                        </li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Auto konfirmasi
                        </li>
                    </ul>
                </div>

                <!-- Fitur 6: Laporan & Analitik -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-blue-100 relative"
                    data-animate>
                    <div class="feature-badge">Advanced</div>
                    <img src="{{ asset('images/laporan.jpg') }}" alt="Laporan & Analitik" class="feature-image"
                        onerror="this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-red-600"></i>
                        </div>
                        Laporan & Analitik
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Akses laporan penjualan, keuangan, dan performa bisnis secara real-time dengan visualisasi
                        grafis yang mudah dipahami.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Dashboard
                            real-time</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Export
                            Excel/PDF</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Laporan
                            Penjualan
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Kami Section -->
    <section id="layanan" class="py-20 px-6 bg-gradient-to-b from-white to-blue-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-animate>
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 font-semibold text-sm mb-4">
                    <i class="fas fa-handshake mr-2"></i>LAYANAN KAMI
                </span>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 text-gray-800">
                    Solusi Lengkap untuk
                    <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Bisnis
                        Anda</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Kami menyediakan layanan sistem kasir terintegrasi yang membantu usaha Anda berkembang lebih cepat
                    dan efisien.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Layanan 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl card-hover border border-blue-100" data-animate>
                    <div class="service-icon bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Implementasi Sistem</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Tim ahli kami membantu setup sistem dari nol, training staff, hingga live operation.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Setup Hosting & Konfigurasi</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Training Operator</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Support 24/7</span>
                        </li>
                    </ul>
                </div>

                <!-- Layanan 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl card-hover border border-blue-100" data-animate>
                    <div class="service-icon bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Support Premium</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Dukungan teknis langsung via WhatsApp, telepon, dan remote team untuk solusi cepat.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>WhatsApp Priority</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Remote Assistance</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Update Berkala</span>
                        </li>
                    </ul>
                </div>

                <!-- Layanan 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl card-hover border border-blue-100" data-animate>
                    <div class="service-icon bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 text-center">Analisis Bisnis</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Laporan detail dan konsultasi strategi untuk meningkatkan penjualan dan efisiensi.
                    </p>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Analisis Penjualan</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Konsultasi Strategi</span>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span>Prediksi Trend</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="demo" class="py-20 px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 opacity-95"></div>
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-60 h-60 bg-blue-400/20 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto relative z-10" data-animate>
            <div class="text-center text-white">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Siap Transformasi Bisnis Anda?</h2>
                <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                    Dapatkan <strong>demo gratis</strong> dan konsultasi dengan tim ahli kami.
                    Lihat langsung bagaimana GoScan bisa mengoptimalkan operasional toko Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <button onclick="openModal()"
                        class="px-10 py-4 bg-white text-blue-700 rounded-xl font-bold text-lg shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 group">
                        <i class="fas fa-calendar-check mr-3 group-hover:rotate-12 transition-transform"></i>
                        Jadwalkan Demo
                    </button>
                    <a href="https://wa.me/6287817184079?text=Halo%20GoScan%2C%20saya%20ingin%20konsultasi"
                        target="_blank"
                        class="px-10 py-4 border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white/10 transition-all duration-300 group">
                        <i class="fab fa-whatsapp mr-3 group-hover:scale-110 transition-transform"></i>
                        Chat via WhatsApp
                    </a>
                </div>

                <p class="mt-8 text-blue-200 text-sm">
                    <i class="fas fa-phone-alt mr-2"></i>Hotline: (628) 7817184079
                    <i class="fas fa-envelope mr-2 ml-4"></i>goscan@gmail.com
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-10 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-10 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden shadow-lg">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                                class="w-full h-full object-contain">
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold">GoScan</h3>
                            <p class="text-blue-300 text-sm">Sistem Kasir Modern</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mb-6">
                        Menyediakan solusi sistem kasir digital terintegrasi untuk membantu UMKM Indonesia berkembang.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Layanan Kami</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Sistem
                                Kasir</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">POS
                                Software</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Inventory
                                System</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Laporan
                                Keuangan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Perusahaan</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Tentang
                                Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Tim Kami</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Hubungi Kami</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-map-marker-alt mr-3 text-blue-400"></i>
                            <span>Universitas Muhammadiyah Yogyakarta, Jl. Brawijaya, Tamantirto, Kec. Kasihan,
                                Kabupaten Bantul, Daerah Istimewa Yogyakarta, Indonesia. </span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-phone mr-3 text-blue-400"></i>
                            <span>(628) 7817184079</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-envelope mr-3 text-blue-400"></i>
                            <span>goscan@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} GoScan - Universitas Muhammadiyah Yogyakarta. All rights reserved. |
                    <a href="#" class="text-blue-400 hover:text-blue-300">Kebijakan Privasi</a> •
                    <a href="#" class="text-blue-400 hover:text-blue-300">Syarat Layanan</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Success Toast -->
    <div id="successToast"
        class="fixed top-6 right-6 z-[200] hidden
           w-[360px] overflow-hidden
           bg-white text-gray-800
           rounded-2xl shadow-2xl border border-green-200">

        <!-- Progress Bar -->
        <div class="h-1 bg-green-200">
            <div id="toastProgress" class="h-full bg-green-500 transition-all duration-[5000ms] ease-linear w-full">
            </div>
        </div>

        <div class="flex items-start gap-4 p-5">

            <!-- ICON AREA -->
            <div class="relative flex-shrink-0">
                <!-- Spinner -->
                <i id="toastSpinner" class="fas fa-spinner fa-spin text-green-500 text-2xl"></i>

                <!-- Check Icon -->
                <i id="toastCheck" class="fas fa-check-circle text-green-600 text-2xl hidden"></i>
            </div>

            <!-- TEXT -->
            <div class="flex-1">
                <p class="font-bold text-gray-900">
                    Permintaan Demo Terkirim
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Tim kami akan segera menghubungi Anda.
                </p>
            </div>

            <!-- CLOSE BUTTON -->
            <button onclick="closeToast()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times"></i>
            </button>

        </div>
    </div>

    <div id="demoModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300">

        <div id="modalCard"
            class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative overflow-hidden transform transition-all scale-95 opacity-0 duration-300">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white relative">
                <button onclick="closeModal()"
                    class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
                <h3 class="text-2xl font-bold">Jadwalkan Demo</h3>
                <p class="text-blue-100 text-sm">Pilih waktu yang tepat untuk konsultasi gratis.</p>
            </div>

            <!-- FORM -->
            <form action="https://formsubmit.co/farhanvirenze18@gmail.com" method="POST" class="p-8 space-y-4">

                <!-- Hidden Config -->
                <input type="hidden" name="_subject" value="Jadwal Demo Baru - GoScan">
                <input type="hidden" name="_template" value="table">
                <input type="hidden" name="_replyto" value="email">
                <input type="hidden" name="_next" value="{{ url('/') }}?demo=success">
                <input type="text" name="_honey" style="display:none">

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200
                           focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Nama Anda">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200
                           focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="email@contoh.com">
                </div>

                <!-- Tanggal & Waktu -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Tanggal
                        </label>
                        <input type="date" name="demo_date" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200
                               focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Waktu
                        </label>
                        <input type="time" name="demo_time" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200
                               focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        WhatsApp / No. HP
                    </label>
                    <input type="tel" name="phone" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200
                           focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Contoh: 0878xxxxxxx">
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Catatan / Pesan
                    </label>
                    <textarea name="note" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200
               focus:ring-2 focus:ring-blue-500 outline-none resize-none"
                        placeholder="Contoh: Ingin demo fitur scan barcode & laporan penjualan">
    </textarea>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold
                       shadow-lg hover:bg-blue-700 transition-all
                       transform hover:-translate-y-1">
                    Kirim Permintaan Demo
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // =====================================================
            // 0. NOTIFIKASI SUCCESS (FORM DEMO BERHASIL)
            // =====================================================

            // Close toast (global karena dipakai onclick)
            window.closeToast = function() {
                const toast = document.getElementById('successToast');
                if (!toast) return;

                toast.classList.remove('toast-show');
                toast.classList.add('toast-hide');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 400);
            };

            const params = new URLSearchParams(window.location.search);

            if (params.get('demo') === 'success') {
                const toast = document.getElementById('successToast');
                const spinner = document.getElementById('toastSpinner');
                const check = document.getElementById('toastCheck');
                const progress = document.getElementById('toastProgress');

                if (toast) {
                    toast.classList.remove('hidden');
                    toast.classList.add('toast-show');

                    // STEP 1: loading spinner (1.2 detik)
                    setTimeout(() => {
                        if (spinner) spinner.classList.add('hidden');
                        if (check) check.classList.remove('hidden');
                    }, 1200);

                    // STEP 2: progress bar berjalan
                    setTimeout(() => {
                        if (progress) progress.style.width = '0%';
                    }, 100);

                    // STEP 3: auto close (5 detik)
                    setTimeout(() => {
                        window.closeToast();
                    }, 5200);
                }

                // Bersihkan URL (?demo=success)
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // =====================================================
            // 1. PENGATURAN MODAL (POP-UP)
            // =====================================================
            const modal = document.getElementById('demoModal');
            const card = document.getElementById('modalCard');

            window.openModal = function() {
                modal.classList.replace('hidden', 'flex');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            };

            window.closeModal = function() {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.replace('flex', 'hidden');
                }, 300);
            };

            window.onclick = function(event) {
                if (event.target === modal) closeModal();
            };

            // =====================================================
            // 2. ANIMASI SCROLL (INTERSECTION OBSERVER)
            // =====================================================
            const animationObserver = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('[data-animate]')
                .forEach(el => animationObserver.observe(el));

            // =====================================================
            // 3. SMOOTH SCROLL NAVIGASI
            // =====================================================
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

        });
    </script>

</body>

</html>
