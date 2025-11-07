<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiPenjualanController extends Controller
{
    // 📊 Tampilkan semua transaksi dengan filter tanggal + pencarian
    public function index(Request $request)
    {
        $query = TransaksiPenjualan::query();

        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $search = $request->input('search');

        // === Filter Berdasarkan Tanggal ===
        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($start) {
            $query->where('tanggal', '>=', $start);
        } elseif ($end) {
            $query->where('tanggal', '<=', $end);
        }

        // === Fitur Search ===
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('nomor_pelanggan', 'like', "%{$search}%")
                    ->orWhere('nama_user', 'like', "%{$search}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                    ->orWhere('total', 'like', "%{$search}%")
                    ->orWhere('profit', 'like', "%{$search}%")
                    ->orWhere('tanggal', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        // === Notifikasi ===
        if ($transaksi->count() === 0 && ($start || $end || $search)) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tidak ada data transaksi sesuai filter yang dipilih.');
        }

        if ($transaksi->count() > 0 && ($start || $end || $search)) {
            session()->flash('success', 'Menampilkan '.$transaksi->count().' transaksi hasil pencarian/filter.');
        }

        return view('transaksi.index', compact('transaksi', 'start', 'end', 'search'));
    }

    // 🧾 Hapus transaksi dan detailnya
    public function destroy($id)
    {
        $user = auth()->user();

        if (! $user->is_admin) {
            abort(403, 'Hanya admin yang dapat menghapus transaksi.');
        }

        $transaksi = TransaksiPenjualan::findOrFail($id);
        $transaksi->detailPenjualan()->delete();
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // 🧾 Cetak / preview struk
    public function print(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // Ambil semua data tanpa pagination
        $query = \App\Models\TransaksiPenjualan::query();

        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        }

        $transaksi = $query->orderBy('tanggal', 'asc')->get();

        return view('transaksi.print', compact('transaksi', 'start', 'end'));
    }

public function cetakTransaksi($id)
{
    try {
        $transaksi = \App\Models\TransaksiPenjualan::with('details.product')
            ->findOrFail($id);

        return view('transaksi.cetak_struk', compact('transaksi'));
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}

public function getTransaksiData($id)
{
    $transaksi = \App\Models\TransaksiPenjualan::with('details.product')->findOrFail($id);
    return response()->json($transaksi);
}

    // 📄 Export PDF berdasarkan filter tanggal
    public function exportPdf(Request $request)
    {
        try {
            $start = $request->input('start_date');
            $end = $request->input('end_date');

            if (! $start || ! $end) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Tanggal belum dipilih.'], 400);
                }

                return redirect()->route('transaksi.index')
                    ->with('error', 'Silakan pilih rentang tanggal sebelum mencetak PDF.');
            }

            $transaksi = TransaksiPenjualan::whereBetween('tanggal', [$start, $end])
                ->orderBy('tanggal', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Tidak ada data pada rentang tanggal tersebut.'], 404);
                }

                return redirect()->route('transaksi.index')
                    ->with('error', 'Tidak ada data transaksi pada rentang tanggal tersebut.');
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('transaksi.pdf', compact('transaksi', 'start', 'end'))
                ->setPaper('a4', 'landscape');

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="laporan-penjualan-'.$start.'-sd-'.$end.'.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal generate PDF: '.$e->getMessage());

            return response()->json(['error' => 'Gagal membuat PDF: '.$e->getMessage()], 500);
        }
    }

    // 📦 EXPORT EXCEL (langsung download seperti PDF)
    public function exportExcel(Request $request)
    {
        try {
            $start = $request->input('start_date');
            $end = $request->input('end_date');

            // Validasi tanggal
            if (! $start || ! $end) {
                return redirect()->route('transaksi.index')
                    ->with('error', 'Silakan pilih rentang tanggal sebelum mengekspor Excel.');
            }

            $transaksi = \App\Models\TransaksiPenjualan::whereBetween('tanggal', [$start, $end])
                ->orderBy('tanggal', 'desc')
                ->get();

            // Jika data kosong
            if ($transaksi->isEmpty()) {
                return redirect()->route('transaksi.index')
                    ->with('error', 'Tidak ada data transaksi pada rentang tanggal tersebut.');
            }

            // 💾 Gunakan class export & langsung kirim sebagai response download
            $fileName = "laporan-penjualan-{$start}-sd-{$end}.xlsx";

            return Excel::download(new \App\Exports\TransaksiPenjualanExport($transaksi), $fileName);
        } catch (\Exception $e) {
            \Log::error('Gagal export Excel: '.$e->getMessage());

            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal membuat file Excel: '.$e->getMessage());
        }
    }
}
