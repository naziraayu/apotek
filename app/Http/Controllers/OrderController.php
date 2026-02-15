<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Halaman checkout dengan WAJIB upload bukti pembayaran
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Keranjang kosong');
        }

        // Validasi stok semua item
        foreach ($cart as $obatId => $item) {
            $obat = Obat::find($obatId);
            if (!$obat || $obat->stok < $item['jumlah']) {
                return redirect()->route('shop.cart')
                    ->with('error', "Stok {$item['nama']} tidak mencukupi");
            }
        }

        $total = $this->calculateTotal($cart);

        return view('shop.checkout', compact('cart', 'total'));
    }

    /**
     * Proses order dengan WAJIB upload bukti pembayaran
     */
    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer,e-wallet,kartu_kredit',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // WAJIB upload!
            'catatan' => 'nullable|string|max:500',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB',
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
                if (!$obat || $obat->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$item['nama']} tidak mencukupi");
                }
            }

            // Upload bukti pembayaran
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            // Hitung total
            $totalHarga = $this->calculateTotal($cart);

            // Buat Penjualan dengan status PENDING (belum diverifikasi apoteker)
            $penjualan = Penjualan::create([
                'no_nota' => Penjualan::generateNoNota(),
                'pelanggan_id' => Auth::user()->pelanggan->id ?? null,
                'user_id' => Auth::id(),
                'tanggal_penjualan' => now(),
                'total_harga' => $totalHarga,
                'diskon' => 0,
                'grand_total' => $totalHarga,
                'status_pembayaran' => 'pending', // PENDING sampai apoteker verifikasi!
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath, // Simpan path bukti
                'catatan' => $request->catatan,
            ]);

            // Buat Detail Penjualan (STOK BELUM DIKURANGI!)
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

            return redirect()->route('shop.orders.show', $penjualan->id)
                ->with('success', 'Pesanan berhasil dibuat! Menunggu verifikasi dari apoteker.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file bukti jika gagal
            if (isset($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Lihat semua order pelanggan
     */
    public function index(Request $request)
    {
        $query = Penjualan::where('user_id', Auth::id())
            ->with('details.obat');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $orders = $query->latest('tanggal_penjualan')->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    /**
     * Detail order
     */
    public function show($id)
    {
        $order = Penjualan::where('user_id', Auth::id())
            ->with('details.obat')
            ->findOrFail($id);

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Cancel order (hanya bisa jika status pending)
     */
    public function cancel($id)
    {
        $order = Penjualan::where('user_id', Auth::id())
            ->findOrFail($id);

        // Hanya bisa cancel jika status pending
        if ($order->status_pembayaran !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        DB::beginTransaction();
        try {
            // Hapus bukti pembayaran dari storage
            if ($order->bukti_pembayaran) {
                Storage::disk('public')->delete($order->bukti_pembayaran);
            }

            // Update status
            $order->update([
                'status_pembayaran' => 'batal',
            ]);

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Re-upload bukti pembayaran (hanya bisa jika status pending)
     */
    public function reupload(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $order = Penjualan::where('user_id', Auth::id())
            ->findOrFail($id);

        // Hanya bisa re-upload jika status pending
        if ($order->status_pembayaran !== 'pending') {
            return back()->with('error', 'Bukti pembayaran tidak dapat diubah untuk pesanan ini');
        }

        DB::beginTransaction();
        try {
            // Hapus bukti lama
            if ($order->bukti_pembayaran) {
                Storage::disk('public')->delete($order->bukti_pembayaran);
            }

            // Upload bukti baru
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');

            // Update
            $order->update([
                'bukti_pembayaran' => $buktiPath,
            ]);

            DB::commit();

            return back()->with('success', 'Bukti pembayaran berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Hitung total
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }
        return $total;
    }
}