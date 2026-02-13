<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';

    protected $fillable = [
        'pembelian_id',
        'obat_id',
        'jumlah',
        'harga_beli',
        'subtotal',
        'tanggal_kadaluarsa',
        'no_batch',
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'harga_beli' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Pembelian (Many to One)
     * Banyak detail milik satu pembelian
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
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
     * Get harga beli format
     */
    public function getHargaBeliFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
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

    // ==================== BOOT EVENTS ====================

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto calculate subtotal sebelum save
        static::saving(function ($detail) {
            $detail->subtotal = $detail->jumlah * $detail->harga_beli;
        });

        // Update total pembelian setelah save/delete
        static::saved(function ($detail) {
            $detail->pembelian->hitungTotal();
        });

        static::deleted(function ($detail) {
            $detail->pembelian->hitungTotal();
        });
    }
}