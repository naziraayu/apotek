<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_role',
        'deskripsi',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke User (One to Many)
     * Satu role bisa dimiliki banyak user
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Relasi ke Permission (Many to Many)
     * Satu role bisa punya banyak permission
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check apakah role punya permission tertentu
     */
    public function hasPermission(string $action, ?string $feature = null): bool
    {
        $query = $this->permissions();

        if ($feature) {
            $query->where('feature', $feature);
        }

        return $query->where('action', $action)->exists();
    }

    /**
     * Assign permission ke role
     */
    public function givePermission($permissionId)
    {
        return $this->permissions()->syncWithoutDetaching($permissionId);
    }

    /**
     * Revoke permission dari role
     */
    public function revokePermission($permissionId)
    {
        return $this->permissions()->detach($permissionId);
    }

    /**
     * Sync semua permission untuk role
     */
    public function syncPermissions(array $permissionIds)
    {
        return $this->permissions()->sync($permissionIds);
    }
}