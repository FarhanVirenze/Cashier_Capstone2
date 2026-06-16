<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        {{-- Lebarkan area utama --}}
        <div class="max-w-[97%]  mx-auto px-4 sm:px-6 lg:px-6">
            <div class="space-y-4">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-600 font-medium">
                        {{ session('success') }}
                    </p>
                @endif

                @if (session('error'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-red-600 dark:text-red-600 font-medium">
                        {{ session('error') }}
                    </p>
                @endif
                {{-- Filter Laporan Penjualan --}}
                <div class="bg-blue-50 border border-blue-100 shadow-md rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        Filter Laporan Penjualan
                    </h3>

                    <form action="{{ route('transaksi.index') }}" method="GET"
                        class="flex flex-col sm:flex-row items-start sm:items-end gap-4">

                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

                          {{-- Dari Tanggal --}}
<div class="flex flex-col w-full sm:w-auto">
    <label for="start_date" class="text-sm text-gray-600 mb-1">
        Dari Tanggal
    </label>

    <input type="date" name="start_date" id="start_date"
        value="{{ request('start_date') }}"
        class="border border-gray-300 rounded-lg
               pr-3 py-2
               focus:outline-none focus:ring-2
               focus:ring-blue-400 focus:border-blue-400
               transition w-full sm:w-48">
</div>

{{-- Sampai Tanggal --}}
<div class="flex flex-col w-full sm:w-auto">
    <label for="end_date" class="text-sm text-gray-600 mb-1">
        Sampai Tanggal
    </label>

    <input type="date" name="end_date" id="end_date"
        value="{{ request('end_date') }}"
        class="border border-gray-300 rounded-lg
               pr-3 py-2
               focus:outline-none focus:ring-2
               focus:ring-blue-400 focus:border-blue-400
               transition w-full sm:w-48">
</div>

                        </div>

                        {{-- Tombol --}}
                        <div class="flex gap-2 mt-4 sm:mt-0">

                            {{-- Tampilkan --}}
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition flex items-center gap-2">
                                <i class="fa fa-filter"></i>
                                Tampilkan
                            </button>

                            {{-- Reset (MUNCUL HANYA JIKA ADA FILTER) --}}
                            @if (request()->filled('start_date') || request()->filled('end_date'))
                                <a href="{{ route('transaksi.index') }}"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-5 py-2 rounded-lg shadow-md transition flex items-center gap-2">
                                    <i class="fa fa-undo"></i>
                                    Reset
                                </a>
                            @endif

                        </div>
                    </form>
                </div>

                {{-- === Tambahan logika baru === --}}
                @php
                    $hasFilter = request('start_date') && request('end_date');
                    $start = request('start_date');
                    $end = request('end_date');
                @endphp

                @if (!$hasFilter)
                    <div
                        class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded-lg text-sm font-medium text-center">
                        Silakan pilih rentang tanggal terlebih dahulu untuk menampilkan data laporan penjualan.
                    </div>
                @endif

                {{-- === Data Penjualan (Responsif untuk Mobile & Desktop) === --}}
                @if ($hasFilter)
                    <div class="bg-blue-50 shadow-md rounded-lg border border-blue-100 p-5 space-y-4">

                        {{-- === Header Data Penjualan === --}}
                        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-300 pb-2 mb-3">
                            Data Penjualan
                        </h3>

                        {{-- 📅 Rentang Tanggal (Vertikal) --}}
                        <div class="flex flex-col gap-2 text-sm sm:text-base">
                            <div class="flex items-center">
                                <span class="font-semibold w-36 text-gray-700">Dari Tanggal</span>
                                <span class="text-gray-800">
                                    : {{ $start ? date('d/m/Y', strtotime($start)) : '-' }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold w-36 text-gray-700">Sampai Tanggal</span>
                                <span class="text-gray-800">
                                    : {{ $end ? date('d/m/Y', strtotime($end)) : '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- 🎯 Tombol Aksi + Search (Responsif) --}}
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mt-3">

                            {{-- 🔘 Tombol Aksi --}}
                            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                                {{-- 🔘 Tombol CETAK PDF + EXCEL --}}
                                <button id="btnDownloadLaporan"
                                    class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow transition-all duration-150 w-full sm:w-auto">
                                    <i class="fa-solid fa-file-export mr-2"></i> CETAK LAPORAN (PDF + EXCEL)
                                </button>

                                <script>
                                    document.getElementById('btnDownloadLaporan').addEventListener('click', async () => {
                                        const start = "{{ $start ?? '' }}";
                                        const end = "{{ $end ?? '' }}";

                                        if (!start || !end) {
                                            alert('⚠️ Silakan pilih rentang tanggal sebelum mencetak laporan.');
                                            return;
                                        }

                                        // 🔁 Fungsi umum untuk download file
                                        async function downloadFile(url, filename) {
                                            try {
                                                const response = await fetch(url, {
                                                    method: 'GET',
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                });

                                                if (!response.ok) {
                                                    const errText = await response.text();
                                                    console.error('Server error:', errText);
                                                    alert(`Gagal mengunduh ${filename}`);
                                                    return;
                                                }

                                                const blob = await response.blob();
                                                const fileURL = window.URL.createObjectURL(blob);

                                                const a = document.createElement('a');
                                                a.href = fileURL;
                                                a.download = filename;
                                                document.body.appendChild(a);
                                                a.click();

                                                window.URL.revokeObjectURL(fileURL);
                                                a.remove();
                                            } catch (err) {
                                                console.error(err);
                                                alert(`Terjadi kesalahan saat mengunduh ${filename}`);
                                            }
                                        }

                                        // 🧾 Unduh PDF
                                        await downloadFile(
                                            `{{ route('transaksi.export-pdf') }}?start_date=${start}&end_date=${end}`,
                                            `laporan-penjualan-${start}-sd-${end}.pdf`
                                        );

                                        // 📊 Unduh Excel
                                        await downloadFile(
                                            `{{ route('transaksi.export-excel') }}?start_date=${start}&end_date=${end}`,
                                            `laporan-penjualan-${start}-sd-${end}.xlsx`
                                        );
                                    });
                                </script>

                                <!-- Tombol PRINT -->
                                <button onclick="printTable()"
                                    class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow transition-all duration-150 w-full sm:w-auto">
                                    <i class="fa-solid fa-print mr-2"></i> PRINT
                                </button>

                                <style>
                                    /* 🎯 CSS khusus untuk mode print */
                                    @media print {

                                        /* Hilangkan semua elemen di luar printArea */
                                        body * {
                                            visibility: hidden !important;
                                        }

                                        #printArea,
                                        #printArea * {
                                            visibility: visible !important;
                                        }

                                        #printArea {
                                            position: absolute;
                                            left: 0;
                                            top: 0;
                                            width: 100%;
                                            background: white;
                                            color: black;
                                            padding: 10px;
                                            font-size: 11px;
                                        }

                                        /* Gaya tabel agar rapi saat dicetak */
                                        #printArea table {
                                            width: 100%;
                                            border-collapse: collapse;
                                            table-layout: fixed;
                                        }

                                        #printArea th,
                                        #printArea td {
                                            border: 1px solid #000;
                                            padding: 4px;
                                            font-size: 9.5px;
                                            word-wrap: break-word;
                                        }

                                        #printArea th {
                                            background-color: #eee;
                                            font-weight: bold;
                                            text-transform: uppercase;
                                        }

                                        /* Hilangkan kolom Aksi */
                                        .no-print {
                                            display: none !important;
                                        }

                                        /* Pastikan tabel tidak terpotong antar halaman */
                                        table,
                                        tr,
                                        td,
                                        th {
                                            page-break-inside: avoid !important;
                                        }
                                    }
                                </style>

                                <script>
                                    function printTable() {
                                        const table = document.querySelector('table');
                                        if (!table) {
                                            alert('Tabel tidak ditemukan');
                                            return;
                                        }

                                        // Clone tabel agar bisa manipulasi kolom tanpa mengubah halaman asli
                                        const clone = table.cloneNode(true);

                                        // Hapus kolom Aksi di clone
                                        const aksiIndex = Array.from(clone.querySelectorAll('th')).findIndex(th => th.innerText.toLowerCase().includes(
                                            'aksi'));
                                        if (aksiIndex >= 0) {
                                            clone.querySelectorAll('tr').forEach(tr => {
                                                const cell = tr.children[aksiIndex];
                                                if (cell) cell.remove();
                                            });
                                        }

                                        // Buat iframe untuk print
                                        const iframe = document.createElement('iframe');
                                        iframe.style.position = 'fixed';
                                        iframe.style.right = '0';
                                        iframe.style.bottom = '0';
                                        iframe.style.width = '0';
                                        iframe.style.height = '0';
                                        iframe.style.border = '0';
                                        document.body.appendChild(iframe);

                                        const doc = iframe.contentWindow.document;
                                        doc.open();
                                        doc.write(`
            <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 10px; margin: 10px; }
                    h2 { text-align: center; margin-bottom: 4px; font-size: 14px; }
                    p { text-align: center; margin: 2px 0 6px; font-size: 11px; }
                    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
                    th, td { border: 1px solid #000; padding: 4px; font-size: 9.5px; word-wrap: break-word; }
                    th { background: #eee; text-transform: uppercase; }
                </style>
            </head>
            <body>
                <h2>LAPORAN TRANSAKSI PENJUALAN</h2>
                <p>Warung Golpal</p>
                <p>Periode: {{ $start ?? '-' }} s.d {{ $end ?? '-' }}</p>
                ${clone.outerHTML}
            </body>
            </html>
        `);
                                        doc.close();

                                        setTimeout(() => {
                                            iframe.contentWindow.focus();
                                            iframe.contentWindow.print();
                                            setTimeout(() => document.body.removeChild(iframe), 1000);
                                        }, 500);
                                    }
                                </script>

                            </div>

                            {{-- 🔍 Search Box Modern --}}
                            <div class="flex flex-col sm:flex-row items-center justify-end gap-2 w-full sm:w-auto">
                                <form action="{{ route('transaksi.index') }}" method="GET"
                                    class="relative flex items-center bg-white/80 backdrop-blur-md border border-gray-200 rounded-full shadow-sm hover:shadow-md transition-all duration-300 focus-within:ring-2 focus-within:ring-blue-400 w-full sm:w-80">

                                    {{-- Hidden input agar filter tanggal tetap tersimpan --}}
                                    <input type="hidden" name="start_date" value="{{ $start }}">
                                    <input type="hidden" name="end_date" value="{{ $end }}">

                                    {{-- Icon Search --}}
                                    <span class="absolute left-4 text-gray-400">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>

                                    {{-- Input --}}
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari transaksi..."
                                        class="w-full pl-10 pr-9 py-2 text-sm text-gray-700 bg-transparent rounded-full focus:outline-none placeholder-gray-400 transition-all" />

                                    {{-- Tombol Submit --}}
                                    <button type="submit"
                                        class="absolute right-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-7 h-7 flex items-center justify-center shadow transition-all duration-200 hover:scale-105"
                                        title="Cari">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </button>
                                </form>

                                {{-- Tombol Reset (Hanya muncul jika ada pencarian) --}}
                                @if (request('search'))
                                    <a href="{{ route('transaksi.index', ['start_date' => $start, 'end_date' => $end]) }}"
                                        class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-600 border border-gray-200 text-sm font-medium px-3 py-2 rounded-full shadow-sm transition-all duration-200 w-full sm:w-auto justify-center"
                                        title="Reset pencarian">
                                        <i class="fa-solid fa-rotate-left"></i>
                                        <span>Reset</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- === Tabel Data Penjualan (Hanya Desktop) === --}}
                    <div class="hidden md:block">
                        <div
                            class="overflow-x-auto rounded-lg shadow-lg border border-gray-200 bg-white max-w-[98vw] mx-auto">
                            <table class="min-w-full text-gray-900 whitespace-nowrap">
                                <thead>
                                    <tr class="bg-blue-600 text-white uppercase text-sm">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">No. Invoice</th>
                                        <th class="px-4 py-2">Tanggal</th>
                                        <th class="px-4 py-2">Kasir</th>
                                        <th class="px-4 py-2">Pelanggan</th>
                                        <th class="px-4 py-2">Nomor</th>
                                        <th class="px-4 py-2">Metode</th>
                                        <th class="px-4 py-2">Sub Total</th>
                                        <th class="px-4 py-2">Diskon</th>
                                        <th class="px-4 py-2">Total</th>
                                        <th class="px-4 py-2">Jumlah Bayar</th>
                                        <th class="px-4 py-2">Kembalian</th>
                                        <th class="px-4 py-2">Total Modal</th>
                                        <th class="px-4 py-2">Profit</th>
                                        <th class="px-4 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transaksi as $i => $trx)
                                        <tr
                                            class="border-b border-gray-200 hover:bg-blue-50 transition-colors duration-200">
                                            <td class="px-4 py-2 text-center font-medium">
                                                {{ $transaksi->firstItem() + $i }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">{{ $trx->no_invoice }}
                                            </td>
                                            <td class="px-4 py-2">{{ $trx->created_at->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="px-4 py-2">{{ $trx->user->name ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $trx->customer->nama ?? 'Umum' }}</td>
                                            <td class="px-4 py-2">{{ $trx->customer->no_telepon ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ ucfirst($trx->metode_pembayaran) }}</td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->diskon, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->total, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->kembalian, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->total_modal, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">
                                                Rp{{ number_format($trx->profit, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 space-y-1">
                                                <div class="flex flex-col space-y-1">
                                                    {{-- Cetak Struk --}}
                                                    <button type="button" onclick="cetakStruk({{ $trx->id }})"
                                                        class="text-amber-600 hover:text-amber-800 font-semibold text-sm flex items-center justify-start">
                                                        <i class="fa-solid fa-print mr-1"></i> Cetak Struk
                                                    </button>

                                                    <script>
                                                        async function cetakStruk(id) {
                                                            try {
                                                                const response = await fetch(`/transaksi/data/${id}`);
                                                                const trx = await response.json();

                                                                if (!trx) {
                                                                    alert('Data transaksi tidak ditemukan');
                                                                    return;
                                                                }

                                                                // 🖨️ IFRAME KHUSUS PRINT
                                                                const iframe = document.createElement('iframe');
                                                                iframe.style.position = 'fixed';
                                                                iframe.style.width = '0';
                                                                iframe.style.height = '0';
                                                                iframe.style.border = '0';

                                                                document.body.appendChild(iframe);

                                                                const doc = iframe.contentWindow.document;
                                                                doc.open();
                                                                doc.write(`
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            margin: 0;
            padding: 10px;
        }
        h2 {
            text-align: center;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        td {
            padding: 2px 0;
        }
        hr {
            border: none;
            border-top: 2px dashed #000;
            margin: 10px 0;
        }
        @page {
            size: auto;
            margin: 0;
        }
    </style>
</head>
<body>
    <h2>Warung Golpal</h2>
  <p style="text-align:center;">
  ${new Date(trx.created_at).toLocaleString('id-ID', {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
  })}
</p>
</p>

   <p>
    <strong>No. Invoice:</strong> ${trx.no_invoice}<br>
    <strong>Kasir:</strong> ${trx.user?.name ?? '-'}<br>
    <strong>Pelanggan:</strong> ${trx.customer?.nama ?? 'Umum'}<br>
    <strong>Nomor:</strong> ${trx.customer?.no_telepon ?? '-'}<br>
    <strong>Metode:</strong> ${trx.metode_pembayaran?.toUpperCase() ?? '-'}
</p>

<hr>

${(trx.details ?? []).map(item => `
                                                                                                                                                                                                                    <div>
                                                                                                                                                                                                                        <strong>${item.product?.nama_product ?? item.nama_product}</strong><br>
                                                                                                                                                                                                                        ${(item.jumlah ?? 0)} x Rp${(item.harga ?? 0).toLocaleString('id-ID')}
                                                                                                                                                                                                                        <span style="float:right;">
                                                                                                                                                                                                                            Rp${(item.total ?? 0).toLocaleString('id-ID')}
                                                                                                                                                                                                                        </span>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                `).join('')}

<hr>

<table>
    <tr>
        <td>Sub Total</td>
        <td style="text-align:right;">Rp${(trx.subtotal ?? 0).toLocaleString('id-ID')}</td>
    </tr>
    <tr>
        <td>Diskon</td>
        <td style="text-align:right;">Rp${(trx.diskon ?? 0).toLocaleString('id-ID')}</td>
    </tr>
    <tr>
        <td>Total</td>
        <td style="text-align:right;">Rp${(trx.total ?? 0).toLocaleString('id-ID')}</td>
    </tr>
    <tr>
        <td>Bayar</td>
        <td style="text-align:right;">Rp${(trx.jumlah_bayar ?? 0).toLocaleString('id-ID')}</td>
    </tr>
    <tr>
        <td>Kembali</td>
        <td style="text-align:right;">Rp${(trx.kembalian ?? 0).toLocaleString('id-ID')}</td>
    </tr>
</table>

<p style="text-align:center; margin-top:12px;">
    *** TERIMA KASIH ***
</p>


</body>
</html>
        `);
                                                                doc.close();

                                                                // ⏳ WAJIB DELAY DI MOBILE
                                                                setTimeout(() => {
                                                                    iframe.contentWindow.focus();
                                                                    iframe.contentWindow.print();

                                                                    setTimeout(() => {
                                                                        document.body.removeChild(iframe);
                                                                    }, 1000);
                                                                }, 500);

                                                            } catch (err) {
                                                                console.error(err);
                                                                alert('Gagal mencetak struk');
                                                            }
                                                        }
                                                    </script>

                                                    {{-- Detail Transaksi --}}
                                                    <button type="button"
                                                        onclick="openDetailModal({{ $trx->id }})"
                                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center justify-start">
                                                        <i class="fa-solid fa-eye mr-1"></i> Detail
                                                    </button>

                                                    {{-- Hapus (Hanya Admin) --}}
                                                    @auth
                                                        @if (auth()->user()->is_admin)
                                                            <form action="{{ route('transaksi.destroy', $trx->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 hover:text-red-800 font-semibold text-sm flex items-center justify-start">
                                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center py-4 text-gray-500 font-medium">
                                                Tidak ada data transaksi pada rentang tanggal ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Modal Detail Transaksi Desktop --}}
                @foreach ($transaksi as $trx)
                    <div id="detailModal{{ $trx->id }}"
                        class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4">

                        <div
                            class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-y-auto max-h-[80vh] p-0 border-t-8 border-blue-600">
                            {{-- Header Modal --}}
                            <div
                                class="bg-blue-600 text-white rounded-t-2xl px-6 py-4 flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Detail Transaksi</h3>
                                <button onclick="closeDetailModal({{ $trx->id }})"
                                    class="text-white hover:text-gray-200 font-bold text-xl">&times;</button>
                            </div>

                            {{-- Nomor Invoice + Kasir --}}
                            <div class="px-6 py-3 border-b border-gray-200 bg-blue-50 flex justify-between">
                                <div>
                                    <span class="text-sm text-gray-700 font-medium">No. Invoice: </span>
                                    <span class="text-gray-900 font-semibold">{{ $trx->no_invoice }}</span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-700 font-medium">Kasir: </span>
                                    <span class="text-gray-900 font-semibold">{{ $trx->user->name ?? '-' }}</span>
                                </div>
                            </div>

                            {{-- Ringkasan Transaksi --}}
                            <div class="px-6 py-3 bg-blue-50 border-b border-gray-200 grid grid-cols-2 gap-2">
                                <div class="flex justify-between text-gray-700">
                                    <span>Pelanggan:</span>
                                    <span class="font-semibold">{{ $trx->customer->nama ?? 'Umum' }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Nomor:</span>
                                    <span class="font-semibold">{{ $trx->customer->no_telepon ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Metode:</span>
                                    <span class="font-semibold">{{ ucfirst($trx->metode_pembayaran) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Sub Total:</span>
                                    <span
                                        class="font-semibold">Rp{{ number_format($trx->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Diskon:</span>
                                    <span
                                        class="font-semibold">Rp{{ number_format($trx->diskon, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Total:</span>
                                    <span class="font-semibold">Rp{{ number_format($trx->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Bayar:</span>
                                    <span
                                        class="font-semibold">Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Kembalian:</span>
                                    <span
                                        class="font-semibold">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Total Modal:</span>
                                    <span
                                        class="font-semibold">Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Profit:</span>
                                    <span
                                        class="font-semibold">Rp{{ number_format($trx->profit, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Tabel Produk --}}
                            <div class="px-6 py-4">
                                <table class="min-w-full text-left border border-gray-200">
                                    <thead class="bg-blue-600">
                                        <tr>
                                            <th class="px-3 py-2 text-white border">No</th>
                                            <th class="px-3 py-2 text-white border">Foto</th>
                                            <th class="px-3 py-2 text-white border">Nama Produk</th>
                                            <th class="px-3 py-2 text-white border">Harga</th>
                                            <th class="px-3 py-2 text-white border">Jumlah</th>
                                            <th class="px-3 py-2 text-white border">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trx->details as $i => $detail)
                                            <tr class="border-b hover:bg-blue-50 transition">
                                                <td class="px-3 py-2 border">{{ $i + 1 }}</td>
                                                <td class="px-3 py-2 border">
                                                    @if ($detail->foto_product)
                                                        <img src="{{ asset($detail->foto_product) }}"
                                                            class="w-12 h-12 object-cover rounded"
                                                            alt="{{ $detail->nama_product }}">
                                                    @else
                                                        <div
                                                            class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded text-gray-400 text-xs">
                                                            No Img
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 border font-semibold text-gray-800">
                                                    {{ $detail->nama_product }}</td>
                                                <td class="px-3 py-2 border text-gray-700">
                                                    Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 border text-gray-700">{{ $detail->jumlah }}</td>
                                                <td class="px-3 py-2 border font-semibold text-gray-800">
                                                    Rp{{ number_format($detail->total, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Script Modal Desktop --}}
                <script>
                    function openDetailModal(id) {
                        document.getElementById('detailModal' + id).classList.remove('hidden');
                    }

                    function closeDetailModal(id) {
                        document.getElementById('detailModal' + id).classList.add('hidden');
                    }
                </script>

                {{-- Mobile Card --}}
                @if ($hasFilter)
                    <div class="md:hidden">
                        @forelse ($transaksi as $trx)
                            <div class="relative shadow-lg p-4 flex flex-col space-y-3 text-gray-700 dark:text-gray-300 text-sm overflow-hidden"
                                style="background-image: url('{{ asset('images/card1.png') }}'); background-size: cover; background-position: center;">

                                {{-- Header: No. Invoice & Tanggal --}}
                                <div class="flex justify-between items-center mb-2">
                                    <div class="text-gray-800 font-semibold text-xs bg-white px-2 py-1 rounded">
                                        No. Invoice: {{ $trx->no_invoice }}
                                    </div>
                                    <div class="flex items-center space-x-1 text-white text-sm font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $trx->created_at->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>

                                {{-- Kasir --}}
                                <div class="flex items-center space-x-2 text-white font-medium">
                                    <i class="fa-solid fa-cash-register"></i>
                                    <span>Kasir: {{ $trx->user->name ?? '-' }}</span>
                                </div>

                                {{-- Ringkasan --}}
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-2">
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-user"></i>
                                        <span><strong>Pelanggan:</strong> {{ $trx->customer->nama ?? 'Umum' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-phone"></i>
                                        <span><strong>Nomor:</strong> {{ $trx->customer->no_telepon ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-credit-card"></i>
                                        <span><strong>Metode:</strong> {{ $trx->metode_pembayaran }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-box"></i>
                                        <span><strong>Subtotal:</strong>
                                            Rp{{ number_format($trx->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-percent"></i>
                                        <span><strong>Diskon:</strong>
                                            Rp{{ number_format($trx->diskon, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-dollar-sign"></i>
                                        <span><strong>Total:</strong>
                                            Rp{{ number_format($trx->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                        <span><strong>Bayar:</strong>
                                            Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-coins"></i>
                                        <span><strong>Kembalian:</strong>
                                            Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                        <span><strong>Total Modal:</strong>
                                            Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-white">
                                        <i class="fa-solid fa-money-bill-trend-up"></i>
                                        <span><strong>Profit:</strong>
                                            Rp{{ number_format($trx->profit, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- Tombol Aksi Mobile --}}
                                <div class="flex flex-wrap items-center gap-2 mt-3">

                                    {{-- Cetak Struk --}}
                                    <button type="button" onclick="cetakStruk({{ $trx->id }})"
                                        class="flex-1 min-w-[120px] flex items-center justify-center bg-white text-amber-600 px-3 py-1 rounded-lg font-semibold text-sm hover:bg-blue-50 hover:text-amber-700 transition">
                                        <i class="fa-solid fa-print mr-1"></i> Cetak Struk
                                    </button>


                                    {{-- Detail --}}
                                    <button type="button" onclick="openDetailModalMobile({{ $trx->id }})"
                                        class="flex-1 min-w-[120px] flex items-center justify-center bg-white text-blue-600 px-3 py-1 rounded-lg font-semibold text-sm hover:bg-green-50 hover:text-blue-700 transition">
                                        <i class="fa-solid fa-eye mr-1"></i> Detail
                                    </button>

                                    @auth
                                        @if (auth()->user()->is_admin)
                                            {{-- Hapus --}}
                                            <button type="button" onclick="openDeleteModal({{ $trx->id }})"
                                                class="flex-1 min-w-[120px] flex items-center justify-center bg-gray-200 text-red-600 px-3 py-1 rounded-lg font-semibold text-sm hover:bg-gray-300 hover:text-red-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                                </svg>
                                                Hapus
                                            </button>
                                        @endif
                                    @endauth
                                </div>

                                {{-- Modal Konfirmasi Hapus Mobile --}}
                                @auth
                                    @if (auth()->user()->is_admin)
                                        <div id="deleteModal{{ $trx->id }}"
                                            class="hidden fixed inset-0 flex items-center justify-center z-50">
                                            <div class="modal-content bg-white bg-cover bg-center rounded-xl shadow-2xl p-6 w-80"
                                                style="background-image: url('{{ asset('images/card1.png') }}');">
                                                <h2 class="text-lg font-semibold text-white mb-3 text-center">Konfirmasi
                                                    Hapus</h2>
                                                <p class="text-sm text-white text-center mb-5">
                                                    Apakah kamu yakin ingin menghapus transaksi ini?
                                                </p>

                                                <div class="flex justify-center gap-3 flex-wrap">
                                                    <form action="{{ route('transaksi.destroy', $trx->id) }}"
                                                        method="POST" class="flex-1 min-w-[100px]">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition duration-200">
                                                            Hapus
                                                        </button>
                                                    </form>

                                                    <button type="button"
                                                        onclick="closeDeleteModal({{ $trx->id }})"
                                                        class="flex-1 min-w-[100px] w-full bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-400 transition duration-200">
                                                        Batal
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endauth

                                {{-- Modal Detail Mobile --}}
                                <div id="detailModalMobile{{ $trx->id }}"
                                    class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4 md:hidden">

                                    <div
                                        class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-y-auto max-h-[80vh] p-0 border-t-8 border-blue-600">
                                        {{-- Header Modal --}}
                                        <div
                                            class="bg-blue-600 text-white rounded-t-2xl px-6 py-4 flex justify-between items-center">
                                            <h3 class="text-lg font-semibold">Detail Transaksi</h3>
                                            <button onclick="closeDetailModalMobile({{ $trx->id }})"
                                                class="text-white hover:text-gray-200 font-bold text-xl">&times;</button>
                                        </div>

                                        {{-- Nomor Invoice & Kasir --}}
                                        <div
                                            class="px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                                            <span class="text-sm text-gray-700 font-medium">
                                                No. Invoice: <span
                                                    class="text-gray-900 font-semibold">{{ $trx->no_invoice }}</span>
                                            </span>

                                            <span class="text-sm text-gray-700 font-medium">
                                                Kasir: <span
                                                    class="font-semibold">{{ $trx->user->name ?? '-' }}</span>
                                            </span>
                                        </div>

                                        {{-- Ringkasan Transaksi --}}
                                        <div
                                            class="px-6 py-3 bg-blue-50 border-b border-gray-200 grid grid-cols-2 gap-2">
                                            <div class="flex justify-between text-gray-700">
                                                <span>Pelanggan:</span>
                                                <span
                                                    class="font-semibold">{{ $trx->customer->nama ?? 'Umum' }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Nomor:</span>
                                                <span
                                                    class="font-semibold">{{ $trx->customer->no_telepon ?? '-' }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Metode:</span>
                                                <span
                                                    class="font-semibold">{{ ucfirst($trx->metode_pembayaran) }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Sub Total:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->subtotal, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Diskon:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->diskon, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Total:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->total, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Bayar:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Kembalian:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Total Modal:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->total_modal, 0, ',', '.') }}</span>
                                            </div>

                                            <div class="flex justify-between text-gray-700">
                                                <span>Profit:</span>
                                                <span
                                                    class="font-semibold">Rp{{ number_format($trx->profit, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                        {{-- List Produk --}}
                                        <div class="px-4 py-3 space-y-3">
                                            @foreach ($trx->details as $i => $detail)
                                                <div
                                                    class="flex space-x-3 items-center p-3 border rounded-lg hover:bg-blue-50 transition">
                                                    <div>
                                                        @if ($detail->foto_product)
                                                            <img src="{{ asset($detail->foto_product) }}"
                                                                class="w-14 h-14 object-cover rounded"
                                                                alt="{{ $detail->nama_product }}">
                                                        @else
                                                            <div
                                                                class="w-14 h-14 bg-gray-200 flex items-center justify-center rounded text-gray-400 text-xs">
                                                                No Img
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="flex-1 text-sm text-gray-700">
                                                        <div class="font-semibold text-gray-800">
                                                            {{ $detail->nama_product }}</div>
                                                        <div class="flex justify-between mt-1 text-gray-600">
                                                            <span>Harga:
                                                                Rp{{ number_format($detail->harga, 0, ',', '.') }}</span>
                                                            <span>Jumlah: {{ $detail->jumlah }}</span>
                                                        </div>
                                                        <div class="mt-1 font-semibold text-gray-800">Total:
                                                            Rp{{ number_format($detail->total, 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Script Modal Mobile --}}
                                <script>
                                    function openDetailModalMobile(id) {
                                        document.getElementById('detailModalMobile' + id).classList.remove('hidden');
                                    }

                                    function closeDetailModalMobile(id) {
                                        document.getElementById('detailModalMobile' + id).classList.add('hidden');
                                    }

                                    function openDeleteModal(id) {
                                        document.getElementById('deleteModal' + id).classList.remove('hidden');
                                    }

                                    function closeDeleteModal(id) {
                                        document.getElementById('deleteModal' + id).classList.add('hidden');
                                    }
                                </script>

                            </div>
                        @empty
                            <div class="text-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                                Tidak ada data transaksi.
                            </div>
                        @endforelse
                    </div>
                @else
                @endif

                {{-- Pagination --}}
                @if ($hasFilter && $transaksi->count())
                    <div class="mt-6">
                        {{ $transaksi->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
