<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'no_nota',
        'pelanggan_id',
        'user_id',
        'tanggal_penjualan',
        'total_harga',
        'diskon',
        'grand_total',
        'status_pembayaran',
        'metode_pembayaran',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Pelanggan (Many to One)
     * Banyak penjualan dari satu pelanggan
     * Nullable karena bisa juga tanpa pelanggan (walk-in)
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Relasi ke User/Kasir (Many to One)
     * Banyak penjualan dilakukan oleh satu kasir
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke DetailPenjualan (One to Many)
     * Satu penjualan punya banyak detail
     */
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    // ==================== ACCESSOR ====================

    /**
     * Get total harga format
     */
    public function getTotalHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Get diskon format
     */
    public function getDiskonFormatAttribute()
    {
        return 'Rp ' . number_format($this->diskon, 0, ',', '.');
    }

    /**
     * Get grand total format
     */
    public function getGrandTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    /**
     * Get status pembayaran badge
     */
    public function getStatusPembayaranBadgeAttribute()
    {
        $badges = [
            'lunas' => '<span class="badge bg-success">Lunas</span>',
            'belum_lunas' => '<span class="badge bg-danger">Belum Lunas</span>',
        ];

        return $badges[$this->status_pembayaran] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get metode pembayaran badge
     */
    public function getMetodePembayaranBadgeAttribute()
    {
        $badges = [
            'tunai' => '<span class="badge bg-success">Tunai</span>',
            'transfer' => '<span class="badge bg-info">Transfer</span>',
            'kartu_kredit' => '<span class="badge bg-warning text-dark">Kartu Kredit</span>',
            'e-wallet' => '<span class="badge bg-primary">E-Wallet</span>',
        ];

        return $badges[$this->metode_pembayaran] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get nama pelanggan atau "Umum"
     */
    public function getPelangganNamaAttribute()
    {
        return $this->pelanggan?->nama_pelanggan ?? 'Umum';
    }

    /**
     * Get nama kasir
     */
    public function getKasirNamaAttribute()
    {
        return $this->user?->name ?? '-';
    }

    /**
     * Get total item
     */
    public function getTotalItemAttribute()
    {
        return $this->details()->sum('jumlah');
    }

    /**
     * Get total profit
     */
    public function getTotalProfitAttribute()
    {
        $profit = 0;
        foreach ($this->details as $detail) {
            $hargaBeli = $detail->obat->harga_beli ?? 0;
            $profitPerItem = ($detail->harga_satuan - $hargaBeli) * $detail->jumlah;
            $profit += $profitPerItem;
        }
        return $profit;
    }

    /**
     * Get total profit format
     */
    public function getTotalProfitFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_profit, 0, ',', '.');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Hitung total harga dari detail
     */
    public function hitungTotal()
    {
        $this->total_harga = $this->details()->sum('subtotal');
        $this->grand_total = $this->total_harga - $this->diskon;
        $this->save();

        return $this;
    }

    /**
     * Tandai sebagai lunas
     */
    public function bayar()
    {
        $this->status_pembayaran = 'lunas';
        $this->save();

        return $this;
    }

    /**
     * Check apakah sudah lunas
     */
    public function isLunas()
    {
        return $this->status_pembayaran === 'lunas';
    }

    /**
     * Get persentase diskon
     */
    public function getPersentaseDiskonAttribute()
    {
        if ($this->total_harga == 0) return 0;
        return ($this->diskon / $this->total_harga) * 100;
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk penjualan lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    /**
     * Scope untuk penjualan belum lunas
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', 'belum_lunas');
    }

    /**
     * Scope untuk filter berdasarkan pelanggan
     */
    public function scopeByPelanggan($query, $pelangganId)
    {
        return $query->where('pelanggan_id', $pelangganId);
    }

    /**
     * Scope untuk penjualan walk-in (tanpa pelanggan)
     */
    public function scopeWalkIn($query)
    {
        return $query->whereNull('pelanggan_id');
    }

    /**
     * Scope untuk filter berdasarkan metode pembayaran
     */
    public function scopeByMetode($query, $metode)
    {
        return $query->where('metode_pembayaran', $metode);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDate($query, $start, $end = null)
    {
        if ($end) {
            return $query->whereBetween('tanggal_penjualan', [$start, $end]);
        }
        return $query->whereDate('tanggal_penjualan', $start);
    }

    /**
     * Scope untuk hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_penjualan', today());
    }

    /**
     * Scope untuk bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_penjualan', now()->month)
                     ->whereYear('tanggal_penjualan', now()->year);
    }

    /**
     * Scope untuk tahun ini
     */
    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal_penjualan', now()->year);
    }

    // ==================== STATIC METHODS ====================

    /**
     * Generate nomor nota otomatis
     */
    public static function generateNoNota()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        
        $lastNota = self::where('no_nota', 'like', "{$prefix}-{$date}-%")
                        ->latest()
                        ->first();

        if ($lastNota) {
            $lastNumber = intval(substr($lastNota->no_nota, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf("%s-%s-%04d", $prefix, $date, $newNumber);
    }
}