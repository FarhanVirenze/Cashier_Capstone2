<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = Customer::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('no_telepon', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'asc')
            ->paginate(5)
            ->withQueryString(); // ⬅ supaya pagination tetap bawa search

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kelamin' => 'required|in:L,P',
            'no_telepon' => 'required',
            'alamat' => 'nullable',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy($id)
    {
        Customer::destroy($id);

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
