<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Pelanggan;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== STATISTIK KARTU ATAS ==========
        
        // Total Penjualan Hari Ini
        $penjualanHariIni = Penjualan::whereDate('tanggal_penjualan', today())
            ->where('status_pembayaran', 'lunas')
            ->sum('grand_total');

        // Total Transaksi Hari Ini
        $transaksiHariIni = Penjualan::whereDate('tanggal_penjualan', today())->count();

        // Obat Stok Minimum
        $obatStokMinimum = Obat::whereRaw('stok <= stok_minimum')->count();

        // Obat Akan Kadaluarsa (90 hari)
        $obatAkanKadaluarsa = Obat::whereNotNull('tanggal_kadaluarsa')
            ->whereBetween('tanggal_kadaluarsa', [now(), now()->addDays(90)])
            ->count();

        // ========== CHART PENJUALAN 7 HARI TERAKHIR ==========
        $penjualan7Hari = Penjualan::where('tanggal_penjualan', '>=', now()->subDays(6))
            ->where('status_pembayaran', 'lunas')
            ->select(
                DB::raw('DATE(tanggal_penjualan) as tanggal'),
                DB::raw('SUM(grand_total) as total'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // ========== CHART PENJUALAN VS PEMBELIAN BULAN INI ==========
        $bulanIni = now()->format('Y-m');
        
        $penjualanPerHari = Penjualan::whereRaw("DATE_FORMAT(tanggal_penjualan, '%Y-%m') = ?", [$bulanIni])
            ->where('status_pembayaran', 'lunas')
            ->select(
                DB::raw('DAY(tanggal_penjualan) as hari'),
                DB::raw('SUM(grand_total) as total')
            )
            ->groupBy('hari')
            ->pluck('total', 'hari');

        $pembelianPerHari = Pembelian::whereRaw("DATE_FORMAT(tanggal_pembelian, '%Y-%m') = ?", [$bulanIni])
            ->where('status', 'selesai')
            ->select(
                DB::raw('DAY(tanggal_pembelian) as hari'),
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy('hari')
            ->pluck('total', 'hari');

        // ========== TOP 5 OBAT TERLARIS ==========
        $obatTerlaris = Obat::withCount([
            'detailPenjualan as total_terjual' => function($query) {
                $query->select(DB::raw('SUM(jumlah)'));
            }
        ])
        ->withSum([
            'detailPenjualan as total_pendapatan' => function($query) {
                $query->select(DB::raw('SUM(subtotal)'));
            }
        ], 'subtotal')
        ->having('total_terjual', '>', 0)
        ->orderBy('total_terjual', 'desc')
        ->take(5)
        ->get();

        // ========== OBAT STOK KRITIS (TOP 10) ==========
        $obatStokKritis = Obat::with('kategori')
            ->whereRaw('stok <= stok_minimum')
            ->orderBy('stok', 'asc')
            ->take(10)
            ->get();

        // ========== OBAT AKAN KADALUARSA (TOP 10) ==========
        $obatKadaluarsa = Obat::with('kategori')
            ->whereNotNull('tanggal_kadaluarsa')
            ->where('tanggal_kadaluarsa', '>=', now())
            ->where('tanggal_kadaluarsa', '<=', now()->addDays(90))
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->take(10)
            ->get();

        // ========== TRANSAKSI TERBARU ==========
        $transaksiTerbaru = Penjualan::with(['pelanggan', 'user'])
            ->latest('tanggal_penjualan')
            ->take(5)
            ->get();

        // ========== STATISTIK PELANGGAN ==========
        $totalPelanggan = Pelanggan::count();
        $pelangganBaru = Pelanggan::whereMonth('tanggal_daftar', now()->month)->count();
        
        // Pelanggan VIP (total belanja >= 5 juta)
        $pelangganVIP = DB::table('pelanggan')
            ->join('penjualan', 'pelanggan.id', '=', 'penjualan.pelanggan_id')
            ->where('penjualan.status_pembayaran', 'lunas')
            ->select('pelanggan.id')
            ->groupBy('pelanggan.id')
            ->havingRaw('SUM(penjualan.grand_total) >= 5000000')
            ->count();

        // ========== STATISTIK SUPPLIER ==========
        $totalSupplier = Supplier::count();
        $supplierAktif = Supplier::whereHas('pembelian', function($q) {
            $q->where('tanggal_pembelian', '>=', now()->subMonths(3));
        })->count();

        // ========== TOTAL NILAI STOK ==========
        $totalNilaiStok = Obat::sum(DB::raw('stok * harga_beli'));

        // ========== PROFIT BULAN INI ==========
        $profitBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->where('status_pembayaran', 'lunas')
            ->with('details.obat')
            ->get()
            ->sum(function($penjualan) {
                return $penjualan->details->sum(function($detail) {
                    $hargaBeli = $detail->obat->harga_beli ?? 0;
                    return ($detail->harga_satuan - $hargaBeli) * $detail->jumlah;
                });
            });

        // ========== PENDING APPROVAL (untuk apoteker) ==========
        $pendingApproval = Penjualan::where('status_pembayaran', 'pending')->count();

        // ========== DAFTAR OBAT YANG TERJUAL HARI INI ==========
        $obatTerjualHariIni = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->join('obat', 'detail_penjualan.obat_id', '=', 'obat.id')
            ->join('kategori_obat', 'obat.kategori_id', '=', 'kategori_obat.id')
            ->whereDate('penjualan.tanggal_penjualan', today())
            ->where('penjualan.status_pembayaran', 'lunas')
            ->select(
                'obat.id',
                'obat.nama_obat',
                'obat.satuan',
                'kategori_obat.nama_kategori',
                DB::raw('SUM(detail_penjualan.jumlah) as total_terjual'),
                DB::raw('SUM(detail_penjualan.subtotal) as total_pendapatan'),
                DB::raw('COUNT(DISTINCT penjualan.id) as jumlah_transaksi')
            )
            ->groupBy('obat.id', 'obat.nama_obat', 'obat.satuan', 'kategori_obat.nama_kategori')
            ->orderBy('total_terjual', 'desc')
            ->get();

        return view('layouts.dashboard', compact(
            'penjualanHariIni',
            'transaksiHariIni',
            'obatStokMinimum',
            'obatAkanKadaluarsa',
            'penjualan7Hari',
            'penjualanPerHari',
            'pembelianPerHari',
            'obatTerlaris',
            'obatStokKritis',
            'obatKadaluarsa',
            'transaksiTerbaru',
            'totalPelanggan',
            'pelangganBaru',
            'pelangganVIP',
            'totalSupplier',
            'supplierAktif',
            'totalNilaiStok',
            'profitBulanIni',
            'pendingApproval',
            'obatTerjualHariIni'
        ));
    }
}