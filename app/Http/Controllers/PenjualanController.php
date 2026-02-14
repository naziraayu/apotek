<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Obat;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'user', 'details.obat'])
            ->latest('tanggal_penjualan');

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter berdasarkan pelanggan
        if ($request->filled('pelanggan_id')) {
            $query->where('pelanggan_id', $request->pelanggan_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_penjualan', [
                $request->tanggal_mulai,
                $request->tanggal_akhir
            ]);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_penjualan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_penjualan', $request->tahun);
        }

        $penjualan = $query->paginate(10);

        // Statistik
        $totalPenjualan = Penjualan::sum('grand_total');
        $totalDiskon = Penjualan::sum('diskon');
        $penjualanBulanIni = Penjualan::bulanIni()->count();
        $penjualanBelumLunas = Penjualan::belumLunas()->count();

        // Data untuk filter
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('penjualan.index', compact(
            'penjualan',
            'totalPenjualan',
            'totalDiskon',
            'penjualanBulanIni',
            'penjualanBelumLunas',
            'pelanggans'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $obats = Obat::where('stok', '>', 0)
            ->where(function($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhere('tanggal_kadaluarsa', '>', now());
            })
            ->orderBy('nama_obat')
            ->get();
        
        $noNota = Penjualan::generateNoNota();

        return view('penjualan.create', compact('pelanggans', 'obats', 'noNota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,transfer,kartu_kredit,e-wallet',
            'status_pembayaran' => 'required|in:lunas,belum_lunas',
            'diskon' => 'nullable|numeric|min:0',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'required|exists:obat,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0',
        ], [
            'obat_id.required' => 'Minimal harus ada 1 obat',
            'obat_id.*.exists' => 'Obat tidak valid',
            'jumlah.*.min' => 'Jumlah minimal 1',
        ]);

        DB::beginTransaction();
        try {
            // Validasi stok
            foreach ($request->obat_id as $key => $obatId) {
                $obat = Obat::find($obatId);
                $jumlah = $request->jumlah[$key];
                
                if ($obat->stok < $jumlah) {
                    throw new \Exception("Stok {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok}");
                }
            }

            // Hitung total
            $totalHarga = 0;
            foreach ($request->obat_id as $key => $obatId) {
                $subtotal = $request->jumlah[$key] * $request->harga_satuan[$key];
                $totalHarga += $subtotal;
            }

            $diskon = $request->diskon ?? 0;
            $grandTotal = $totalHarga - $diskon;

            // Create Penjualan
            $penjualan = Penjualan::create([
                'no_nota' => Penjualan::generateNoNota(),
                'pelanggan_id' => $request->pelanggan_id,
                'user_id' => Auth::id(),
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'grand_total' => $grandTotal,
                'status_pembayaran' => $request->status_pembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // Create Detail Penjualan
            foreach ($request->obat_id as $key => $obatId) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'obat_id' => $obatId,
                    'jumlah' => $request->jumlah[$key],
                    'harga_satuan' => $request->harga_satuan[$key],
                    'subtotal' => $request->jumlah[$key] * $request->harga_satuan[$key],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('penjualan.show', $penjualan->id)
                ->with('success', 'Penjualan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'user', 'details.obat'])
            ->findOrFail($id);

        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penjualan = Penjualan::with(['details.obat'])->findOrFail($id);
        
        // Hanya bisa edit jika belum lunas
        if ($penjualan->status_pembayaran === 'lunas') {
            return redirect()
                ->route('penjualan.show', $id)
                ->with('error', 'Tidak dapat mengubah penjualan yang sudah lunas');
        }

        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $obats = Obat::where('stok', '>', 0)
            ->orWhereIn('id', $penjualan->details->pluck('obat_id'))
            ->orderBy('nama_obat')
            ->get();

        return view('penjualan.edit', compact('penjualan', 'pelanggans', 'obats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        // Validasi status
        if ($penjualan->status_pembayaran === 'lunas') {
            return redirect()
                ->route('penjualan.show', $id)
                ->with('error', 'Tidak dapat mengubah penjualan yang sudah lunas');
        }

        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,transfer,kartu_kredit,e-wallet',
            'status_pembayaran' => 'required|in:lunas,belum_lunas',
            'diskon' => 'nullable|numeric|min:0',
            'obat_id' => 'required|array|min:1',
            'obat_id.*' => 'required|exists:obat,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Kembalikan stok dari detail lama
            foreach ($penjualan->details as $detail) {
                $detail->obat->tambahStok($detail->jumlah);
            }

            // Hapus detail lama
            $penjualan->details()->delete();

            // Validasi stok baru
            foreach ($request->obat_id as $key => $obatId) {
                $obat = Obat::find($obatId);
                $jumlah = $request->jumlah[$key];
                
                if ($obat->stok < $jumlah) {
                    throw new \Exception("Stok {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok}");
                }
            }

            // Hitung total
            $totalHarga = 0;
            foreach ($request->obat_id as $key => $obatId) {
                $subtotal = $request->jumlah[$key] * $request->harga_satuan[$key];
                $totalHarga += $subtotal;
            }

            $diskon = $request->diskon ?? 0;
            $grandTotal = $totalHarga - $diskon;

            // Update Penjualan
            $penjualan->update([
                'pelanggan_id' => $request->pelanggan_id,
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'grand_total' => $grandTotal,
                'status_pembayaran' => $request->status_pembayaran,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // Create Detail Penjualan baru
            foreach ($request->obat_id as $key => $obatId) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'obat_id' => $obatId,
                    'jumlah' => $request->jumlah[$key],
                    'harga_satuan' => $request->harga_satuan[$key],
                    'subtotal' => $request->jumlah[$key] * $request->harga_satuan[$key],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('penjualan.show', $penjualan->id)
                ->with('success', 'Penjualan berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::findOrFail($id);

            // Hanya bisa hapus jika belum lunas
            if ($penjualan->status_pembayaran === 'lunas') {
                throw new \Exception('Tidak dapat menghapus penjualan yang sudah lunas');
            }

            // Kembalikan stok
            foreach ($penjualan->details as $detail) {
                $detail->obat->tambahStok($detail->jumlah);
            }

            // Hapus detail
            $penjualan->details()->delete();

            // Hapus penjualan
            $penjualan->delete();

            DB::commit();

            return redirect()
                ->route('penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete multiple resources
     */
    public function destroyAll(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:penjualan,id'
        ]);

        DB::beginTransaction();
        try {
            $penjualans = Penjualan::whereIn('id', $request->ids)->get();

            foreach ($penjualans as $penjualan) {
                // Skip jika sudah lunas
                if ($penjualan->status_pembayaran === 'lunas') {
                    continue;
                }

                // Kembalikan stok
                foreach ($penjualan->details as $detail) {
                    $detail->obat->tambahStok($detail->jumlah);
                }

                // Hapus detail
                $penjualan->details()->delete();

                // Hapus penjualan
                $penjualan->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cetak nota penjualan
     */
    public function cetak(string $id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'user', 'details.obat'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('penjualan.cetak', compact('penjualan'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream("Nota-{$penjualan->no_nota}.pdf");
    }

    /**
     * Laporan penjualan
     */
    public function laporan(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'user', 'details.obat']);

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_penjualan', [
                $request->tanggal_mulai,
                $request->tanggal_akhir
            ]);
        }

        // Filter berdasarkan bulan & tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_penjualan', $request->bulan)
                  ->whereYear('tanggal_penjualan', $request->tahun);
        }

        $penjualans = $query->latest('tanggal_penjualan')->get();

        // Statistik
        $totalPenjualan = $penjualans->sum('grand_total');
        $totalDiskon = $penjualans->sum('diskon');
        $totalTransaksi = $penjualans->count();
        $totalProfit = $penjualans->sum('total_profit');

        // Untuk export PDF
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('penjualan.laporan-pdf', compact(
                'penjualans',
                'totalPenjualan',
                'totalDiskon',
                'totalTransaksi',
                'totalProfit'
            ));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download('Laporan-Penjualan-' . date('Y-m-d') . '.pdf');
        }

        return view('penjualan.laporan', compact(
            'penjualans',
            'totalPenjualan',
            'totalDiskon',
            'totalTransaksi',
            'totalProfit'
        ));
    }

    // ==================== AJAX METHODS ====================

    /**
     * Get list obat untuk autocomplete
     */
    public function getObat(Request $request)
    {
        $search = $request->get('q');
        
        $obats = Obat::where('stok', '>', 0)
            ->where(function($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhere('tanggal_kadaluarsa', '>', now());
            })
            ->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'nama_obat', 'kode_obat', 'harga_jual', 'stok', 'satuan']);

        return response()->json($obats);
    }

    /**
     * Get list pelanggan untuk autocomplete
     */
    public function getPelanggan(Request $request)
    {
        $search = $request->get('q');
        
        $pelanggans = Pelanggan::where('nama_pelanggan', 'like', "%{$search}%")
            ->orWhere('no_telepon', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama_pelanggan', 'no_telepon', 'alamat']);

        return response()->json($pelanggans);
    }

    /**
     * Get harga obat
     */
    public function getHargaObat(Request $request)
    {
        $obat = Obat::find($request->obat_id);
        
        if (!$obat) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        return response()->json([
            'harga_jual' => $obat->harga_jual,
            'stok' => $obat->stok,
            'satuan' => $obat->satuan,
        ]);
    }

    /**
     * Check stok obat
     */
    public function checkStok(Request $request)
    {
        $obat = Obat::find($request->obat_id);
        
        if (!$obat) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        $available = $obat->stok >= $request->jumlah;

        return response()->json([
            'available' => $available,
            'stok' => $obat->stok,
            'message' => $available 
                ? 'Stok tersedia' 
                : "Stok tidak mencukupi. Tersedia: {$obat->stok}"
        ]);
    }
}