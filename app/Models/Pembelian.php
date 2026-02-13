<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'no_nota',
        'supplier_id',
        'user_id',
        'tanggal_pembelian',
        'total_harga',
        'diskon',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Supplier (Many to One)
     * Banyak pembelian dari satu supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relasi ke User (Many to One)
     * Banyak pembelian dilakukan oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke DetailPembelian (One to Many)
     * Satu pembelian punya banyak detail
     */
    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
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
     * Get grand total (total - diskon)
     */
    public function getGrandTotalAttribute()
    {
        return $this->total_harga - $this->diskon;
    }

    /**
     * Get grand total format
     */
    public function getGrandTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'batal' => '<span class="badge bg-danger">Batal</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get nama supplier
     */
    public function getSupplierNamaAttribute()
    {
        return $this->supplier?->nama_supplier ?? '-';
    }

    /**
     * Get nama user
     */
    public function getUserNamaAttribute()
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

    // ==================== HELPER METHODS ====================

    /**
     * Hitung total harga dari detail
     */
    public function hitungTotal()
    {
        $this->total_harga = $this->details()->sum('subtotal');
        $this->save();

        return $this;
    }

    /**
     * Update status ke selesai
     */
    public function selesai()
    {
        $this->status = 'selesai';
        $this->save();

        // Update stok obat
        foreach ($this->details as $detail) {
            $detail->obat->tambahStok($detail->jumlah);
            
            // Update tanggal kadaluarsa dan batch jika ada
            if ($detail->tanggal_kadaluarsa) {
                $detail->obat->update([
                    'tanggal_kadaluarsa' => $detail->tanggal_kadaluarsa,
                    'no_batch' => $detail->no_batch,
                ]);
            }
        }

        return $this;
    }

    /**
     * Batalkan pembelian
     */
    public function batal()
    {
        // Jika sudah selesai, kembalikan stok
        if ($this->status === 'selesai') {
            foreach ($this->details as $detail) {
                $detail->obat->kurangiStok($detail->jumlah);
            }
        }

        $this->status = 'batal';
        $this->save();

        return $this;
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk pembelian pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk pembelian selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk pembelian batal
     */
    public function scopeBatal($query)
    {
        return $query->where('status', 'batal');
    }

    /**
     * Scope untuk filter berdasarkan supplier
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDate($query, $start, $end = null)
    {
        if ($end) {
            return $query->whereBetween('tanggal_pembelian', [$start, $end]);
        }
        return $query->whereDate('tanggal_pembelian', $start);
    }

    /**
     * Scope untuk bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_pembelian', now()->month)
                     ->whereYear('tanggal_pembelian', now()->year);
    }

    /**
     * Scope untuk tahun ini
     */
    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal_pembelian', now()->year);
    }

    // ==================== STATIC METHODS ====================

    /**
     * Generate nomor nota otomatis
     */
    public static function generateNoNota()
    {
        $prefix = 'PO';
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