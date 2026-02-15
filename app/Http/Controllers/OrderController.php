<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Halaman checkout
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Keranjang kosong');
        }

        $total = $this->calculateTotal($cart);

        return view('orders.checkout', compact('cart', 'total'));
    }

    /**
     * Proses order
     */
    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer,e-wallet,kartu_kredit',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();
        try {
            // Validasi stok semua obat
            foreach ($cart as $obatId => $item) {
                $obat = Obat::find($obatId);
                if ($obat->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$obat->nama_obat} tidak mencukupi");
                }
            }

            // Hitung total
            $totalHarga = $this->calculateTotal($cart);

            // Buat Penjualan
            $penjualan = Penjualan::create([
                'no_nota' => Penjualan::generateNoNota(),
                'pelanggan_id' => Auth::user()->pelanggan->id ?? null,
                'user_id' => Auth::id(),
                'tanggal_penjualan' => now(),
                'total_harga' => $totalHarga,
                'diskon' => 0,
                'grand_total' => $totalHarga,
                'status_pembayaran' => 'pending', // Perlu approval
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // Buat Detail Penjualan
            foreach ($cart as $obatId => $item) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'obat_id' => $obatId,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['jumlah'],
                ]);
            }

            DB::commit();

            // Clear cart
            Session::forget('cart');

            return redirect()->route('orders.show', $penjualan->id)
                ->with('success', 'Pesanan berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Lihat order sendiri
     */
    public function index()
    {
        $orders = Penjualan::where('user_id', Auth::id())
            ->with('details.obat')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail order
     */
    public function show($id)
    {
        $order = Penjualan::where('user_id', Auth::id())
            ->with('details.obat')
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        return $total;
    }
}