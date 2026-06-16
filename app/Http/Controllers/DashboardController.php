<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExport;
use App\Models\DetailPenjualan;
use App\Models\Product;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function exportExcel(Request $request)
    {
        $data = $this->index($request)->getData();

        return Excel::download(
            new DashboardExport((array) $data),
            'laporan-dashboard.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        // PANGGIL LOGIKA YANG SAMA DENGAN DASHBOARD
        $data = $this->index($request)->getData();

        $pdf = Pdf::loadView('dashboard.export.pdf', (array) $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-dashboard.pdf');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $filter = $request->get('filter', 'semua');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $filterUserId = $request->get('user_id', 'semua');

        if (! $user->is_admin) {
            $filterUserId = $user->id;
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL (created_at)
        |--------------------------------------------------------------------------
        */
        $queryTanggal = function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
            if ($filter === 'hari_ini') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($filter === 'bulan_ini') {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            } elseif ($filter === 'tahun_ini') {
                $query->whereYear('created_at', Carbon::now()->year);
            } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('created_at', [
                    Carbon::parse($tanggalMulai)->startOfDay(),
                    Carbon::parse($tanggalSelesai)->endOfDay(),
                ]);
            }
        };

        /*
        |--------------------------------------------------------------------------
        | FILTER USER
        |--------------------------------------------------------------------------
        */
        $queryUser = function ($query) use ($user, $filterUserId) {
            if (! $user->is_admin) {
                $query->where('user_id', $user->id);
            } elseif ($filterUserId !== 'semua') {
                $query->where('user_id', $filterUserId);
            }
        };

        /*
        |--------------------------------------------------------------------------
        | DROPDOWN USER
        |--------------------------------------------------------------------------
        */
        $users = User::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | PENJUALAN HARIAN (created_at)
        |--------------------------------------------------------------------------
        */
        $penjualanHarian = TransaksiPenjualan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(total) as total')
        )
            ->when($filter !== 'semua', $queryTanggal)
            ->when(true, $queryUser)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH TRANSAKSI HARIAN (created_at)
        |--------------------------------------------------------------------------
        */
        $transaksiHarian = TransaksiPenjualan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as jumlah')
        )
            ->when($filter !== 'semua', $queryTanggal)
            ->when(true, $queryUser)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOTAL USER
        |--------------------------------------------------------------------------
        */
        $jumlahUser = $user->is_admin ? User::count() : 1;

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERJUAL
        |--------------------------------------------------------------------------
        */
        $produkTerjual = DetailPenjualan::when(true, function ($query) use ($user, $filterUserId) {
            if (! $user->is_admin) {
                $query->whereHas('transaksi', fn ($q) => $q->where('user_id', $user->id));
            } elseif ($filterUserId !== 'semua') {
                $query->whereHas('transaksi', fn ($q) => $q->where('user_id', $filterUserId));
            }
        })
            ->when($filter !== 'semua', $queryTanggal)
            ->sum('jumlah');

        /*
        |--------------------------------------------------------------------------
        | PRODUK & STOK
        |--------------------------------------------------------------------------
        */
        $jumlahProduk = Product::count();
        $stokTersediaChart = Product::orderBy('stok', 'DESC')->take(5)->get();
        $stokTersediaTable = Product::orderBy('nama', 'ASC')->paginate(5, ['*'], 'stok_page');

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS (CHART)
        |--------------------------------------------------------------------------
        */
        $produkTerlarisChart = DetailPenjualan::select(
            'product_id',
            DB::raw('SUM(jumlah) as total')
        )
            ->when(true, function ($query) use ($user, $filterUserId) {
                if (! $user->is_admin) {
                    $query->whereHas('transaksi', fn ($q) => $q->where('user_id', $user->id));
                } elseif ($filterUserId !== 'semua') {
                    $query->whereHas('transaksi', fn ($q) => $q->where('user_id', $filterUserId));
                }
            })
            ->when($filter !== 'semua', $queryTanggal)
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS (TABLE)
        |--------------------------------------------------------------------------
        */
        $produkTerlarisTable = DetailPenjualan::select(
            'product_id',
            DB::raw('SUM(jumlah) as total')
        )
            ->when(true, function ($query) use ($user, $filterUserId) {
                if (! $user->is_admin) {
                    $query->whereHas('transaksi', fn ($q) => $q->where('user_id', $user->id));
                } elseif ($filterUserId !== 'semua') {
                    $query->whereHas('transaksi', fn ($q) => $q->where('user_id', $filterUserId));
                }
            })
            ->when($filter !== 'semua', $queryTanggal)
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total')
            ->paginate(5, ['*'], 'produk_page');

        /*
        |--------------------------------------------------------------------------
        | PROFIT HARIAN (created_at)
        |--------------------------------------------------------------------------
        */
        $profitHarian = DetailPenjualan::join('products', 'detail_penjualan.product_id', '=', 'products.id')
            ->join('transaksi_penjualan as t', 'detail_penjualan.transaksi_penjualan_id', '=', 't.id')
            ->select(
                DB::raw('DATE(t.created_at) as tanggal'),
                DB::raw('SUM((products.harga - products.modal) * detail_penjualan.jumlah) as total_profit')
            )
            ->when(true, function ($query) use ($user, $filterUserId) {
                if (! $user->is_admin) {
                    $query->where('t.user_id', $user->id);
                } elseif ($filterUserId !== 'semua') {
                    $query->where('t.user_id', $filterUserId);
                }
            })
            ->when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
                if ($filter === 'hari_ini') {
                    $query->whereDate('t.created_at', Carbon::today());
                } elseif ($filter === 'bulan_ini') {
                    $query->whereMonth('t.created_at', Carbon::now()->month)
                        ->whereYear('t.created_at', Carbon::now()->year);
                } elseif ($filter === 'tahun_ini') {
                    $query->whereYear('t.created_at', Carbon::now()->year);
                } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                    $query->whereBetween('t.created_at', [
                        Carbon::parse($tanggalMulai)->startOfDay(),
                        Carbon::parse($tanggalSelesai)->endOfDay(),
                    ]);
                }
            })
            ->groupBy(DB::raw('DATE(t.created_at)'))
            ->orderBy('tanggal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PENDAPATAN & TRANSAKSI
        |--------------------------------------------------------------------------
        */
        $totalPendapatan = TransaksiPenjualan::when($filter !== 'semua', $queryTanggal)
            ->when(true, $queryUser)
            ->sum('total') ?? 0;

        $jumlahTransaksi = TransaksiPenjualan::when($filter !== 'semua', $queryTanggal)
            ->when(true, $queryUser)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL PROFIT
        |--------------------------------------------------------------------------
        */
        $totalProfit = DetailPenjualan::join('products', 'detail_penjualan.product_id', '=', 'products.id')
            ->join('transaksi_penjualan as t', 'detail_penjualan.transaksi_penjualan_id', '=', 't.id')
            ->when(true, function ($query) use ($user, $filterUserId) {
                if (! $user->is_admin) {
                    $query->where('t.user_id', $user->id);
                } elseif ($filterUserId !== 'semua') {
                    $query->where('t.user_id', $filterUserId);
                }
            })
            ->when($filter !== 'semua', function ($query) use ($filter, $tanggalMulai, $tanggalSelesai) {
                if ($filter === 'hari_ini') {
                    $query->whereDate('t.created_at', Carbon::today());
                } elseif ($filter === 'bulan_ini') {
                    $query->whereMonth('t.created_at', Carbon::now()->month)
                        ->whereYear('t.created_at', Carbon::now()->year);
                } elseif ($filter === 'tahun_ini') {
                    $query->whereYear('t.created_at', Carbon::now()->year);
                } elseif ($filter === 'rentang' && $tanggalMulai && $tanggalSelesai) {
                    $query->whereBetween('t.created_at', [
                        Carbon::parse($tanggalMulai)->startOfDay(),
                        Carbon::parse($tanggalSelesai)->endOfDay(),
                    ]);
                }
            })
            ->select(DB::raw('SUM((products.harga - products.modal) * detail_penjualan.jumlah) as total_profit'))
            ->value('total_profit') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE PROFIT
        |--------------------------------------------------------------------------
        */
        $persentaseProfit = $totalPendapatan > 0
            ? round(($totalProfit / $totalPendapatan) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | METODE PEMBAYARAN
        |--------------------------------------------------------------------------
        */
        $metodePembayaran = TransaksiPenjualan::select(
            'metode_pembayaran',
            DB::raw('COUNT(*) as total')
        )
            ->when($filter !== 'semua', $queryTanggal)
            ->when(true, $queryUser)
            ->groupBy('metode_pembayaran')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI TERAKHIR
        |--------------------------------------------------------------------------
        */
        $transaksiTerakhir = TransaksiPenjualan::when($filter !== 'semua', $queryTanggal)
            ->when(true, $queryUser)
            ->orderBy('created_at', 'DESC')
            ->take(5)
            ->get();

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
            'tanggalSelesai',
            'users',
            'filterUserId'
        ));
    }
}
