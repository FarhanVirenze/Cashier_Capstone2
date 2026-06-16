<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Customer;
use App\Models\DetailPenjualan;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $customers = Customer::orderBy('nama')->get();

        $subtotal = $items->sum(fn($item) => $item->product->harga * $item->quantity);
        $diskon = 0;
        $totalBayar = $subtotal - $diskon;

        $snapToken = null;

        if ($items->isNotEmpty()) {
            Config::$serverKey = config('midtrans.server_key');
            Config::$clientKey = config('midtrans.client_key');
            Config::$isProduction = true;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $itemDetails = $items->map(fn($item) => [
                'id' => $item->product->id,
                'price' => (int)$item->product->harga,
                'quantity' => (int)$item->quantity,
                'name' => $item->product->nama,
            ])->toArray();

            $params = [
                'transaction_details' => [
                    'order_id' => 'DUMMY-'.now()->format('YmdHis'),
                    'gross_amount' => $totalBayar,
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($params);
        }

        return view('cart.index', compact('items','customers','subtotal','totalBayar','snapToken'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id_customer',
            'metode_pembayaran' => 'required|in:cash,midtrans',
            'jumlah_bayar' => 'nullable|numeric|min:0',
        ]);

        $items = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        // Hitung subtotal dan diskon
        $subtotal = $items->sum(fn($i) => $i->product->harga * $i->quantity);
        $diskon = $request->customer_id ? round($subtotal * 0.05) : 0;
        $totalBayar = $subtotal - $diskon;

        // Validasi cash
        if ($request->metode_pembayaran === 'cash' && $request->jumlah_bayar < $totalBayar) {
            return back()->with('error', 'Jumlah bayar kurang dari total');
        }

        $jumlahBayar = $request->metode_pembayaran === 'cash' ? $request->jumlah_bayar : $totalBayar;
        $kembalian = $request->metode_pembayaran === 'cash' ? $jumlahBayar - $totalBayar : 0;

        $totalModal = $items->sum(fn($i) => $i->product->modal * $i->quantity);
        $profit = $totalBayar - $totalModal;

        $noInvoice = 'INV-'.now()->format('YmdHis').'-'.rand(100,999);

        // Simpan transaksi
        DB::transaction(function() use ($items, $request, $noInvoice, $subtotal, $diskon, $totalBayar, $jumlahBayar, $kembalian, $totalModal, $profit, &$transaksi) {
            $transaksi = TransaksiPenjualan::create([
                'no_invoice' => $noInvoice,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $request->metode_pembayaran === 'cash' ? 'paid' : 'pending',
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $totalBayar,
                'jumlah_bayar' => $jumlahBayar,
                'kembalian' => $kembalian,
                'total_modal' => $totalModal,
                'profit' => $profit,
                'cetak_struk' => true,
            ]);

            foreach ($items as $item) {
                $totalItem = $item->product->harga * $item->quantity;
                $diskonItem = $diskon > 0 ? round($totalItem / $subtotal * $diskon) : 0;
                $totalSetelahDiskon = $totalItem - $diskonItem;

                DetailPenjualan::create([
                    'transaksi_penjualan_id' => $transaksi->id,
                    'product_id' => $item->product->id,
                    'nama_product' => $item->product->nama,
                    'foto_product' => $item->product->foto,
                    'harga' => $item->product->harga,
                    'jumlah' => $item->quantity,
                    'total' => $totalSetelahDiskon,
                ]);

                $item->product->decrement('stok', $item->quantity);
            }

            CartItem::where('user_id', Auth::id())->delete();
        });

        $transaksi->load(['user','customer','details']);

        // Jika cash
        if ($request->metode_pembayaran === 'cash') {
            return redirect()->route('cart.index')->with([
                'success' => 'Struk dicetak. Kembalian: Rp '.number_format($kembalian,0,',','.'),
                'print_transaksi' => $transaksi,
            ]);
        }

        // MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = true;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Diskon per item proporsional
        $itemDetails = $items->map(function($item) use ($diskon, $subtotal) {
            $totalItem = $item->product->harga * $item->quantity;
            $diskonItem = $diskon > 0 ? round($totalItem / $subtotal * $diskon) : 0;
            $priceAfterDiscount = $item->quantity > 0 ? ceil(($totalItem - $diskonItem) / $item->quantity) : 0;

            return [
                'id' => $item->product->id,
                'price' => (int)$priceAfterDiscount,
                'quantity' => (int)$item->quantity,
                'name' => $item->product->nama.($diskon > 0 ? ' (Diskon 5% diterapkan)' : ''),
            ];
        })->toArray();

        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->no_invoice,
                'gross_amount' => $totalBayar,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => optional($transaksi->customer)->nama ?? 'Umum',
                'email' => optional($transaksi->customer)->email ?? 'example@email.com',
                'phone' => optional($transaksi->customer)->no_telepon ?? '08123456789',
            ],
            'enabled_payments' => ['qris','gopay','shopeepay','bank_transfer'],
        ];

        $snapToken = Snap::getSnapToken($params);

        $customers = Customer::orderBy('nama')->get();

        return view('cart.index', [
            'items' => collect([]),
            'customers' => $customers,
            'subtotal' => 0,
            'totalBayar' => 0,
            'snapToken' => $snapToken,
        ])->with('print_transaksi', $transaksi)
          ->with('success', 'Silakan selesaikan pembayaran melalui Midtrans');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Admin & user SAMA: hanya boleh update cart milik sendiri
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $quantity = $request->quantity;

        if ($quantity == 0) {
            $cartItem->delete();

            return back()->with('success', 'Produk telah dihapus dari keranjang.');
        }

        $product = $cartItem->product;

        if ($quantity > $product->stok) {
            return back()->with(
                'error',
                'Jumlah melebihi stok tersedia ('.$product->stok.').'
            );
        }

        $cartItem->update(['quantity' => $quantity]);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function destroy(CartItem $cartItem)
    {
        // Admin & user SAMA: hanya boleh hapus cart milik sendiri
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
