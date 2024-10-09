<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('check.peternak')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        // Mendapatkan semua produk
        $products = Product::with('photos')->get(); // Memuat foto produk bersamaan dengan produk
        return response()->json($products);
    }

    public function show($id)
    {
        // Menampilkan detail produk beserta fotonya
        $product = Product::with('photos')->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        Log::info('Received request data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'stock' => 'required|integer',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'whatsapp_number' => 'nullable|string|max:20'
        ]);

        // Membuat produk baru
        $product = Product::create([
            'name' => $validated['name'],
            'peternak_id' => Auth::user()->id,
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'stock' => $validated['stock'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
        ]);

        // Menyimpan foto produk
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/photos', $filename);
                // Menyimpan jalur foto ke database
                $product->photos()->create(['photo_path' => $filename]);
            }
        }

        Log::info('Product created:', $product->toArray());

        return response()->json(['product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        Log::info('Received update request data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'stock' => 'required|integer',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'whatsapp_number' => 'nullable|string|max:20'
        ]);

        // Menemukan produk yang akan diperbarui
        $product = Product::findOrFail($id);

        // Memperbarui informasi produk
        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? $product->description,
            'stock' => $validated['stock'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? $product->whatsapp_number,
        ]);

        // Menyimpan foto produk baru jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/photos', $filename);
                // Menyimpan jalur foto ke database
                $product->photos()->create(['photo_path' => $filename]);
            }
        }

        Log::info('Product updated:', $product->toArray());

        return response()->json(['product' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus semua foto terkait sebelum menghapus produk
        foreach ($product->photos as $photo) {
            // Hapus file foto dari penyimpanan
            \Storage::delete('public/photos/' . $photo->photo_path);
            // Hapus entri foto dari database
            $photo->delete();
        }

        // Hapus produk
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
