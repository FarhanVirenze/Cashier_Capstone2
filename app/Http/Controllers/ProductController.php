<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter pencarian berdasarkan nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Ambil data dengan pagination
        $product = $query->latest()->paginate(5);

        // Format URL foto agar bisa diakses langsung dari public
        $product->getCollection()->transform(function ($product) {
            $product->foto = $product->foto ? asset($product->foto) : null;
            return $product;
        });

        return view('product.index', compact('product'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode',
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0', // validasi modal
            'stok' => 'required|integer|min:0',
            'foto' => 'required|image|max:2048', // max 2MB
        ]);

        $folderPath = public_path('product_files');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $fileName = time() . '_' . $request->file('foto')->getClientOriginalName();
        $request->file('foto')->move($folderPath, $fileName);

        $fotoPath = 'product_files/' . $fileName;

        Product::create([
            'nama' => $request->nama,
            'barcode' => $request->barcode,
            'harga' => $request->harga,
            'modal' => $request->modal, // simpan modal
            'stok' => $request->stok,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('product.index')->with('success', 'Product berhasil ditambahkan');
    }

    public function show($id)
    {
        $item = Product::findOrFail($id);
        return view('barcode', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode,' . $id,
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0', // validasi modal
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        $updateData = $request->only(['nama', 'barcode', 'harga', 'modal', 'stok']);

        if ($request->hasFile('foto')) {
            $folderPath = public_path('product_files');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Hapus foto lama jika ada
            if ($product->foto && File::exists(public_path($product->foto))) {
                File::delete(public_path($product->foto));
            }

            $fileName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move($folderPath, $fileName);

            $updateData['foto'] = 'product_files/' . $fileName;
        }

        $product->update($updateData);

        return redirect()->route('product.index')->with('success', 'Product berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->foto && File::exists(public_path($product->foto))) {
            File::delete(public_path($product->foto));
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product berhasil dihapus');
    }
}
