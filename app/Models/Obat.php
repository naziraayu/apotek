<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';

    protected $fillable = [
        'kategori_id',
        'nama_obat',
        'deskripsi',
        'satuan',
        'harga_jual',
        'harga_beli',
        'stok',
        'stok_minimum',
        'tanggal_kadaluarsa',
        'no_batch',
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
        'harga_jual' => 'decimal:2',
        'harga_beli' => 'decimal:2',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke KategoriObat (Many to One)
     * Banyak obat memiliki satu kategori
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriObat::class, 'kategori_id');
    }

    /**
     * Relasi ke DetailPenjualan (One to Many)
     * Satu obat bisa ada di banyak detail penjualan
     */
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'obat_id');
    }

    /**
     * Relasi ke DetailPembelian (One to Many)
     * Satu obat bisa ada di banyak detail pembelian
     */
    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'obat_id');
    }

    // ==================== ACCESSOR ====================

    /**
     * Format harga jual ke Rupiah
     */
    public function getHargaJualFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    /**
     * Format harga beli ke Rupiah
     */
    public function getHargaBeliFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    /**
     * Hitung profit margin (%)
     */
    public function getProfitMarginAttribute()
    {
        if ($this->harga_beli == 0) return 0;
        return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
    }

    /**
     * Format profit margin
     */
    public function getProfitMarginFormatAttribute()
    {
        return number_format($this->profit_margin, 2) . '%';
    }

    /**
     * Get nama kategori
     */
    public function getKategoriNamaAttribute()
    {
        return $this->kategori?->nama_kategori ?? '-';
    }

    /**
     * Status badge lengkap
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->isExpired()) {
            return '<span class="badge bg-dark">Kadaluarsa</span>';
        }
        if ($this->stok == 0) {
            return '<span class="badge bg-danger">Habis</span>';
        }
        if ($this->isStokMinimum()) {
            return '<span class="badge bg-warning text-dark">Stok Minimum</span>';
        }
        if ($this->isNearExpired()) {
            return '<span class="badge bg-info">Akan Kadaluarsa</span>';
        }
        return '<span class="badge bg-success">Normal</span>';
    }

    // ==================== HELPER METHODS ====================

    /**
     * Cek apakah stok mencapai minimum
     */
    public function isStokMinimum()
    {
        return $this->stok <= $this->stok_minimum && $this->stok > 0;
    }

    /**
     * Cek apakah akan kadaluarsa (dalam 90 hari)
     * FIXED: Cek dengan benar apakah belum kadaluarsa dan dalam 90 hari
     */
    public function isNearExpired()
    {
        if (!$this->tanggal_kadaluarsa) return false;
        
        $daysUntilExpiry = now()->diffInDays($this->tanggal_kadaluarsa, false);
        
        // Return true jika belum kadaluarsa DAN kurang dari 90 hari
        return $daysUntilExpiry >= 0 && $daysUntilExpiry <= 90;
    }

    /**
     * Cek apakah sudah kadaluarsa
     */
    public function isExpired()
    {
        if (!$this->tanggal_kadaluarsa) return false;
        return $this->tanggal_kadaluarsa->isPast();
    }

    /**
     * Get sisa hari sebelum kadaluarsa
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->tanggal_kadaluarsa) return null;
        
        // Return nilai signed (bisa positif/negatif)
        return now()->diffInDays($this->tanggal_kadaluarsa, false);
    }
    /**
     * Kurangi stok
     */
    public function kurangiStok(int $jumlah)
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok tidak mencukupi. Stok tersedia: {$this->stok}");
        }

        $this->stok -= $jumlah;
        $this->save();

        return $this;
    }

    /**
     * Tambah stok
     */
    public function tambahStok(int $jumlah)
    {
        $this->stok += $jumlah;
        $this->save();

        return $this;
    }

    /**
     * Cek apakah obat tersedia (stok > 0)
     */
    public function isTersedia()
    {
        return $this->stok > 0 && !$this->isExpired();
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk obat dengan stok minimum
     */
    public function scopeStokMinimum($query)
    {
        return $query->whereRaw('stok <= stok_minimum')->where('stok', '>', 0);
    }

    /**
     * Scope untuk obat yang akan kadaluarsa (3 bulan)
     */
    public function scopeKadaluarsa($query)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                     ->whereDate('tanggal_kadaluarsa', '>=', now())
                     ->whereDate('tanggal_kadaluarsa', '<=', now()->addMonths(3));
    }

    /**
     * Scope untuk obat yang sudah kadaluarsa
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                     ->whereDate('tanggal_kadaluarsa', '<', now());
    }

    /**
     * Scope untuk obat yang habis
     */
    public function scopeHabis($query)
    {
        return $query->where('stok', 0);
    }

    /**
     * Scope untuk obat tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0)
                     ->where(function($q) {
                         $q->whereNull('tanggal_kadaluarsa')
                           ->orWhereDate('tanggal_kadaluarsa', '>=', now());
                     });
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama_obat', 'like', "%{$term}%")
              ->orWhere('no_batch', 'like', "%{$term}%");
        });
    }
}