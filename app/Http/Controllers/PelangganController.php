<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Filter berdasarkan status pelanggan
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'vip':
                    $query->vip();
                    break;
                case 'loyal':
                    $query->loyal();
                    break;
                case 'regular':
                    $query->whereDoesntHave('penjualan', function($q) {
                        $q->havingRaw('COUNT(*) >= 5');
                    });
                    break;
            }
        }

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_daftar', $request->bulan)
                  ->whereYear('tanggal_daftar', $request->tahun);
        }

        // Filter berdasarkan aktivitas transaksi
        if ($request->filled('aktivitas')) {
            switch ($request->aktivitas) {
                case 'aktif':
                    // Transaksi dalam 30 hari terakhir
                    $query->whereHas('penjualan', function($q) {
                        $q->where('tanggal_penjualan', '>=', now()->subDays(30));
                    });
                    break;
                case 'tidak_aktif':
                    // Tidak ada transaksi dalam 30 hari terakhir
                    $query->whereDoesntHave('penjualan', function($q) {
                        $q->where('tanggal_penjualan', '>=', now()->subDays(30));
                    })->has('penjualan');
                    break;
                case 'belum_transaksi':
                    // Belum pernah transaksi
                    $query->doesntHave('penjualan');
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Urutkan dan paginate
        $pelanggan = $query->withCount('penjualan')
                           ->withSum('penjualan', 'grand_total')
                           ->latest('tanggal_daftar')
                           ->paginate(10)
                           ->appends($request->all());

        return view('pelanggan.index', compact('pelanggan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'required|string|max:20|unique:pelanggan,no_telp',
            'email' => 'nullable|email|max:255|unique:pelanggan,email',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        // Set tanggal daftar otomatis
        $validated['tanggal_daftar'] = now();

        try {
            Pelanggan::create($validated);
            
            return redirect()->route('pelanggan.index')
                           ->with('success', 'Data pelanggan berhasil ditambahkan');
        } catch (\Exception $e) {
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
        $pelanggan = Pelanggan::with(['penjualan' => function($query) {
            $query->latest('tanggal_penjualan')->take(10);
        }])->findOrFail($id);

        // Statistik pelanggan
        $statistik = [
            'total_transaksi' => $pelanggan->total_transaksi,
            'total_belanja' => $pelanggan->total_belanja,
            'rata_rata_belanja' => $pelanggan->total_transaksi > 0 
                ? $pelanggan->total_belanja / $pelanggan->total_transaksi 
                : 0,
            'transaksi_terakhir' => $pelanggan->transaksi_terakhir,
            'lama_pelanggan' => $pelanggan->lama_pelanggan,
        ];

        // Riwayat transaksi per bulan (6 bulan terakhir)
        $transaksiPerBulan = DB::table('penjualan')
            ->select(
                DB::raw('MONTH(tanggal_penjualan) as bulan'),
                DB::raw('YEAR(tanggal_penjualan) as tahun'),
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(grand_total) as total')
            )
            ->where('pelanggan_id', $id)
            ->where('tanggal_penjualan', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('pelanggan.show', compact('pelanggan', 'statistik', 'transaksiPerBulan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'required|string|max:20|unique:pelanggan,no_telp,' . $id,
            'email' => 'nullable|email|max:255|unique:pelanggan,email,' . $id,
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        try {
            $pelanggan->update($validated);
            
            return redirect()->route('pelanggan.index')
                           ->with('success', 'Data pelanggan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            
            // Cek apakah pelanggan punya transaksi
            if ($pelanggan->penjualan()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'Tidak dapat menghapus pelanggan yang sudah memiliki riwayat transaksi');
            }
            
            $pelanggan->delete();
            
            return redirect()->route('pelanggan.index')
                           ->with('success', 'Data pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyAll(Request $request)
    {
        try {
            $ids = $request->ids;
            
            // Cek apakah ada pelanggan yang punya transaksi
            $pelangganDenganTransaksi = Pelanggan::whereIn('id', $ids)
                ->has('penjualan')
                ->count();
            
            if ($pelangganDenganTransaksi > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pelanggan yang sudah memiliki riwayat transaksi'
                ]);
            }
            
            Pelanggan::whereIn('id', $ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data pelanggan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get data for DataTables (AJAX)
     */
    public function data(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $pelanggan = $query->withCount('penjualan')
                           ->withSum('penjualan', 'grand_total')
                           ->get();

        return response()->json($pelanggan);
    }

    /**
     * Search pelanggan (untuk autocomplete)
     */
    public function search(Request $request)
    {
        $term = $request->get('term', '');
        
        $pelanggan = Pelanggan::search($term)
            ->select('id', 'nama_pelanggan', 'no_telp', 'alamat')
            ->limit(10)
            ->get();

        return response()->json($pelanggan);
    }
}