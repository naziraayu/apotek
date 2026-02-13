<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat atau update users
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@apotek.com'],
            [
                'role_id' => 1, // Admin
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir2@apotek.com'],
            [
                'role_id' => 2, // Kasir
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password123'),
                'no_telp' => '081234567893',
                'alamat' => 'Jl. Thamrin No. 321, Jakarta',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'apoteker@apotek.com'],
            [
                'role_id' => 3, // Apoteker
                'name' => 'Dr. Ahmad Fauzi, S.Farm., Apt',
                'password' => Hash::make('password123'),
                'no_telp' => '081234567894',
                'alamat' => 'Jl. Kuningan No. 654, Jakarta',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        // Kasih semua permission ke role Admin (role_id = 1)
        $adminRole = Role::find(1);
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::all()->pluck('id'));
        }

        // Kasih permission ke role Kasir (role_id = 2)
        // Kasir: mengelola transaksi penjualan
        $kasirRole = Role::find(2);
        if ($kasirRole) {
            $kasirPermissions = Permission::where(function($query) {
                // Penjualan - Full access
                $query->where(function($q) {
                    $q->where('feature', 'penjualan')
                      ->whereIn('action', ['read', 'add', 'detail', 'update', 'delete', 'export']);
                })
                // Obat - Read only
                ->orWhere(function($q) {
                    $q->where('feature', 'obat')
                      ->whereIn('action', ['read', 'detail']);
                })
                // Pelanggan - CRUD
                ->orWhere(function($q) {
                    $q->where('feature', 'pelanggan')
                      ->whereIn('action', ['read', 'add', 'detail', 'update']);
                });
            })->pluck('id');
            
            $kasirRole->permissions()->sync($kasirPermissions);
        }

        // Kasih permission ke role Apoteker (role_id = 3)
        // Apoteker: mengelola obat, stok, supplier, pembelian
        $apotekerRole = Role::find(3);
        if ($apotekerRole) {
            $apotekerPermissions = Permission::where(function($query) {
                // Obat - Full access
                $query->where(function($q) {
                    $q->where('feature', 'obat')
                      ->whereIn('action', ['read', 'add', 'detail', 'update', 'delete', 'import', 'export']);
                })
                // Kategori Obat - Full access
                ->orWhere(function($q) {
                    $q->where('feature', 'kategori_obat')
                      ->whereIn('action', ['read', 'add', 'detail', 'update', 'delete', 'export']);
                })
                // Supplier - Full access
                ->orWhere(function($q) {
                    $q->where('feature', 'supplier')
                      ->whereIn('action', ['read', 'add', 'detail', 'update', 'delete', 'import', 'export']);
                })
                // Pembelian - Full access
                ->orWhere(function($q) {
                    $q->where('feature', 'pembelian')
                      ->whereIn('action', ['read', 'add', 'detail', 'update', 'delete', 'import', 'export']);
                })
                // Penjualan - Read only
                ->orWhere(function($q) {
                    $q->where('feature', 'penjualan')
                      ->whereIn('action', ['read', 'detail', 'export']);
                });
            })->pluck('id');
            
            $apotekerRole->permissions()->sync($apotekerPermissions);
        }
    }
}