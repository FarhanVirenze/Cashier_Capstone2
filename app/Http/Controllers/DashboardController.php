<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Product;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 Ambil filter dari request
        $filter = $request->get('filter', 'semua'); // semua | hari_ini | bulan_ini | tahun_ini | rentang
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        // 🔹 Query filter reusable untuk tabel TransaksiPenjualan
        $queryFilter = function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
            if ($filter === 'hari_ini') {
                $query->whereDate('tanggal', Carbon::today());
            } elseif ($filter === 'bulan_ini') {
                $query->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year);
            } elseif ($filter === 'tahun_ini') {
                $query->whereYear('tanggal', Carbon::now()->year);
            } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            }
        };

        /* ============================================================
         * 1️⃣ PENJUALAN HARIAN (Grafik Pendapatan)
         * ============================================================ */
        $penjualanHarian = TransaksiPenjualan::select(
            DB::raw('DATE(tanggal) as tanggal'),
            DB::raw('SUM(total) as total')
        )
            ->when($filter !== 'semua', $queryFilter)
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal', 'ASC')
            ->get();

        /* ============================================================
         * 2️⃣ JUMLAH TRANSAKSI HARIAN
         * ============================================================ */
        $transaksiHarian = TransaksiPenjualan::select(
            DB::raw('DATE(tanggal) as tanggal'),
            DB::raw('COUNT(*) as jumlah')
        )
            ->when($filter !== 'semua', $queryFilter)
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal', 'ASC')
            ->get();

        /* ============================================================
         * 3️⃣ TOTAL PENGGUNA DAN PRODUK TERJUAL
         * ============================================================ */
        $jumlahUser = User::count();

        $produkTerjual = DetailPenjualan::when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
            if ($filter === 'hari_ini') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($filter === 'bulan_ini') {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            } elseif ($filter === 'tahun_ini') {
                $query->whereYear('created_at', Carbon::now()->year);
            } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
            }
        })->sum('jumlah');

        /* ============================================================
         * 4️⃣ TOTAL PRODUK & STOK TERSEDIA (Top 5)
         * ============================================================ */
        $jumlahProduk = Product::count();
        // Untuk chart (Top 5)
        $stokTersediaChart = Product::orderBy('stok', 'DESC')->take(5)->get();

        // Untuk tabel (semua)
        $stokTersediaTable = Product::orderBy('nama', 'ASC')
            ->paginate(5, ['*'], 'stok_page');

        /* ============================================================
         * 5️⃣ PRODUK TERLARIS (Top 5)
         * ============================================================ */
        $produkTerlarisChart = DetailPenjualan::select('product_id', DB::raw('SUM(jumlah) as total'))
            ->when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
                if ($filter === 'hari_ini') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($filter === 'bulan_ini') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                } elseif ($filter === 'tahun_ini') {
                    $query->whereYear('created_at', Carbon::now()->year);
                } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                    $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
                }
            })
            ->groupBy('product_id')
            ->orderBy('total', 'DESC')
            ->with('product')
            ->take(5)
            ->get();

        // Untuk tabel (Top 5 / paginasi), sekarang pakai filter juga
        $produkTerlarisTable = DetailPenjualan::select('product_id', DB::raw('SUM(jumlah) as total'))
            ->when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
                if ($filter === 'hari_ini') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($filter === 'bulan_ini') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                } elseif ($filter === 'tahun_ini') {
                    $query->whereYear('created_at', Carbon::now()->year);
                } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                    $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
                }
            })
            ->groupBy('product_id')
            ->with('product')
            ->orderBy('total', 'DESC')
            ->paginate(5, ['*'], 'produk_page');
            
        /* ============================================================
         * 6️⃣ PROFIT HARIAN (Grafik Profit)
         * ============================================================ */
        $profitHarian = DetailPenjualan::join('products', 'detail_penjualan.product_id', '=', 'products.id')
            ->select(
                DB::raw('DATE(detail_penjualan.created_at) as tanggal'),
                DB::raw('SUM((products.harga - products.modal) * detail_penjualan.jumlah) as total_profit')
            )
            ->when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
                if ($filter === 'hari_ini') {
                    $query->whereDate('detail_penjualan.created_at', Carbon::today());
                } elseif ($filter === 'bulan_ini') {
                    $query->whereMonth('detail_penjualan.created_at', Carbon::now()->month)
                        ->whereYear('detail_penjualan.created_at', Carbon::now()->year);
                } elseif ($filter === 'tahun_ini') {
                    $query->whereYear('detail_penjualan.created_at', Carbon::now()->year);
                } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                    $query->whereBetween('detail_penjualan.created_at', [$tanggalMulai, $tanggalSelesai]);
                }
            })
            ->groupBy(DB::raw('DATE(detail_penjualan.created_at)'))
            ->orderBy('tanggal', 'ASC')
            ->get();

        /* ============================================================
         * 7️⃣ TOTAL PENDAPATAN & JUMLAH TRANSAKSI
         * ============================================================ */
        $totalPendapatan = TransaksiPenjualan::when($filter !== 'semua', $queryFilter)->sum('total') ?? 0;
        $jumlahTransaksi = TransaksiPenjualan::when($filter !== 'semua', $queryFilter)->count();

        /* ============================================================
         * 8️⃣ TOTAL PROFIT & PERSENTASE PROFIT
         * ============================================================ */
        $totalProfit = DetailPenjualan::join('products', 'detail_penjualan.product_id', '=', 'products.id')
            ->when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
                if ($filter === 'hari_ini') {
                    $query->whereDate('detail_penjualan.created_at', Carbon::today());
                } elseif ($filter === 'bulan_ini') {
                    $query->whereMonth('detail_penjualan.created_at', Carbon::now()->month)
                        ->whereYear('detail_penjualan.created_at', Carbon::now()->year);
                } elseif ($filter === 'tahun_ini') {
                    $query->whereYear('detail_penjualan.created_at', Carbon::now()->year);
                } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                    $query->whereBetween('detail_penjualan.created_at', [$tanggalMulai, $tanggalSelesai]);
                }
            })
            ->select(DB::raw('SUM((products.harga - products.modal) * detail_penjualan.jumlah) as total_profit'))
            ->first()
            ->total_profit ?? 0;

        $persentaseProfit = $totalPendapatan > 0 ? round(($totalProfit / $totalPendapatan) * 100, 2) : 0;

        /* ============================================================
         * 9️⃣ DISTRIBUSI METODE PEMBAYARAN
         * ============================================================ */
        $metodePembayaran = TransaksiPenjualan::select('metode_pembayaran', DB::raw('COUNT(*) as total'))
            ->when($filter !== 'semua', $queryFilter)
            ->groupBy('metode_pembayaran')
            ->get();

        /* ============================================================
         * 🔟 TRANSAKSI TERAKHIR (5 terakhir)
         * ============================================================ */
        $transaksiTerakhir = TransaksiPenjualan::when($filter !== 'semua', $queryFilter)
            ->orderBy('tanggal', 'DESC')
            ->take(5)
            ->get();

        /* ============================================================
         * 🔚 RETURN KE VIEW
         * ============================================================ */
        return view('dashboard', compact(
            'penjualanHarian',
            'transaksiHarian',
            'produkTerjual',
            'stokTersediaChart',
            'stokTersediaTable',
            'produkTerlarisChart',
            'produkTerlarisTable',
            'totalPendapatan',
            'jumlahTransaksi',
            'jumlahUser',
            'jumlahProduk',
            'metodePembayaran',
            'profitHarian',
            'totalProfit',
            'persentaseProfit',
            'transaksiTerakhir',
            'filter',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }
}
