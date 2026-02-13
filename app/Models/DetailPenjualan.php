<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'obat_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Penjualan (Many to One)
     * Banyak detail milik satu penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    /**
     * Relasi ke Obat (Many to One)
     * Banyak detail untuk satu obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    // ==================== ACCESSOR ====================

    /**
     * Get harga satuan format
     */
    public function getHargaSatuanFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Get subtotal format
     */
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get nama obat
     */
    public function getObatNamaAttribute()
    {
        return $this->obat?->nama_obat ?? '-';
    }

    /**
     * Get profit per item
     */
    public function getProfitAttribute()
    {
        $hargaBeli = $this->obat->harga_beli ?? 0;
        return ($this->harga_satuan - $hargaBeli) * $this->jumlah;
    }

    /**
     * Get profit format
     */
    public function getProfitFormatAttribute()
    {
        return 'Rp ' . number_format($this->profit, 0, ',', '.');
    }

    // ==================== BOOT EVENTS ====================

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto calculate subtotal sebelum save
        static::saving(function ($detail) {
            $detail->subtotal = $detail->jumlah * $detail->harga_satuan;
        });

        // Update stok obat dan total penjualan setelah save
        static::saved(function ($detail) {
            $detail->penjualan->hitungTotal();
        });

        // Kurangi stok obat setelah create
        static::created(function ($detail) {
            $detail->obat->kurangiStok($detail->jumlah);
        });

        // Update total penjualan setelah delete
        static::deleted(function ($detail) {
            // Kembalikan stok obat
            $detail->obat->tambahStok($detail->jumlah);
            
            $detail->penjualan->hitungTotal();
        });
    }
}