<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriObat extends Model
{
    use HasFactory;

    protected $table = 'kategori_obat';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Obat (One to Many)
     * Satu kategori bisa memiliki banyak obat
     */
    public function obat()
    {
        return $this->hasMany(Obat::class, 'kategori_id');
    }

    // ==================== ACCESSOR ====================

    /**
     * Get jumlah obat dalam kategori ini
     */
    public function getTotalObatAttribute()
    {
        return $this->obat()->count();
    }

    /**
     * Get total stok obat dalam kategori ini
     */
    public function getTotalStokAttribute()
    {
        return $this->obat()->sum('stok');
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk kategori yang punya obat
     */
    public function scopeHasObat($query)
    {
        return $query->has('obat');
    }

    /**
     * Scope untuk kategori yang tidak punya obat
     */
    public function scopeEmpty($query)
    {
        return $query->doesntHave('obat');
    }
}