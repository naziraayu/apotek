<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelian;
use App\Models\Obat;
use App\Models\Pembelian;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'user', 'details']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pembelian', [
                $request->tanggal_mulai,
                $request->tanggal_akhir
            ]);
        }

        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_pembelian', $request->bulan)
                  ->whereYear('tanggal_pembelian', $request->tahun);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal_pembelian', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_nota', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('nama_supplier', 'like', "%{$search}%");
                  });
            });
        }

        $pembelian = $query->latest('tanggal_pembelian')
                          ->paginate(10)
                          ->appends($request->all());

        $suppliers = Supplier::orderBy('nama_supplier')->get();

        // Data untuk statistik
        $totalPembelian = Pembelian::sum('total_harga');
        $totalDiskon = Pembelian::sum('diskon');
        $pembelianBulanIni = Pembelian::bulanIni()->count();
        $pembelianPending = Pembelian::pending()->count();

        return view('pembelian.index', compact(
            'pembelian',
            'suppliers',
            'totalPembelian',
            'totalDiskon',
            'pembelianBulanIni',
            'pembelianPending'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $noNota = Pembelian::generateNoNota();
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $obat = Obat::where('stok', '>', 0)
                    ->orWhere('stok', '>=', 0)
                    ->orderBy('nama_obat')
                    ->get();

        return view('pembelian.create', compact('noNota', 'suppliers', 'obat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'diskon' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'required|exists:obat,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|numeric|min:1',
            'harga_beli' => 'required|array|min:1',
            'harga_beli.*' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'nullable|array',
            'tanggal_kadaluarsa.*' => 'nullable|date|after:today',
            'no_batch' => 'nullable|array',
            'no_batch.*' => 'nullable|string|max:50',
        ], [
            'supplier_id.required' => 'Supplier harus dipilih',
            'tanggal_pembelian.required' => 'Tanggal pembelian harus diisi',
            'obat_id.required' => 'Minimal 1 obat harus dipilih',
            'obat_id.*.exists' => 'Obat tidak valid',
            'jumlah.*.min' => 'Jumlah minimal 1',
            'harga_beli.*.min' => 'Harga beli tidak boleh negatif',
            'tanggal_kadaluarsa.*.after' => 'Tanggal kadaluarsa harus setelah hari ini',
        ]);

        DB::beginTransaction();
        try {
            // Create Pembelian
            $pembelian = Pembelian::create([
                'no_nota' => Pembelian::generateNoNota(),
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'total_harga' => 0,
                'diskon' => $request->diskon ?? 0,
                'status' => 'pending',
                'keterangan' => $request->keterangan,
            ]);

            // Create Detail Pembelian
            $totalHarga = 0;
            foreach ($request->obat_id as $index => $obatId) {
                $jumlah = $request->jumlah[$index];
                $hargaBeli = $request->harga_beli[$index];
                $subtotal = $jumlah * $hargaBeli;
                $totalHarga += $subtotal;

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'obat_id' => $obatId,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $subtotal,
                    'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa[$index] ?? null,
                    'no_batch' => $request->no_batch[$index] ?? null,
                ]);
            }

            // Update total harga
            $pembelian->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('pembelian.show', $pembelian->id)
                           ->with('success', 'Data pembelian berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'details.obat'])->findOrFail($id);
        
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pembelian = Pembelian::with(['details.obat'])->findOrFail($id);
        
        // Hanya bisa edit jika status pending
        if ($pembelian->status !== 'pending') {
            return redirect()->route('pembelian.index')
                           ->with('error', 'Hanya pembelian dengan status pending yang bisa diedit');
        }

        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $obat = Obat::orderBy('nama_obat')->get();

        return view('pembelian.edit', compact('pembelian', 'suppliers', 'obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Handle action selesai atau batal
        if ($request->has('action')) {
            if ($request->action === 'selesai') {
                try {
                    $pembelian->selesai();
                    return redirect()->route('pembelian.show', $pembelian->id)
                                   ->with('success', 'Pembelian berhasil diselesaikan. Stok telah diupdate.');
                } catch (\Exception $e) {
                    return redirect()->back()
                                   ->with('error', 'Gagal menyelesaikan pembelian: ' . $e->getMessage());
                }
            } elseif ($request->action === 'batal') {
                try {
                    $pembelian->batal();
                    return redirect()->route('pembelian.show', $pembelian->id)
                                   ->with('success', 'Pembelian berhasil dibatalkan.');
                } catch (\Exception $e) {
                    return redirect()->back()
                                   ->with('error', 'Gagal membatalkan pembelian: ' . $e->getMessage());
                }
            }
        }

        // Hanya bisa update jika status pending
        if ($pembelian->status !== 'pending') {
            return redirect()->route('pembelian.index')
                           ->with('error', 'Hanya pembelian dengan status pending yang bisa diupdate');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'diskon' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'required|exists:obat,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|numeric|min:1',
            'harga_beli' => 'required|array|min:1',
            'harga_beli.*' => 'required|numeric|min:0',
            'tanggal_kadaluarsa' => 'nullable|array',
            'tanggal_kadaluarsa.*' => 'nullable|date|after:today',
            'no_batch' => 'nullable|array',
            'no_batch.*' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Update Pembelian
            $pembelian->update([
                'supplier_id' => $request->supplier_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'diskon' => $request->diskon ?? 0,
                'keterangan' => $request->keterangan,
            ]);

            // Delete old details
            $pembelian->details()->delete();

            // Create new details
            $totalHarga = 0;
            foreach ($request->obat_id as $index => $obatId) {
                $jumlah = $request->jumlah[$index];
                $hargaBeli = $request->harga_beli[$index];
                $subtotal = $jumlah * $hargaBeli;
                $totalHarga += $subtotal;

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'obat_id' => $obatId,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $subtotal,
                    'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa[$index] ?? null,
                    'no_batch' => $request->no_batch[$index] ?? null,
                ]);
            }

            // Update total harga
            $pembelian->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('pembelian.show', $pembelian->id)
                           ->with('success', 'Data pembelian berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pembelian = Pembelian::findOrFail($id);

            // Tidak bisa hapus jika sudah selesai
            if ($pembelian->status === 'selesai') {
                return redirect()->back()
                               ->with('error', 'Tidak dapat menghapus pembelian yang sudah selesai');
            }

            $pembelian->delete();

            return redirect()->route('pembelian.index')
                           ->with('success', 'Data pembelian berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple records
     */
    public function destroyAll(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pembelian,id'
        ]);

        try {
            $pembelian = Pembelian::whereIn('id', $request->ids)
                                 ->where('status', '!=', 'selesai')
                                 ->get();

            foreach ($pembelian as $item) {
                $item->delete();
            }

            return response()->json([
                'success' => true,
                'message' => count($pembelian) . ' data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get obat data for AJAX
     */
    public function getObat(Request $request)
    {
        $search = $request->get('q');
        
        $obat = Obat::when($search, function($query) use ($search) {
                    return $query->where('nama_obat', 'like', "%{$search}%")
                                 ->orWhere('kode_obat', 'like', "%{$search}%");
                })
                ->orderBy('nama_obat')
                ->limit(10)
                ->get(['id', 'nama_obat', 'kode_obat', 'harga_beli', 'harga_jual', 'stok', 'satuan']);

        return response()->json($obat);
    }

    /**
     * Get supplier data for AJAX
     */
    public function getSupplier(Request $request)
    {
        $search = $request->get('q');
        
        $suppliers = Supplier::when($search, function($query) use ($search) {
                        return $query->where('nama_supplier', 'like', "%{$search}%");
                    })
                    ->orderBy('nama_supplier')
                    ->limit(10)
                    ->get(['id', 'nama_supplier', 'no_telp', 'alamat']);

        return response()->json($suppliers);
    }

    /**
     * Get supplier detail
     */
    public function getSupplierDetail(Request $request)
    {
        $supplier = Supplier::find($request->id);
        
        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }

    /**
     * Print/Download PDF
     */
    public function cetak($id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'details.obat'])->findOrFail($id);

        $pdf = Pdf::loadView('pembelian.cetak', compact('pembelian'));
        
        return $pdf->download('Pembelian-' . $pembelian->no_nota . '.pdf');
    }

    /**
     * Laporan Pembelian
     */
    public function laporan(Request $request)
    {
        $query = Pembelian::with(['supplier', 'details.obat']);

        // Filter
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pembelian', [
                $request->tanggal_mulai,
                $request->tanggal_akhir
            ]);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembelian = $query->latest('tanggal_pembelian')->get();

        // Statistik
        $totalPembelian = $pembelian->sum('total_harga');
        $totalDiskon = $pembelian->sum('diskon');
        $grandTotal = $totalPembelian - $totalDiskon;
        $totalTransaksi = $pembelian->count();

        $suppliers = Supplier::orderBy('nama_supplier')->get();

        // Export PDF jika diminta
        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('pembelian.laporan-pdf', compact(
                'pembelian',
                'totalPembelian',
                'totalDiskon',
                'grandTotal',
                'totalTransaksi'
            ));
            
            return $pdf->download('Laporan-Pembelian-' . date('Y-m-d') . '.pdf');
        }

        return view('pembelian.laporan', compact(
            'pembelian',
            'suppliers',
            'totalPembelian',
            'totalDiskon',
            'grandTotal',
            'totalTransaksi'
        ));
    }
}