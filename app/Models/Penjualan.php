<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        'bukti_pembayaran', // TAMBAHAN
        'catatan', // TAMBAHAN
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // ==================== RELASI ====================

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    // ==================== ACCESSOR ====================

    public function getTotalHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getDiskonFormatAttribute()
    {
        return 'Rp ' . number_format($this->diskon, 0, ',', '.');
    }

    public function getGrandTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    /**
     * Get URL bukti pembayaran
     */
    public function getBuktiPembayaranUrlAttribute()
    {
        if (!$this->bukti_pembayaran) {
            return null;
        }
        return Storage::url($this->bukti_pembayaran);
    }

    /**
     * Check apakah bukti pembayaran ada
     */
    public function hasBuktiPembayaran()
    {
        return !empty($this->bukti_pembayaran) && Storage::disk('public')->exists($this->bukti_pembayaran);
    }

    public function getStatusPembayaranBadgeAttribute()
    {
        $badges = [
            'lunas' => '<span class="badge bg-success">Lunas</span>',
            'pending' => '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>',
            'belum_lunas' => '<span class="badge bg-danger">Belum Lunas</span>',
            'batal' => '<span class="badge bg-secondary">Dibatalkan</span>',
        ];

        return $badges[$this->status_pembayaran] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

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

    public function getPelangganNamaAttribute()
    {
        return $this->pelanggan?->nama_pelanggan ?? 'Umum';
    }

    public function getKasirNamaAttribute()
    {
        return $this->user?->name ?? '-';
    }

    public function getTotalItemAttribute()
    {
        return $this->details()->sum('jumlah');
    }

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

    public function getTotalProfitFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_profit, 0, ',', '.');
    }

    // ==================== HELPER METHODS ====================

    public function hitungTotal()
    {
        $this->total_harga = $this->details()->sum('subtotal');
        $this->grand_total = $this->total_harga - $this->diskon;
        $this->save();

        return $this;
    }

    /**
     * Approve pembayaran (untuk apoteker)
     * - Set status jadi lunas
     * - Kurangi stok obat
     */
    public function approve()
    {
        if ($this->status_pembayaran !== 'pending') {
            throw new \Exception('Hanya pesanan pending yang bisa diapprove');
        }

        DB::beginTransaction();
        try {
            // Validasi stok
            foreach ($this->details as $detail) {
                if ($detail->obat->stok < $detail->jumlah) {
                    throw new \Exception("Stok {$detail->obat->nama_obat} tidak mencukupi");
                }
            }

            // Kurangi stok
            foreach ($this->details as $detail) {
                $detail->obat->kurangiStok($detail->jumlah);
            }

            // Update status
            $this->update([
                'status_pembayaran' => 'lunas',
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject pembayaran (untuk apoteker)
     */
    public function reject($alasan = null)
    {
        if ($this->status_pembayaran !== 'pending') {
            throw new \Exception('Hanya pesanan pending yang bisa direject');
        }

        $this->update([
            'status_pembayaran' => 'batal',
            'catatan' => $alasan ? "Ditolak: {$alasan}" : 'Ditolak oleh apoteker',
        ]);

        return true;
    }

    public function bayar()
    {
        $this->status_pembayaran = 'lunas';
        $this->save();

        return $this;
    }

    public function isLunas()
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function isPending()
    {
        return $this->status_pembayaran === 'pending';
    }

    public function isBatal()
    {
        return $this->status_pembayaran === 'batal';
    }

    public function getPersentaseDiskonAttribute()
    {
        if ($this->total_harga == 0) return 0;
        return ($this->diskon / $this->total_harga) * 100;
    }

    // ==================== SCOPES ====================

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopePending($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', 'belum_lunas');
    }

    public function scopeBatal($query)
    {
        return $query->where('status_pembayaran', 'batal');
    }

    public function scopeByPelanggan($query, $pelangganId)
    {
        return $query->where('pelanggan_id', $pelangganId);
    }

    public function scopeWalkIn($query)
    {
        return $query->whereNull('pelanggan_id');
    }

    public function scopeByMetode($query, $metode)
    {
        return $query->where('metode_pembayaran', $metode);
    }

    public function scopeByDate($query, $start, $end = null)
    {
        if ($end) {
            return $query->whereBetween('tanggal_penjualan', [$start, $end]);
        }
        return $query->whereDate('tanggal_penjualan', $start);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_penjualan', today());
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_penjualan', now()->month)
                     ->whereYear('tanggal_penjualan', now()->year);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal_penjualan', now()->year);
    }

    // ==================== STATIC METHODS ====================

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