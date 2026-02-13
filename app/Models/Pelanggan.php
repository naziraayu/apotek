<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'alamat',
        'no_telp',
        'email',
        'tanggal_daftar',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Penjualan (One to Many)
     * Satu pelanggan bisa punya banyak penjualan
     */
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }

    // ==================== ACCESSOR ====================

    /**
     * Get total penjualan/transaksi pelanggan
     */
    public function getTotalTransaksiAttribute()
    {
        return $this->penjualan()->count();
    }

    /**
     * Get total belanja pelanggan
     */
    public function getTotalBelanjaAttribute()
    {
        return $this->penjualan()->sum('grand_total');
    }

    /**
     * Get total belanja format
     */
    public function getTotalBelanjaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_belanja, 0, ',', '.');
    }

    /**
     * Get transaksi terakhir
     */
    public function getTransaksiTerakhirAttribute()
    {
        return $this->penjualan()->latest()->first();
    }

    /**
     * Get lama menjadi pelanggan (dalam hari)
     */
    public function getLamaPelangganAttribute()
    {
        return $this->tanggal_daftar->diffInDays(now());
    }

    /**
     * Check apakah pelanggan loyal (pernah transaksi > 5x)
     */
    public function isLoyal()
    {
        return $this->total_transaksi >= 5;
    }

    /**
     * Check apakah pelanggan VIP (total belanja > 5 juta)
     */
    public function isVip()
    {
        return $this->total_belanja >= 5000000;
    }

    /**
     * Get status pelanggan
     */
    public function getStatusPelangganAttribute()
    {
        if ($this->isVip()) {
            return '<span class="badge bg-warning text-dark">VIP</span>';
        } elseif ($this->isLoyal()) {
            return '<span class="badge bg-info">Loyal</span>';
        } else {
            return '<span class="badge bg-secondary">Regular</span>';
        }
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk pelanggan yang pernah transaksi
     */
    public function scopeHasTransaksi($query)
    {
        return $query->has('penjualan');
    }

    /**
     * Scope untuk pelanggan loyal
     */
    public function scopeLoyal($query)
    {
        return $query->has('penjualan', '>=', 5);
    }

    /**
     * Scope untuk pelanggan VIP
     */
    public function scopeVip($query)
    {
        return $query->whereHas('penjualan', function($q) {
            $q->havingRaw('SUM(grand_total) >= 5000000');
        });
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama_pelanggan', 'like', "%{$term}%")
              ->orWhere('no_telp', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}