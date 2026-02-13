<?php

namespace App\Http\Controllers;

use App\Models\KategoriObat;
use App\Models\Obat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::with('kategori');

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan status stok
        if ($request->filled('status_stok')) {
            if ($request->status_stok == 'minimum') {
                $query->stokMinimum();
            } elseif ($request->status_stok == 'habis') {
                $query->where('stok', 0);
            }
        }

        // Filter kadaluarsa
        if ($request->filled('kadaluarsa')) {
            $query->kadaluarsa();
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('no_batch', 'like', "%{$search}%");
            });
        }

        $obats = $query->latest()->paginate(10);
        $kategoris = KategoriObat::all();

        return view('obat.index', compact('obats', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriObat::all();
        return view('obat.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_obat,id',
            'nama_obat' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'nullable|date|after:today',
            'no_batch' => 'nullable|string|max:50|unique:obat,no_batch',
        ], [
            'kategori_id.required' => 'Kategori obat wajib dipilih',
            'kategori_id.exists' => 'Kategori obat tidak valid',
            'nama_obat.required' => 'Nama obat wajib diisi',
            'satuan.required' => 'Satuan wajib diisi',
            'harga_jual.required' => 'Harga jual wajib diisi',
            'harga_beli.required' => 'Harga beli wajib diisi',
            'stok.required' => 'Stok wajib diisi',
            'stok_minimum.required' => 'Stok minimum wajib diisi',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah hari ini',
            'no_batch.unique' => 'Nomor batch sudah digunakan',
        ]);

        // Validasi custom: Harga jual harus > harga beli
        if ($validated['harga_jual'] <= $validated['harga_beli']) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['harga_jual' => 'Harga jual harus lebih besar dari harga beli']);
        }

        try {
            Obat::create($validated);
            
            return redirect()
                ->route('obat.index')
                ->with('success', 'Data obat berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data obat: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $obat = Obat::with('kategori')->findOrFail($id);
        return view('obat.show', compact('obat'));
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        $kategoris = KategoriObat::all();
        return view('obat.edit', compact('obat', 'kategoris'));
    }

    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id);

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_obat,id',
            'nama_obat' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'nullable|date',
            'no_batch' => 'nullable|string|max:50|unique:obat,no_batch,' . $id,
        ]);

        // Validasi custom: Harga jual harus > harga beli
        if ($validated['harga_jual'] <= $validated['harga_beli']) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['harga_jual' => 'Harga jual harus lebih besar dari harga beli']);
        }

        try {
            $obat->update($validated);
            
            return redirect()
                ->route('obat.index')
                ->with('success', 'Data obat berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data obat: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $obat = Obat::findOrFail($id);
            
            // Cek apakah sedang digunakan di transaksi
            if ($obat->detailPenjualan()->exists() || $obat->detailPembelian()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', "Obat '{$obat->nama_obat}' tidak bisa dihapus karena sudah ada transaksi");
            }
            
            $obat->delete();
            
            return redirect()
                ->route('obat.index')
                ->with('success', 'Data obat berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data obat: ' . $e->getMessage());
        }
    }

    public function destroyAll(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:obat,id'
        ]);

        try {
            $obats = Obat::whereIn('id', $request->ids)->get();
            
            // Cek apakah ada yang sedang digunakan di transaksi
            foreach ($obats as $obat) {
                if ($obat->detailPenjualan()->exists() || $obat->detailPembelian()->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Obat '{$obat->nama_obat}' tidak bisa dihapus karena sudah ada transaksi"
                    ], 422);
                }
            }
            
            Obat::whereIn('id', $request->ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' data obat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getObat(Request $request)
    {
        $search = $request->search ?? '';
        
        $obats = Obat::with('kategori')
            ->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('no_batch', 'like', "%{$search}%");
            })
            ->tersedia() // Only available drugs
            ->limit(10)
            ->get();

        return response()->json($obats);
    }

    public function stok(Request $request)
    {
        $query = Obat::with('kategori');

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status == 'habis') {
                $query->where('stok', 0);
            } elseif ($request->status == 'kritis') {
                // Stok kritis: > 0 tapi <= 50% dari stok minimum
                $query->whereRaw('stok > 0 AND stok <= (stok_minimum * 0.5)');
            } elseif ($request->status == 'minimum') {
                // Stok minimum: > 50% tapi <= stok minimum
                $query->stokMinimum();
            }
        }

        // Urutkan berdasarkan prioritas:
        // 1. Stok habis (0) - prioritas tertinggi
        // 2. Stok kritis (<50% minimum)
        // 3. Stok minimum (<=minimum)
        // 4. Stok normal (>minimum)
        $obats = $query->orderByRaw('
            CASE 
                WHEN stok = 0 THEN 1
                WHEN stok <= (stok_minimum * 0.5) THEN 2
                WHEN stok <= stok_minimum THEN 3
                ELSE 4
            END
        ')
        ->orderBy('stok', 'asc')
        ->paginate(20)
        ->appends($request->all()); // Preserve query parameters
        
        // Hitung statistik untuk dashboard cards
        $allObats = Obat::all();
        $stats = [
            'total_minimum' => $allObats->filter(function($obat) {
                return $obat->isStokMinimum() || $obat->stok == 0;
            })->count(),
            'stok_habis' => $allObats->where('stok', 0)->count(),
            'stok_kritis' => $allObats->filter(function($obat) {
                return $obat->stok > 0 && $obat->stok <= ($obat->stok_minimum * 0.5);
            })->count(),
            'perlu_restock' => $allObats->filter(function($obat) {
                return $obat->isStokMinimum() && $obat->stok > 0;
            })->count(),
        ];
            
        return view('obat.detail.stok', compact('obats', 'stats'));
    }

    public function kadaluarsa(Request $request)
    {
        $query = Obat::with('kategori')
            ->whereNotNull('tanggal_kadaluarsa');

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan status kadaluarsa
        if ($request->filled('status')) {
            $now = now();
            
            if ($request->status == 'expired') {
                // Sudah kadaluarsa
                $query->whereDate('tanggal_kadaluarsa', '<', $now);
            } elseif ($request->status == '30_days') {
                // Akan kadaluarsa dalam 30 hari (belum kadaluarsa)
                $query->whereDate('tanggal_kadaluarsa', '>=', $now)
                    ->whereDate('tanggal_kadaluarsa', '<=', $now->copy()->addDays(30));
            } elseif ($request->status == '60_days') {
                // Akan kadaluarsa dalam 60 hari (belum kadaluarsa)
                $query->whereDate('tanggal_kadaluarsa', '>=', $now)
                    ->whereDate('tanggal_kadaluarsa', '<=', $now->copy()->addDays(60));
            } elseif ($request->status == '90_days') {
                // Akan kadaluarsa dalam 90 hari (belum kadaluarsa)
                $query->whereDate('tanggal_kadaluarsa', '>=', $now)
                    ->whereDate('tanggal_kadaluarsa', '<=', $now->copy()->addDays(90));
            }
        } else {
            // Default: tampilkan yang sudah kadaluarsa DAN akan kadaluarsa dalam 90 hari
            $query->where(function($q) {
                $q->whereDate('tanggal_kadaluarsa', '<', now())
                ->orWhereDate('tanggal_kadaluarsa', '<=', now()->addDays(90));
            });
        }

        // Urutan berdasarkan request atau default ascending (terdekat dulu)
        $sortOrder = $request->get('sort', 'asc');
        $query->orderBy('tanggal_kadaluarsa', $sortOrder);

        $obats = $query->paginate(20)->appends($request->all());

        // Hitung statistik untuk dashboard cards
        $allObatsWithExpiry = Obat::whereNotNull('tanggal_kadaluarsa')->get();
        $now = now();
        
        $stats = [
            'sudah_kadaluarsa' => $allObatsWithExpiry->filter(function($obat) use ($now) {
                return $obat->tanggal_kadaluarsa < $now;
            })->count(),
            
            'kadaluarsa_30_hari' => $allObatsWithExpiry->filter(function($obat) use ($now) {
                $daysUntil = $now->diffInDays($obat->tanggal_kadaluarsa, false);
                return $daysUntil >= 0 && $daysUntil <= 30;
            })->count(),
            
            'kadaluarsa_60_hari' => $allObatsWithExpiry->filter(function($obat) use ($now) {
                $daysUntil = $now->diffInDays($obat->tanggal_kadaluarsa, false);
                return $daysUntil >= 0 && $daysUntil <= 60;
            })->count(),
            
            'kadaluarsa_90_hari' => $allObatsWithExpiry->filter(function($obat) use ($now) {
                $daysUntil = $now->diffInDays($obat->tanggal_kadaluarsa, false);
                return $daysUntil >= 0 && $daysUntil <= 90;
            })->count(),
        ];

        return view('obat.detail.kadaluarsa', compact('obats', 'stats'));
    }

    public function stokMinim()
    {
        $obats = Obat::with('kategori')
            ->stokMinimum()
            ->get();
            
        return response()->json($obats);
    }

    public function checkKode(Request $request)
    {
        $exists = Obat::where('no_batch', $request->kode)
            ->where('id', '!=', $request->id ?? 0)
            ->exists();
            
        return response()->json(['exists' => $exists]);
    }
    public function search(Request $request)
    {
        $term = $request->term ?? '';
        
        $obats = Obat::where(function($q) use ($term) {
                $q->where('nama_obat', 'like', "%{$term}%")
                  ->orWhere('no_batch', 'like', "%{$term}%");
            })
            ->tersedia() // Only available
            ->limit(10)
            ->get(['id', 'nama_obat', 'satuan', 'harga_jual', 'stok']);
            
        return response()->json($obats);
    }

    public function data(Request $request)
    {
        $obats = Obat::with('kategori')->select('obat.*');

        return DataTables::eloquent($obats)
            ->addIndexColumn()
            ->addColumn('kategori_nama', function($obat) {
                return $obat->kategori->nama_kategori ?? '-';
            })
            ->addColumn('harga_jual_format', function($obat) {
                return 'Rp ' . number_format($obat->harga_jual, 0, ',', '.');
            })
            ->addColumn('harga_beli_format', function($obat) {
                return 'Rp ' . number_format($obat->harga_beli, 0, ',', '.');
            })
            ->addColumn('status_stok', function($obat) {
                if ($obat->stok == 0) {
                    return '<span class="badge bg-danger">Habis</span>';
                } elseif ($obat->isStokMinimum()) {
                    return '<span class="badge bg-warning text-dark">Stok Minimum</span>';
                } else {
                    return '<span class="badge bg-success">Tersedia</span>';
                }
            })
            ->addColumn('action', function($obat) {
                return view('obat.action', compact('obat'));
            })
            ->rawColumns(['status_stok', 'action'])
            ->make(true);
    }

    public function export(Request $request)
    {
        // Apply same filters as index
        $query = Obat::with('kategori');

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status_stok')) {
            if ($request->status_stok == 'minimum') {
                $query->stokMinimum();
            } elseif ($request->status_stok == 'habis') {
                $query->where('stok', 0);
            }
        }

        if ($request->filled('kadaluarsa')) {
            $query->kadaluarsa();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('no_batch', 'like', "%{$search}%");
            });
        }

        $obats = $query->get();

        $filename = 'data-obat-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($obats) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'No', 
                'Nama Obat', 
                'Kategori', 
                'Satuan', 
                'Harga Jual', 
                'Harga Beli', 
                'Stok', 
                'Stok Min', 
                'Batch', 
                'Kadaluarsa',
                'Status'
            ]);
            
            // Data
            foreach ($obats as $index => $obat) {
                $status = '';
                if ($obat->isExpired()) $status = 'Kadaluarsa';
                elseif ($obat->stok == 0) $status = 'Habis';
                elseif ($obat->isStokMinimum()) $status = 'Stok Minimum';
                elseif ($obat->isNearExpired()) $status = 'Akan Kadaluarsa';
                else $status = 'Normal';

                fputcsv($file, [
                    $index + 1,
                    $obat->nama_obat,
                    $obat->kategori_nama,
                    $obat->satuan,
                    $obat->harga_jual,
                    $obat->harga_beli,
                    $obat->stok,
                    $obat->stok_minimum,
                    $obat->no_batch ?? '-',
                    $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('Y-m-d') : '-',
                    $status,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}