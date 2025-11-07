<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\DetailPenjualan;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            // Admin melihat semua cart items dengan user dan produk
            $items = CartItem::with(['product', 'user'])->get();
        } else {
            // User biasa hanya melihat cart miliknya sendiri
            $items = CartItem::with('product')->where('user_id', Auth::id())->get();
        }

        return view('cart.index', compact('items'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'nullable|string|max:255',
            'nomor_pelanggan' => 'nullable|string|max:50',
            'metode_pembayaran' => 'required|in:cash,qris',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        $items = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        // Hitung total bayar, modal, dan profit
        $totalBayar = $items->sum(fn ($item) => $item->product->harga * $item->quantity);
        $totalModal = $items->sum(fn ($item) => $item->product->modal * $item->quantity);
        $profit = $totalBayar - $totalModal;

        if ($request->jumlah_bayar < $totalBayar) {
            return back()->with('error', 'Jumlah bayar kurang dari total');
        }

        $kembalian = $request->jumlah_bayar - $totalBayar;

        // Generate no_invoice unik, misal INV + timestamp
        $noInvoice = 'INV'.now()->format('YmdHis');

        $transaksi = null;

        DB::transaction(function () use ($items, $request, $totalBayar, $kembalian, $totalModal, $profit, $noInvoice, &$transaksi) {
            // Simpan transaksi penjualan
            $transaksi = TransaksiPenjualan::create([
                'no_invoice' => $noInvoice,
                'tanggal' => now()->toDateString(),
                'nama_pelanggan' => $request->nama_pelanggan,
                'nomor_pelanggan' => $request->nomor_pelanggan,
                'nama_user' => Auth::user()->name,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar' => $request->jumlah_bayar,
                'total' => $totalBayar,
                'kembalian' => $kembalian,
                'total_modal' => $totalModal,
                'profit' => $profit,
                'cetak_struk' => true,
            ]);

            foreach ($items as $item) {
                logger($item->product->foto); // cek di log
                DetailPenjualan::create([
                    'transaksi_penjualan_id' => $transaksi->id,
                    'product_id' => $item->product->id,
                    'nama_product' => $item->product->nama,
                    'foto_product' => $item->product->foto,
                    'harga' => $item->product->harga,
                    'jumlah' => $item->quantity,
                    'total' => $item->product->harga * $item->quantity,
                ]);

                // Kurangi stok produk
                $item->product->decrement('stok', $item->quantity);
            }

            // Hapus item di keranjang
            CartItem::where('user_id', Auth::id())->delete();
        });

        // Simpan transaksi untuk dicetak di session
        session(['print_transaksi' => $transaksi]);

        return redirect()->route('cart.index')->with([
            'success' => 'Struk dicetak. Kembalian: Rp '.number_format($kembalian, 0, ',', '.'),
        ]);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // User biasa hanya bisa update keranjang miliknya sendiri
        if (! Auth::user()->is_admin && $cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer',
        ]);

        $quantity = $request->quantity;

        if ($quantity < 1) {
            return back()->with('error', 'Jumlah minimal adalah 1.');
        }

        $product = $cartItem->product;

        if ($quantity > $product->stok) {
            return back()->with('error', 'Jumlah melebihi stok tersedia ('.$product->stok.').');
        }

        $cartItem->update(['quantity' => $quantity]);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function destroy(CartItem $cartItem)
    {
        // Admin boleh hapus semua cart item
        if (! Auth::user()->is_admin) {
            return back()->with('error', 'Hanya admin yang dapat menghapus item dari keranjang.');
        }

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang');
    }
}
