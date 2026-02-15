<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nama_role' => 'Admin',
                'deskripsi' => 'Administrator sistem dengan akses penuh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Pelanggan',
                'deskripsi' => 'Customer yang bisa membeli obat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Apoteker',
                'deskripsi' => 'Apoteker yang mengelola obat dan memberikan konsultasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
