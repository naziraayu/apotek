<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telp',
        'email',
        'kota',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Pembelian (One to Many)
     * Satu supplier bisa punya banyak pembelian
     */
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'supplier_id');
    }

    // ==================== ACCESSOR ====================

    /**
     * Get total pembelian dari supplier
     */
    public function getTotalPembelianAttribute()
    {
        return $this->pembelian()->count();
    }

    /**
     * Get total nilai pembelian
     */
    public function getTotalNilaiPembelianAttribute()
    {
        return $this->pembelian()->sum('total_harga');
    }

    /**
     * Get total nilai pembelian format
     */
    public function getTotalNilaiPembelianFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_nilai_pembelian, 0, ',', '.');
    }

    /**
     * Get pembelian terakhir
     */
    public function getPembelianTerakhirAttribute()
    {
        return $this->pembelian()->latest()->first();
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk supplier yang pernah melakukan transaksi
     */
    public function scopeHasTransaksi($query)
    {
        return $query->has('pembelian');
    }

    /**
     * Scope untuk supplier aktif (pernah transaksi 6 bulan terakhir)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('pembelian', function($q) {
            $q->where('tanggal_pembelian', '>=', now()->subMonths(6));
        });
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama_supplier', 'like', "%{$term}%")
              ->orWhere('kota', 'like', "%{$term}%")
              ->orWhere('no_telp', 'like', "%{$term}%");
        });
    }
}