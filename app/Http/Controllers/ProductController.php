<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter pencarian berdasarkan nama atau barcode
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
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

    // Halaman form tambah produk
    public function create()
    {
        return view('product.create');
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'barcode' => ['required', 'string', 'unique:products,barcode', 'regex:/^\d{12,13}$/'],
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'required|image|max:2048', // max 2MB
        ], [
            'barcode.regex' => 'Barcode harus terdiri dari 12 atau 13 digit.',
        ]);

        $fotoPath = $this->handleUploadFoto($request);

        Product::create([
            'nama' => $request->nama,
            'barcode' => $request->barcode,
            'harga' => $request->harga,
            'modal' => $request->modal,
            'stok' => $request->stok,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('product.index')->with('success', 'Product berhasil ditambahkan');
    }

    // Halaman edit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('product.edit', compact('product'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'barcode' => ['required', 'string', 'unique:products,barcode,'.$id, 'regex:/^\d{12,13}$/'],
            'harga' => 'required|numeric|min:0',
            'modal' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ], [
            'barcode.regex' => 'Barcode harus terdiri dari 12 atau 13 digit.',
        ]);

        $updateData = $request->only(['nama', 'barcode', 'harga', 'modal', 'stok']);

        if ($request->hasFile('foto')) {
            $fotoPath = $this->handleUploadFoto($request, $product->foto);
            $updateData['foto'] = $fotoPath;
        }

        $product->update($updateData);

        return redirect()->route('product.index')->with('success', 'Product berhasil diperbarui');
    }

    // Hapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->foto && File::exists(public_path($product->foto))) {
            File::delete(public_path($product->foto));
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product berhasil dihapus');
    }

    // Menampilkan halaman barcode
    public function show($id)
    {
        $item = Product::findOrFail($id);

        return view('barcode', compact('item'));
    }

    // Fungsi bantu upload foto dan handle foto lama jika ada
    private function handleUploadFoto(Request $request, $oldFoto = null)
    {
        $folderPath = public_path('product_files');
        if (! File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        if ($oldFoto && File::exists(public_path($oldFoto))) {
            File::delete(public_path($oldFoto));
        }

        $fileName = time().'_'.$request->file('foto')->getClientOriginalName();
        $request->file('foto')->move($folderPath, $fileName);

        return 'product_files/'.$fileName;
    }
}
