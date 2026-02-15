<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\KategoriObat;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display katalog obat untuk pelanggan
     */
    public function index(Request $request)
    {
        $query = Obat::with('kategori')
            ->tersedia() // Hanya obat yang tersedia
            ->tidakKadaluarsa(); // Tidak kadaluarsa

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $obats = $query->latest()->paginate(12);
        $kategoris = KategoriObat::has('obat')->get();

        return view('shop.index', compact('obats', 'kategoris'));
    }

    /**
     * Display detail obat
     */
    public function show($id)
    {
        $obat = Obat::with('kategori')
            ->where('stok', '>', 0)
            ->findOrFail($id);

        return view('shop.show', compact('obat'));
    }
}