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
            ->tersedia() // Hanya obat yang stok > 0
            ->tidakKadaluarsa(); // Tidak kadaluarsa

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('nama_obat', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama_obat', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('harga_jual', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('harga_jual', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $obats = $query->paginate(12)->appends($request->all());
        $kategoris = KategoriObat::has('obat')->orderBy('nama_kategori')->get();

        return view('shop.index', compact('obats', 'kategoris'));
    }

    /**
     * Display detail obat
     */
    public function show($id)
    {
        $obat = Obat::with('kategori')
            ->where('stok', '>', 0)
            ->whereNotNull('harga_jual')
            ->findOrFail($id);

        // Cek apakah kadaluarsa
        if ($obat->isExpired()) {
            return redirect()->route('shop.index')
                ->with('error', 'Obat ini sudah kadaluarsa dan tidak tersedia untuk dibeli');
        }

        // Obat terkait (dari kategori yang sama)
        $relatedObats = Obat::with('kategori')
            ->where('kategori_id', $obat->kategori_id)
            ->where('id', '!=', $id)
            ->tersedia()
            ->tidakKadaluarsa()
            ->limit(4)
            ->get();

        return view('shop.show', compact('obat', 'relatedObats'));
    }
}