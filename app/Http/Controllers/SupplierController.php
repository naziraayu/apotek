<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Filter berdasarkan status transaksi
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->active();
            } elseif ($request->status === 'transaksi') {
                $query->hasTransaksi();
            } elseif ($request->status === 'belum_transaksi') {
                $query->doesntHave('pembelian');
            }
        }

        // Filter berdasarkan kota
        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        // Filter berdasarkan bulan & tahun registrasi
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Ambil data dengan relasi dan pagination
        $supplier = $query->withCount('pembelian')
                         ->withSum('pembelian', 'total_harga')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        // Ambil list kota untuk filter
        $kotaList = Supplier::select('kota')
                           ->distinct()
                           ->whereNotNull('kota')
                           ->orderBy('kota')
                           ->pluck('kota');

        return view('supplier.index', compact('supplier', 'kotaList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'kota' => 'nullable|string|max:100',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            Supplier::create([
                'nama_supplier' => $request->nama_supplier,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'kota' => $request->kota,
            ]);

            DB::commit();

            return redirect()->route('supplier.index')
                           ->with('success', 'Data supplier berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supplier = Supplier::with(['pembelian' => function($query) {
                              $query->latest()->limit(10);
                          }])
                          ->withCount('pembelian')
                          ->withSum('pembelian', 'total_harga')
                          ->findOrFail($id);

        return view('supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $id,
            'kota' => 'nullable|string|max:100',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $supplier->update([
                'nama_supplier' => $request->nama_supplier,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'kota' => $request->kota,
            ]);

            DB::commit();

            return redirect()->route('supplier.index')
                           ->with('success', 'Data supplier berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Cek apakah supplier punya transaksi pembelian
            if ($supplier->pembelian()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'Supplier tidak dapat dihapus karena sudah memiliki riwayat pembelian');
            }

            $supplier->delete();

            return redirect()->route('supplier.index')
                           ->with('success', 'Data supplier berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus banyak data sekaligus
     */
    public function destroyAll(Request $request)
    {
        try {
            $ids = $request->ids;

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipilih'
                ]);
            }

            // Cek apakah ada supplier yang punya transaksi
            $supplierWithTransaksi = Supplier::whereIn('id', $ids)
                                            ->has('pembelian')
                                            ->count();

            if ($supplierWithTransaksi > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa supplier tidak dapat dihapus karena memiliki riwayat pembelian'
                ]);
            }

            Supplier::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' supplier berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get data supplier untuk AJAX
     */
    public function data(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $suppliers = $query->withCount('pembelian')
                          ->withSum('pembelian', 'total_harga')
                          ->get();

        return response()->json($suppliers);
    }

    /**
     * Search supplier untuk autocomplete
     */
    public function search(Request $request)
    {
        $term = $request->get('term', '');
        
        $suppliers = Supplier::where('nama_supplier', 'like', "%{$term}%")
                            ->orWhere('kota', 'like', "%{$term}%")
                            ->limit(10)
                            ->get(['id', 'nama_supplier', 'kota', 'no_telp']);

        return response()->json($suppliers);
    }

    /**
     * Check kode supplier (jika nanti ada auto-generate kode)
     */
    public function checkKode(Request $request)
    {
        $kode = $request->get('kode');
        $exists = Supplier::where('kode_supplier', $kode)->exists();
        
        return response()->json(['exists' => $exists]);
    }
}