<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature',
        'action',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Role (Many to Many)
     * Satu permission bisa dimiliki banyak role
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    // ==================== SCOPES ====================

    /**
     * Scope untuk filter berdasarkan feature
     */
    public function scopeByFeature($query, string $feature)
    {
        return $query->where('feature', $feature);
    }

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get permission dalam format "feature.action"
     */
    public function getFullNameAttribute()
    {
        return "{$this->feature}.{$this->action}";
    }
}