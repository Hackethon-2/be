<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // Model Kategori
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Get all categories
    public function getKategoris()
    {
        $kategoris = Kategori::all();
        return response()->json($kategoris);
    }

    // Store a new category
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create($request->all());

        return response()->json(['message' => 'Kategori created successfully', 'kategori' => $kategori], 201);
    }

    // Update category
    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return response()->json(['message' => 'Kategori updated successfully', 'kategori' => $kategori]);
    }

    // Delete category
    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Kategori deleted successfully']);
    }
}
