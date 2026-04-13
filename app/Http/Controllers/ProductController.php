<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // ================= ADMIN =================
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->get();

        return view('admin.produk.index', compact('products'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'image.required' => 'Gambar produk wajib diisi.',
            'image.image' => 'Gambar harus berupa file gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.'
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'price' => str_replace(['Rp. ', '.'], '', $request->price),
            'stock' => $request->stock,
            'image' => $imagePath
        ]);

        return redirect('/admin/produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.produk.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
            'image.image' => 'Gambar harus berupa file gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.'
        ]);

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'price' => preg_replace('/[^0-9]/', '', $request->price),
        ]);

        return redirect('/admin/produk')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success_delete', 'Berhasil hapus produk');
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required'
        ], [
            'stock.required' => 'Stok wajib diisi.'
        ]);

        Product::findOrFail($id)->update([
            'stock' => $request->stock
        ]);

        return back()->with('success', 'Stok berhasil diupdate');
    }

    // ================= PETUGAS =================
    public function indexPetugas(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->get();

        return view('petugas.produk.index', compact('products'));
    }
}