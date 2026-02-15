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

        return view('cart.index', compact('cart', 'total'));
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
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = Session::get('cart', []);

        // Jika obat sudah ada di cart, update jumlah
        if (isset($cart[$obat->id])) {
            $cart[$obat->id]['jumlah'] += $request->jumlah;
        } else {
            $cart[$obat->id] = [
                'nama' => $obat->nama_obat,
                'harga' => $obat->harga_jual,
                'jumlah' => $request->jumlah,
                'satuan' => $obat->satuan,
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Obat berhasil ditambahkan ke keranjang');
    }

    /**
     * Update jumlah di keranjang
     */
    public function update(Request $request, $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] = $request->jumlah;
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Keranjang diupdate');
    }

    /**
     * Hapus dari keranjang
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);

        return back()->with('success', 'Item dihapus dari keranjang');
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