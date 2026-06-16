<?php

namespace App\Http\Controllers;

use App\Exports\DetailPenjualanExport;
use App\Models\Customer;
use App\Models\DetailPenjualan;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DetailPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailPenjualan::with('transaksi.customer', 'product');

        // Filter tanggal
        if ($request->start_date) {
            $query->whereHas('transaksi', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            });
        }

        if ($request->end_date) {
            $query->whereHas('transaksi', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });
        }

        // Filter produk
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter pelanggan: Semua / Umum / Customer tertentu
       if ($request->filled('customer_id')) {
    if ($request->customer_id === 'umum') {
        $query->whereHas('transaksi', function ($q) {
            $q->whereNull('customer_id');
        });
    } else {
        $query->whereHas('transaksi', function ($q) use ($request) {
            $q->where('customer_id', $request->customer_id);
        });
    }
}

        // Pagination
        $details = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Subtotal halaman aktif
        $subtotal = $details->sum('total');

        $products = Product::orderBy('nama')->get();
        $customers = Customer::orderBy('nama')->get();

        return view('detailpenjualan.index', compact(
            'details',
            'subtotal',
            'products',
            'customers'
        ));
    }

    public function destroy($id)
    {
        DetailPenjualan::findOrFail($id)->delete();

        return back()->with('success', 'Detail penjualan berhasil dihapus.');
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $query = DetailPenjualan::with('transaksi.customer', 'product');

        // Filter sama seperti index
        if ($request->start_date) {
            $query->whereHas('transaksi', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            });
        }
        if ($request->end_date) {
            $query->whereHas('transaksi', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });
        }
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
         if ($request->filled('customer_id')) {
        if ($request->customer_id === 'umum') {
            $query->whereHas('transaksi', function ($q) {
                $q->whereNull('customer_id');
            });
        } else {
            $query->whereHas('transaksi', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }
    }
    
        $details = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('detailpenjualan.export_pdf', compact('details'));

        return $pdf->download('detail-penjualan.pdf');
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(new DetailPenjualanExport($request), 'detail-penjualan.xlsx');
    }
}
