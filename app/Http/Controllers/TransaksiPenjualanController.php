<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiPenjualan::with('details', 'customer', 'user'); // eager load relasi

        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $search = $request->input('search');

        // === FILTER TANGGAL ===
        if ($start && $end) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
        } elseif ($start) {
            $query->whereDate('created_at', '>=', $start);
        } elseif ($end) {
            $query->whereDate('created_at', '<=', $end);
        }

        // === SEARCH ===
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                    ->orWhere('total', 'like', "%{$search}%")
                    ->orWhere('profit', 'like', "%{$search}%")
                    ->orWhereDate('created_at', $search)
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhere('no_telepon', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $transaksi = $query->latest('created_at')
            ->paginate(6)
            ->withQueryString();

        // === HITUNG SUBTOTAL DINAMIS ===
        $transaksi->each(function ($trx) {
            $trx->subtotal = $trx->details->reduce(function ($carry, $item) {
                return $carry + ($item->harga * $item->jumlah);
            }, 0);
        });

        // === NOTIFIKASI ===
        if ($transaksi->count() === 0 && ($start || $end || $search)) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada data transaksi sesuai filter yang dipilih.');
        }

        if ($transaksi->count() > 0 && ($start || $end || $search)) {
            session()->flash(
                'success',
                'Menampilkan '.$transaksi->count().' transaksi hasil pencarian/filter.'
            );
        }

        return view('transaksi.index', compact('transaksi', 'start', 'end', 'search'));
    }

    // 🧾 Hapus transaksi
    public function destroy($id)
    {
        $user = auth()->user();

        if (! $user->is_admin) {
            abort(403, 'Hanya admin yang dapat menghapus transaksi.');
        }

        $transaksi = TransaksiPenjualan::findOrFail($id);
        $transaksi->detailPenjualan()->delete();
        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    // 🧾 PRINT PREVIEW
    public function print(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $query = TransaksiPenjualan::query();

        if ($start && $end) {
            $query->whereBetween(
                DB::raw('DATE(created_at)'),
                [$start, $end]
            );
        }

        $transaksi = $query
            ->orderBy('created_at', 'asc')
            ->get();

        return view('transaksi.print', compact('transaksi', 'start', 'end'));
    }

    // 🧾 CETAK STRUK
    public function cetakTransaksi($id)
    {
        $transaksi = TransaksiPenjualan::with(['details.product', 'customer', 'user'])
            ->findOrFail($id);

        // Hitung subtotal
        $transaksi->subtotal = $transaksi->details->sum(fn ($item) => $item->harga * $item->jumlah);

        // Kirim ke JS (struk)
        $trx = $transaksi->toArray(); // <-- ini penting supaya relasi & subtotal ikut

        return view('transaksi.cetak_struk', compact('trx'));
    }

    public function getTransaksiData($id)
    {
        // Ambil transaksi beserta relasi lengkap
        $transaksi = TransaksiPenjualan::with(['details.product', 'customer', 'user'])
            ->findOrFail($id);

        // Hitung subtotal berdasarkan detail
        $transaksi->subtotal = $transaksi->details->sum(fn ($item) => $item->harga * $item->jumlah);

        // Kirim JSON lengkap ke JS
        return response()->json($transaksi);
    }
    
    // 📄 EXPORT PDF
    public function exportPdf(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if (! $start || ! $end) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Silakan pilih rentang tanggal sebelum mencetak PDF.');
        }

        // Ambil transaksi beserta relasinya (details, customer, user)
        $transaksi = TransaksiPenjualan::with('details', 'customer', 'user')
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada data transaksi pada rentang tanggal tersebut.');
        }

        // Hitung subtotal untuk masing-masing transaksi
        foreach ($transaksi as $trx) {
            $trx->subtotal = $trx->details->reduce(function ($carry, $item) {
                return $carry + ($item->harga * $item->jumlah);
            }, 0);
        }

        $pdf = Pdf::loadView('transaksi.pdf', compact('transaksi', 'start', 'end'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("laporan-penjualan-{$start}-sd-{$end}.pdf");
    }

    // 📦 EXPORT EXCEL
    public function exportExcel(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if (! $start || ! $end) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Silakan pilih rentang tanggal sebelum mengekspor Excel.');
        }

        // Ambil transaksi beserta relasinya (details, customer, user)
        $transaksi = TransaksiPenjualan::with('details', 'customer', 'user')
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada data transaksi pada rentang tanggal tersebut.');
        }

        // Hitung subtotal untuk masing-masing transaksi
        foreach ($transaksi as $trx) {
            $trx->subtotal = $trx->details->reduce(function ($carry, $item) {
                return $carry + ($item->harga * $item->jumlah);
            }, 0);
        }

        $fileName = "laporan-penjualan-{$start}-sd-{$end}.xlsx";

        return Excel::download(
            new \App\Exports\TransaksiPenjualanExport($transaksi),
            $fileName
        );
    }
}
