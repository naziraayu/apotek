<?php

namespace App\Http\Controllers;

use App\Models\KategoriObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KategoriObat::query()->withCount('obat');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status == 'has_obat') {
                $query->has('obat');
            } elseif ($request->status == 'empty') {
                $query->doesntHave('obat');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kategoris = $query->latest()->paginate(10);

        return view('kategori-obat.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori-obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100|unique:kategori_obat,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            KategoriObat::create([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('kategori-obat.index')
                ->with('success', 'Kategori obat berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kategori obat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = KategoriObat::with(['obat' => function($query) {
            $query->latest();
        }])->findOrFail($id);

        return view('kategori-obat.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriObat::findOrFail($id);
        return view('kategori-obat.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = KategoriObat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100|unique:kategori_obat,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('kategori-obat.index')
                ->with('success', 'Kategori obat berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kategori obat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kategori = KategoriObat::findOrFail($id);
            
            // Check if kategori has obat
            if ($kategori->obat()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $kategori->obat()->count() . ' obat');
            }

            $kategori->delete();

            return redirect()->route('kategori-obat.index')
                ->with('success', 'Kategori obat berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus kategori obat: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple categories
     */
    public function destroyAll(Request $request)
    {
        try {
            $ids = $request->ids;
            
            // Check if any kategori has obat
            $kategorisWithObat = KategoriObat::whereIn('id', $ids)
                ->has('obat')
                ->get();

            if ($kategorisWithObat->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa kategori tidak dapat dihapus karena masih memiliki obat'
                ], 422);
            }

            KategoriObat::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kategori data for AJAX
     */
    public function data(Request $request)
    {
        $kategoris = KategoriObat::withCount('obat')
            ->latest()
            ->get();

        return response()->json($kategoris);
    }

    /**
     * Check if kategori name exists
     */
    public function checkKode(Request $request)
    {
        $exists = KategoriObat::where('nama_kategori', $request->nama_kategori)
            ->when($request->id, function($query) use ($request) {
                return $query->where('id', '!=', $request->id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}