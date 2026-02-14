<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function kategori()
    {
        return $this->belongsTo(KategoriObat::class, 'kategori_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'obat_id');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'obat_id');
    }

    // ==================== ACCESSOR ====================

    public function getHargaJualFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    public function getHargaBeliFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    public function getProfitMarginAttribute()
    {
        if ($this->harga_beli == 0) return 0;
        return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
    }

    public function getProfitMarginFormatAttribute()
    {
        return number_format($this->profit_margin, 2) . '%';
    }

    public function getKategoriNamaAttribute()
    {
        return $this->kategori?->nama_kategori ?? '-';
    }

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

    public function getStokStatusAttribute()
    {
        if ($this->stok <= 0) {
            return 'habis';
        } elseif ($this->stok <= $this->stok_minimum) {
            return 'menipis';
        } else {
            return 'tersedia';
        }
    }

    public function getStokBadgeAttribute()
    {
        $status = $this->stok_status;
        
        $badges = [
            'habis' => '<span class="badge bg-danger">Habis</span>',
            'menipis' => '<span class="badge bg-warning">Menipis</span>',
            'tersedia' => '<span class="badge bg-success">Tersedia</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getProfitPerUnitAttribute()
    {
        return $this->harga_jual - $this->harga_beli;
    }

    public function getProfitPerUnitFormatAttribute()
    {
        return 'Rp ' . number_format($this->profit_per_unit, 0, ',', '.');
    }

    public function getTotalNilaiStokAttribute()
    {
        return $this->harga_beli * $this->stok;
    }

    public function getTotalNilaiStokFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_nilai_stok, 0, ',', '.');
    }

    public function getAlertStokAttribute()
    {
        if ($this->stok <= 0) {
            return "HABIS! Stok {$this->nama_obat} sudah habis";
        } elseif ($this->stok <= $this->stok_minimum) {
            return "PERINGATAN! Stok {$this->nama_obat} tinggal {$this->stok} {$this->satuan}";
        }
        
        return null;
    }

    public function getKodeNamaAttribute()
    {
        return "{$this->kode_obat} - {$this->nama_obat}";
    }

    // ==================== HELPER METHODS ====================

    public function isStokMinimum()
    {
        return $this->stok <= $this->stok_minimum && $this->stok > 0;
    }

    public function isNearExpired()
    {
        if (!$this->tanggal_kadaluarsa) return false;
        
        $daysUntilExpiry = now()->diffInDays($this->tanggal_kadaluarsa, false);
        
        // Return true jika belum kadaluarsa DAN kurang dari 90 hari
        return $daysUntilExpiry >= 0 && $daysUntilExpiry <= 90;
    }

    public function isExpired()
    {
        if (!$this->tanggal_kadaluarsa) return false;
        return $this->tanggal_kadaluarsa->isPast();
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->tanggal_kadaluarsa) return null;
        
        // Return nilai signed (bisa positif/negatif)
        return now()->diffInDays($this->tanggal_kadaluarsa, false);
    }

    public function kurangiStok(int $jumlah)
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok tidak mencukupi. Stok tersedia: {$this->stok}");
        }

        $this->stok -= $jumlah;
        $this->save();

        return $this;
    }

    public function tambahStok(int $jumlah)
    {
        $this->stok += $jumlah;
        $this->save();

        return $this;
    }

    public function updateStok($jumlah)
    {
        $this->stok = $jumlah;
        $this->save();

        return true;
    }

    public function cekStok($jumlah)
    {
        return $this->stok >= $jumlah;
    }

    public function isTersedia()
    {
        return $this->stok > 0 && !$this->isExpired();
    }

    /**
     * Get persentase stok tersisa
     * Berguna untuk progress bar
     */
    public function getPersentaseStokAttribute()
    {
        if ($this->stok_minimum == 0) return 100;
        
        $percentage = ($this->stok / $this->stok_minimum) * 100;
        return min($percentage, 100); // Max 100%
    }

    /**
     * Get warna progress bar berdasarkan stok
     */
    public function getStokProgressColorAttribute()
    {
        $persentase = $this->persentase_stok;
        
        if ($persentase <= 0) return 'danger';
        if ($persentase <= 50) return 'warning';
        if ($persentase <= 100) return 'info';
        return 'success';
    }

    /**
     * Format tanggal kadaluarsa yang lebih readable
     */
    public function getTanggalKadaluarsaFormatAttribute()
    {
        if (!$this->tanggal_kadaluarsa) {
            return 'Tidak ada';
        }
        
        return $this->tanggal_kadaluarsa->format('d F Y');
    }

    /**
     * Get status kadaluarsa dengan detail
     */
    public function getKadaluarsaStatusAttribute()
    {
        if (!$this->tanggal_kadaluarsa) {
            return 'Tidak ada tanggal kadaluarsa';
        }
        
        if ($this->isExpired()) {
            $days = abs($this->days_until_expiry);
            return "Sudah kadaluarsa {$days} hari yang lalu";
        }
        
        $days = $this->days_until_expiry;
        
        if ($days <= 30) {
            return "Akan kadaluarsa dalam {$days} hari";
        } elseif ($days <= 90) {
            return "Akan kadaluarsa dalam " . ceil($days / 30) . " bulan";
        } else {
            return "Masih lama kadaluarsa";
        }
    }

    // ==================== SCOPES ====================

    public function scopeStokMinimum($query)
    {
        return $query->whereRaw('stok <= stok_minimum')->where('stok', '>', 0);
    }

    public function scopeKadaluarsa($query)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                     ->whereDate('tanggal_kadaluarsa', '>=', now())
                     ->whereDate('tanggal_kadaluarsa', '<=', now()->addMonths(3));
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                     ->whereDate('tanggal_kadaluarsa', '<', now());
    }

    public function scopeHabis($query)
    {
        return $query->where('stok', 0);
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0)
                     ->where(function($q) {
                         $q->whereNull('tanggal_kadaluarsa')
                           ->orWhereDate('tanggal_kadaluarsa', '>=', now());
                     });
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama_obat', 'like', "%{$term}%")
              ->orWhere('no_batch', 'like', "%{$term}%");
        });
    }

    public function scopeStokHabis($query)
    {
        return $query->where('stok', '<=', 0);
    }

    public function scopeTidakKadaluarsa($query)
    {
        return $query->where(function($q) {
            $q->whereNull('tanggal_kadaluarsa')
            ->orWhere('tanggal_kadaluarsa', '>', now());
        });
    }

    public function scopeAkanKadaluarsaDalam($query, $hari = 90)
    {
        return $query->whereNotNull('tanggal_kadaluarsa')
                    ->whereBetween('tanggal_kadaluarsa', [
                        now(),
                        now()->addDays($hari)
                    ]);
    }

    public function scopeByHargaRange($query, $min, $max)
    {
        return $query->whereBetween('harga_jual', [$min, $max]);
    }

    public function scopeByMinProfit($query, $minPercentage)
    {
        return $query->whereRaw("((harga_jual - harga_beli) / harga_beli * 100) >= ?", [$minPercentage]);
    }

    public function scopeTerlaris($query, $limit = 10)
    {
        return $query->withCount('detailPenjualan')
                    ->orderBy('detail_penjualan_count', 'desc')
                    ->limit($limit);
    }

    // ==================== OPTIONAL - STATIC METHODS ====================

    /**
     * Get obat yang perlu direstock
     * Static method untuk dashboard
     */
    public static function perluRestock()
    {
        return self::whereRaw('stok <= stok_minimum')
                ->orderBy('stok', 'asc')
                ->get();
    }

    /**
     * Get obat yang hampir kadaluarsa
     * Static method untuk dashboard
     */
    public static function hampirKadaluarsa($days = 90)
    {
        return self::whereNotNull('tanggal_kadaluarsa')
                ->whereBetween('tanggal_kadaluarsa', [
                    now(),
                    now()->addDays($days)
                ])
                ->orderBy('tanggal_kadaluarsa', 'asc')
                ->get();
    }

    /**
     * Get statistik stok keseluruhan
     * Untuk dashboard overview
     */
    public static function statistikStok()
    {
        return [
            'total_obat' => self::count(),
            'stok_habis' => self::habis()->count(),
            'stok_minimum' => self::stokMinimum()->count(),
            'akan_kadaluarsa' => self::kadaluarsa()->count(),
            'total_nilai_stok' => self::sum(DB::raw('harga_beli * stok')),
            'total_item_stok' => self::sum('stok'),
        ];
    }

    /**
     * Get obat dengan profit tertinggi
     */
    public static function profitTertinggi($limit = 10)
    {
        return self::selectRaw('*, (harga_jual - harga_beli) as profit')
                ->orderBy('profit', 'desc')
                ->limit($limit)
                ->get();
    }

    /**
     * Get total nilai inventory (semua stok × harga beli)
     */
    public static function totalNilaiInventory()
    {
        return self::sum(DB::raw('stok * harga_beli'));
    }

    /**
     * Get total potensi profit (semua stok × profit per unit)
     */
    public static function totalPotensiProfit()
    {
        return self::sum(DB::raw('stok * (harga_jual - harga_beli)'));
    }

    // ==================== OPTIONAL - VALIDASI TAMBAHAN ====================

    /**
     * Validasi sebelum update harga
     * Pastikan harga jual > harga beli
     */
    public function validateHarga()
    {
        if ($this->harga_jual <= $this->harga_beli) {
            throw new \Exception("Harga jual harus lebih besar dari harga beli!");
        }
        
        return true;
    }

    /**
     * Validasi stok tidak boleh negatif
     */
    public function validateStok()
    {
        if ($this->stok < 0) {
            throw new \Exception("Stok tidak boleh negatif!");
        }
        
        return true;
    }

    /**
     * Auto-generate kode obat jika belum ada
     * Contoh format: OBT-20260214-001
     */
    public static function generateKodeObat()
    {
        $prefix = 'OBT';
        $date = date('Ymd');
        
        $lastObat = self::where('kode_obat', 'like', "{$prefix}-{$date}-%")
                        ->latest()
                        ->first();
        
        if ($lastObat) {
            $lastNumber = intval(substr($lastObat->kode_obat, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf("%s-%s-%03d", $prefix, $date, $newNumber);
    }

}