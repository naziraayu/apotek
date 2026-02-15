<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'no_telp',
        'alamat',
        'status',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==================== RELASI ====================
    
    /**
     * Relasi ke Role (Many to One)
     * Satu user memiliki satu role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi ke Penjualan (One to Many)
     * Satu user (kasir) bisa melakukan banyak penjualan
     */
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'user_id');
    }

    /**
     * Relasi ke Pembelian (One to Many)
     * Satu user bisa melakukan banyak pembelian
     */
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'user_id');
    }

    // ==================== ACCESSOR & HELPER ====================

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'aktif' 
            ? '<span class="badge bg-success">Aktif</span>' 
            : '<span class="badge bg-danger">Non-Aktif</span>';
    }

    /**
     * Get role name
     */
    public function getRoleNameAttribute()
    {
        return $this->role?->nama_role ?? 'No Role';
    }

    // ==================== PERMISSION HELPERS ====================

    /**
     * Get all permissions dari role
     */
    public function permissions()
    {
        return $this->role?->permissions ?? collect();
    }

    /**
     * Check apakah user punya permission tertentu
     * 
     * @param string $action (read, add, update, delete, etc)
     * @param string|null $feature (obat, penjualan, pembelian, etc)
     * @return bool
     */
    public function hasPermission(string $action, ?string $feature = null): bool
    {
        if (!$this->role) return false;

        $query = $this->role->permissions();

        if ($feature) {
            $query->where('feature', $feature);
        }

        return $query->where('action', $action)->exists();
    }

    /**
     * Get all features yang bisa diakses user
     */
    public function features()
    {
        if (!$this->role) return collect();

        return $this->role->permissions()->pluck('feature')->unique();
    }

    /**
     * Check apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role?->nama_role === 'Admin';
    }

    /**
     * Check apakah user adalah kasir
     */
    public function isPelanggan()
    {
        return $this->role?->nama_role === 'Pelanggan';
    }

    /**
     * Check apakah user adalah apoteker
     */
    public function isApoteker()
    {
        return $this->role?->nama_role === 'Apoteker';
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk filter user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk filter user non-aktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'non-aktif');
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }
}