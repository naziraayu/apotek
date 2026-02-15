<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Tampilkan keranjang
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('shop.cart', compact('cart', 'total'));
    }

    /**
     * Tambah ke keranjang
     */
    public function add(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($request->obat_id);

        // Validasi stok
        if ($obat->stok < $request->jumlah) {
            return back()->with('error', "Stok {$obat->nama_obat} tidak mencukupi. Stok tersedia: {$obat->stok}");
        }

        // Validasi kadaluarsa
        if ($obat->isExpired()) {
            return back()->with('error', "Obat {$obat->nama_obat} sudah kadaluarsa");
        }

        $cart = Session::get('cart', []);

        // Jika obat sudah ada di cart, update jumlah
        if (isset($cart[$obat->id])) {
            $newQuantity = $cart[$obat->id]['jumlah'] + $request->jumlah;
            
            // Cek stok lagi
            if ($obat->stok < $newQuantity) {
                return back()->with('error', "Total jumlah melebihi stok yang tersedia. Stok tersedia: {$obat->stok}");
            }
            
            $cart[$obat->id]['jumlah'] = $newQuantity;
        } else {
            $cart[$obat->id] = [
                'nama' => $obat->nama_obat,
                'harga' => $obat->harga_jual,
                'jumlah' => $request->jumlah,
                'satuan' => $obat->satuan,
                'image' => null, // Bisa ditambahkan jika ada gambar
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', "{$obat->nama_obat} berhasil ditambahkan ke keranjang");
    }

    /**
     * Update jumlah di keranjang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Item tidak ditemukan di keranjang');
        }

        // Cek stok
        $obat = Obat::find($id);
        if (!$obat) {
            return back()->with('error', 'Obat tidak ditemukan');
        }

        if ($obat->stok < $request->jumlah) {
            return back()->with('error', "Stok tidak mencukupi. Stok tersedia: {$obat->stok}");
        }

        $cart[$id]['jumlah'] = $request->jumlah;
        Session::put('cart', $cart);

        return back()->with('success', 'Keranjang berhasil diupdate');
    }

    /**
     * Hapus dari keranjang
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (!isset($cart[$id])) {
            return back()->with('error', 'Item tidak ditemukan di keranjang');
        }

        $namaObat = $cart[$id]['nama'];
        unset($cart[$id]);
        
        Session::put('cart', $cart);

        return back()->with('success', "{$namaObat} dihapus dari keranjang");
    }

    /**
     * Clear semua cart
     */
    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Keranjang dikosongkan');
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