<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    /* =========================
       🔎 CEK PRODUK BY BARCODE
    ========================= */
    public function index(Request $request)
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User belum login',
            ], 401);
        }

        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = $request->barcode;

        $product = Product::where('barcode', $barcode)
            ->orWhere('barcode', ltrim($barcode, '0'))
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    /* =========================
       ➕ ADD / INCREMENT CART
    ========================= */
    public function add(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $item = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => 1,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /* =========================
       🛒 GET CART
    ========================= */
    public function cart()
    {
        if (! Auth::check()) {
            return response()->json(['items' => [], 'total' => 0]);
        }

        $items = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $items->sum(fn ($item) => $item->quantity * $item->product->harga
        );

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    /* =========================
       ➕➖ UPDATE QTY
    ========================= */
    public function update(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $item = CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if (! $item) {
            return response()->json(['success' => false], 404);
        }

        // Kalau qty 0 → hapus
        if ($request->quantity < 1) {
            $item->delete();
        } else {
            $item->update(['quantity' => $request->quantity]);
        }

        return response()->json(['success' => true]);
    }

    public function checkStock(Product $product)
    {
        if (! $product) {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'stok' => $product->stok,
        ]);
    }
}
