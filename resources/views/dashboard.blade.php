<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight flex items-center gap-3">
            <span>{{ __('Dashboard') }}</span>
        </h2>
    </x-slot>

    <script src="https://kit.fontawesome.com/a2e0f1f3f1.js" crossorigin="anonymous"></script>

    <div class="bg-white min-h-screen pt-6 pb-16">
        <div class="max-w-[97%] mx-auto px-4 sm:px-6 lg:px-6 space-y-10">

            {{-- FILTER --}}
            <div class="bg-blue-50 backdrop-blur-xl shadow-lg rounded-2xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    Filter Data
                </h3>

                <form method="GET" action="{{ route('dashboard') }}"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 items-end">

                    {{-- Tipe Filter --}}
                    <div>
                        <label class="text-gray-700 font-medium text-sm mb-1 block">Tipe Filter</label>
                        <select name="filter" id="filter"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                            <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua</option>
                            <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>Hari Ini
                            </option>
                            <option value="bulan_ini" {{ request('filter') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini
                            </option>
                            <option value="tahun_ini" {{ request('filter') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini
                            </option>
                            <option value="rentang" {{ request('filter') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal
                            </option>
                        </select>
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div id="tanggalMulaiWrapper" class="{{ request('filter') == 'rentang' ? '' : 'hidden' }}">
                        <label class="text-gray-700 font-medium text-sm mb-1 block">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div id="tanggalSelesaiWrapper" class="{{ request('filter') == 'rentang' ? '' : 'hidden' }}">
                        <label class="text-gray-700 font-medium text-sm mb-1 block">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-end space-x-2 sm:col-span-2 md:col-span-1">
                        <button type="submit"
                            class="flex-1 bg-blue-500 text-white px-4 py-2.5 rounded-xl font-semibold hover:bg-blue-600 hover:shadow-md transition-all duration-300">
                            <i class="fas fa-check-circle mr-1"></i> Tampilkan
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-medium hover:bg-gray-300 transition-all duration-300">
                            <i class="fas fa-undo-alt"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
                @php
                    $cards = [
                        ['icon' => 'fa-boxes', 'color' => 'text-sky-500', 'label' => 'Total Produk', 'value' => $jumlahProduk],
                        ['icon' => 'fa-shopping-cart', 'color' => 'text-indigo-500', 'label' => 'Produk Terjual', 'value' => $produkTerjual],
                        ['icon' => 'fa-coins', 'color' => 'text-green-500', 'label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.')],
                        ['icon' => 'fa-chart-line', 'color' => 'text-purple-500', 'label' => 'Total Profit', 'value' => 'Rp ' . number_format($totalProfit, 0, ',', '.')],
                        ['icon' => 'fa-receipt', 'color' => 'text-amber-500', 'label' => 'Jumlah Transaksi', 'value' => $jumlahTransaksi],
                        ['icon' => 'fa-users', 'color' => 'text-rose-500', 'label' => 'Jumlah Pengguna', 'value' => $jumlahUser],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div
                        class="bg-blue-50 backdrop-blur-lg border border-blue-100 shadow-[0_4px_15px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.15)] transition-all duration-300 ease-in-out rounded-2xl p-5 text-center transform hover:scale-[1.03] group">
                        <div
                            class="flex justify-center mb-3 {{ $card['color'] }} text-3xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                        <p class="text-sm font-medium tracking-wide text-gray-600">{{ $card['label'] }}</p>
                        <p class="text-2xl sm:text-3xl font-extrabold mt-1 text-gray-900">{{ $card['value'] }}</p>

                        {{-- Persentase Profit --}}
                        @if ($card['label'] === 'Total Profit')
                            @php
                                $arrow = $persentaseProfit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                                $color = $persentaseProfit >= 0 ? 'text-green-500' : 'text-red-500';
                            @endphp
                            <div class="mt-2 flex items-center justify-center gap-1">
                                <i class="fas {{ $arrow }} {{ $color }}"></i>
                                <span class="{{ $color }} font-semibold text-sm sm:text-base">
                                    {{ abs($persentaseProfit) }}%
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Script toggle tanggal rentang --}}
            <script>
                const filterSelect = document.getElementById('filter');
                const tanggalMulaiWrapper = document.getElementById('tanggalMulaiWrapper');
                const tanggalSelesaiWrapper = document.getElementById('tanggalSelesaiWrapper');

                function toggleTanggal() {
                    const isRentang = filterSelect.value === 'rentang';
                    tanggalMulaiWrapper.classList.toggle('hidden', !isRentang);
                    tanggalSelesaiWrapper.classList.toggle('hidden', !isRentang);
                }

                filterSelect.addEventListener('change', toggleTanggal);
                toggleTanggal(); // jalankan saat page load
            </script>

            {{-- 📈 GRAFIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @php
                    $charts = [
                        ['id' => 'penjualanChart', 'title' => 'Total Penjualan', 'color' => 'text-blue-700', 'icon' => 'fa-chart-line', 'border' => 'border-blue-100'],
                        ['id' => 'profitChart', 'title' => 'Profit', 'color' => 'text-green-700', 'icon' => 'fa-hand-holding-usd', 'border' => 'border-blue-100'],
                        ['id' => 'transaksiChart', 'title' => 'Jumlah Transaksi', 'color' => 'text-blue-700', 'icon' => 'fa-file-invoice-dollar', 'border' => 'border-blue-100'],
                        ['id' => 'metodePembayaranChart', 'title' => 'Distribusi Metode Pembayaran', 'color' => 'text-yellow-700', 'icon' => 'fa-wallet', 'border' => 'border-blue-100'],
                        ['id' => 'produkTerlarisChart', 'title' => 'Top 5 Produk Terlaris', 'color' => 'text-pink-700', 'icon' => 'fa-fire', 'border' => 'border-blue-100'],
                        ['id' => 'stokProdukChart', 'title' => 'Top 5 Stok Produk', 'color' => 'text-purple-700', 'icon' => 'fa-box-open', 'border' => 'border-blue-100'],
                    ];
                @endphp

                @foreach ($charts as $chart)
                    <div
                        class="bg-blue-50 shadow-lg rounded-2xl p-6 border {{ $chart['border'] }} hover:shadow-2xl hover:scale-[1.02] transition-all duration-300 ease-in-out {{ $chart['border'] }}">
                        <h3 class="text-lg font-semibold {{ $chart['color'] }} mb-3 flex items-center gap-2">
                            <i class="fas {{ $chart['icon'] }}"></i> {{ $chart['title'] }}
                        </h3>
                        <canvas id="{{ $chart['id'] }}" height="120"></canvas>
                    </div>
                @endforeach
            </div>

            {{-- 📊 TABEL DATA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                {{-- PRODUK TERLARIS --}}
                <div
                    class="bg-blue-50 backdrop-blur-md shadow-lg rounded-xl p-5 border border-blue-100 hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500"></i> Produk Terlaris
                    </h3>
                    <table class="w-full border border-gray-200 text-sm text-left rounded-lg overflow-hidden">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Produk</th>
                                <th class="px-3 py-2 text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkTerlarisTable as $index => $item)
                                @php $product = $item->product; @endphp
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-3 py-2">
                                        {{ $loop->iteration + ($produkTerlarisTable->currentPage() - 1) * $produkTerlarisTable->perPage() }}
                                    </td>
                                    <td class="px-3 py-2 flex items-center gap-3">
                                        {{-- 🖼️ FOTO PRODUK --}}
                                        <div class="relative w-10 h-10 overflow-hidden rounded-md group">
                                            @if ($product && $product->foto)
                                                <img src="{{ asset($product->foto) }}" alt="{{ $product->nama }}"
                                                    class="w-full h-full object-cover rounded-md group-hover:scale-110 transition-transform duration-300">
                                            @else
                                                <div
                                                    class="w-10 h-10 flex items-center justify-center bg-gray-200 text-gray-500 rounded-md text-xs">
                                                    N/A
                                                </div>
                                            @endif
                                        </div>
                                        <span
                                            class="font-medium text-gray-800">{{ $product->nama ?? 'Tidak diketahui' }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-right font-semibold text-gray-700">{{ $item->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-1">
                        {{ $produkTerlarisTable->appends(request()->except('produk_page'))->links() }}
                    </div>
                </div>

                {{-- STOK PRODUK --}}
                <div
                    class="bg-blue-50 backdrop-blur-md shadow-lg rounded-xl p-5 border border-blue-100 hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-cubes text-green-500"></i> Stok Produk Tersedia
                    </h3>
                    <table class="w-full border border-gray-200 text-sm text-left rounded-lg overflow-hidden">
                        <thead class="bg-green-500 text-white">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Produk</th>
                                <th class="px-3 py-2 text-right">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokTersediaTable as $index => $produk)
                                <tr class="border-b hover:bg-green-50 transition">
                                    <td class="px-3 py-2">
                                        {{ $loop->iteration + ($stokTersediaTable->currentPage() - 1) * $stokTersediaTable->perPage() }}
                                    </td>
                                    <td class="px-3 py-2 flex items-center gap-3">
                                        {{-- 🖼️ FOTO PRODUK --}}
                                        <div class="relative w-10 h-10 overflow-hidden rounded-md group">
                                            @if ($produk->foto)
                                                <img src="{{ asset($produk->foto) }}" alt="{{ $produk->nama }}"
                                                    class="w-full h-full object-cover rounded-md group-hover:scale-110 transition-transform duration-300">
                                            @else
                                                <div
                                                    class="w-10 h-10 flex items-center justify-center bg-gray-200 text-gray-500 rounded-md text-xs">
                                                    N/A
                                                </div>
                                            @endif
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $produk->nama }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-right font-semibold text-gray-700">{{ $produk->stok }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-1">
                        {{ $stokTersediaTable->appends(request()->except('stok_page'))->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // Utility warna
        const textColor = '#1e3a8a';
        const gridColor = 'rgba(59,130,246,0.1)';

        // === PENJUALAN HARIAN DENGAN PERSENTASE TREND ===
        const ctxPenjualan = document.getElementById('penjualanChart').getContext('2d');

        // Ambil data dari Laravel
        const penjualanLabels = {!! json_encode($penjualanHarian->pluck('tanggal')) !!};
        const penjualanData = {!! json_encode($penjualanHarian->pluck('total')) !!};

        // Hitung persentase perubahan antar hari
        const persentasePerubahan = [0]; // hari pertama tidak punya perubahan
        for (let i = 1; i < penjualanData.length; i++) {
            const prev = penjualanData[i - 1];
            const curr = penjualanData[i];
            const persen = prev === 0 ? 0 : ((curr - prev) / prev) * 100;
            persentasePerubahan.push(persen);
        }

        // Tentukan warna garis berdasarkan tren terakhir
        const isNaik = persentasePerubahan.at(-1) >= 0;
        const lineColor = isNaik ? '#10b981' : '#ef4444'; // hijau atau merah

        // Buat gradasi dinamis
        const gradient = ctxPenjualan.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, isNaik ? 'rgba(16, 185, 129, 0.5)' : 'rgba(239, 68, 68, 0.5)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

        // Buat chart
        new Chart(ctxPenjualan, {
            type: 'line',
            data: {
                labels: penjualanLabels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: penjualanData,
                    backgroundColor: gradient,
                    borderColor: lineColor,
                    borderWidth: 3,
                    pointBackgroundColor: penjualanData.map((val, i) =>
                        persentasePerubahan[i] >= 0 ? '#10b981' : '#ef4444'
                    ),
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                        callbacks: {
                            label: function (context) {
                                const value = context.raw.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                                const perubahan = persentasePerubahan[context.dataIndex];
                                const tanda = perubahan >= 0 ? '🟢 +' : '🔴 ';
                                return `${value} (${tanda}${perubahan.toFixed(2)}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        },
                        grid: { color: gridColor }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            }
        });

        // === PROFIT HARIAN ===
        const ctxProfit = document.getElementById('profitChart').getContext('2d');
        const labels = {!! json_encode($profitHarian->pluck('tanggal')) !!};
        const dataProfit = {!! json_encode($profitHarian->pluck('total_profit')) !!};

        // Hitung persentase perubahan antar hari
        const percentChanges = dataProfit.map((value, index) => {
            if (index === 0) return 0;
            const prev = dataProfit[index - 1];
            return ((value - prev) / prev) * 100;
        });

        // Buat warna dinamis: hijau naik, merah turun, abu jika sama
        const lineColors = percentChanges.map((p, i) => {
            if (i === 0) return '#10b981'; // warna awal
            return p > 0 ? '#10b981' : p < 0 ? '#ef4444' : '#9ca3af';
        });

        // Gradasi hijau elegan
        const gradientProfit = ctxProfit.createLinearGradient(0, 0, 0, 300);
        gradientProfit.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradientProfit.addColorStop(1, 'rgba(255, 255, 255, 0)');

        new Chart(ctxProfit, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Profit Harian',
                    data: dataProfit,
                    backgroundColor: gradientProfit,
                    borderColor: function (context) {
                        const index = context.dataIndex;
                        return lineColors[index];
                    },
                    segment: {
                        borderColor: ctx => {
                            const i = ctx.p0DataIndex;
                            return lineColors[i + 1] || '#10b981';
                        }
                    },
                    borderWidth: 3,
                    pointBackgroundColor: function (context) {
                        const index = context.dataIndex;
                        return lineColors[index];
                    },
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function (context) {
                                const index = context.dataIndex;
                                const profit = context.raw;
                                const percent = percentChanges[index].toFixed(2);
                                const changeText =
                                    index === 0
                                        ? ''
                                        : percent > 0
                                            ? ` (+${percent}%)`
                                            : percent < 0
                                                ? ` (${percent}%)`
                                                : ' (0%)';
                                return 'Rp ' + profit.toLocaleString() + changeText;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#4b5563',
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { color: '#4b5563' },
                        grid: { display: false }
                    }
                },
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'easeInOutQuad',
                        from: 0.2,
                        to: 0.4,
                        loop: false
                    }
                }
            }
        });

        // === TRANSAKSI ===
        const ctxTransaksi = document.getElementById('transaksiChart').getContext('2d');
        const transaksiData = {!! json_encode($transaksiHarian->pluck('jumlah')) !!};
        const transaksiLabels = {!! json_encode($transaksiHarian->pluck('tanggal')) !!};

        const transaksiGradient = ctxTransaksi.createLinearGradient(0, 0, 0, 300);
        transaksiGradient.addColorStop(0, 'rgba(59,130,246,0.7)');
        transaksiGradient.addColorStop(1, 'rgba(59,130,246,0.3)');

        new Chart(ctxTransaksi, {
            type: 'bar',
            data: {
                labels: transaksiLabels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: transaksiData,
                    backgroundColor: transaksiGradient,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(59,130,246,1)',
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                        cornerRadius: 6,
                        padding: 10,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: textColor,
                        font: { weight: 'bold' },
                        formatter: (value) => value
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor, stepSize: 1 },
                        grid: { color: gridColor, drawBorder: false }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // === METODE PEMBAYARAN (BAR CHART) ===
        const ctxMetode = document.getElementById('metodePembayaranChart').getContext('2d');
        const metodeLabels = {!! json_encode($metodePembayaran->pluck('metode_pembayaran')) !!};
        const metodeData = {!! json_encode($metodePembayaran->pluck('total')) !!};
        const metodeColors = ['#3b82f6', '#10b981', '#facc15'];

        new Chart(ctxMetode, {
            type: 'bar',
            data: {
                labels: metodeLabels,
                datasets: [{
                    label: 'Total',
                    data: metodeData,
                    backgroundColor: metodeColors.map(c => `${c}cc`), // sedikit transparan
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: metodeColors,
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#fff',
                        bodyColor: '#d1d5db',
                        cornerRadius: 6,
                        padding: 10,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.label}: ${context.raw}`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: textColor,
                        font: { weight: 'bold' },
                        formatter: (value) => value
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor, stepSize: 1 },
                        grid: { color: gridColor, drawBorder: false }
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // === PRODUK TERLARIS ===
        const ctxProdukTerlaris = document.getElementById('produkTerlarisChart').getContext('2d');
        new Chart(ctxProdukTerlaris, {
            type: 'pie',
            data: {
                labels: {!! json_encode($produkTerlarisChart->pluck('product.nama')) !!},
                datasets: [{
                    data: {!! json_encode($produkTerlarisChart->pluck('total')) !!},
                    backgroundColor: ['#3b82f6', '#10b981', '#f43f5e', '#facc15', '#8b5cf6']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: textColor } }, datalabels: { color: '#fff' } }
            },
            plugins: [ChartDataLabels]
        });

        // === STOK PRODUK ===
        const ctxStokProduk = document.getElementById('stokProdukChart').getContext('2d');
        new Chart(ctxStokProduk, {
            type: 'pie',
            data: {
                labels: {!! json_encode($stokTersediaChart->pluck('nama')) !!},
                datasets: [{
                    data: {!! json_encode($stokTersediaChart->pluck('stok')) !!},
                    backgroundColor: ['#10b981', '#3b82f6', '#facc15', '#f43f5e', '#8b5cf6']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: textColor } }, datalabels: { color: '#fff' } }
            },
            plugins: [ChartDataLabels]
        });
    </script>
</x-app-layout>