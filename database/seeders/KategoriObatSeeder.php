<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriObat;
use Illuminate\Support\Facades\DB;

class KategoriObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('kategori_obat')->delete();

        $kategoris = [
            [
                'nama_kategori' => 'Obat Bebas',
                'deskripsi' => 'Obat yang dapat dibeli tanpa resep dokter, aman untuk penggunaan umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Bebas Terbatas',
                'deskripsi' => 'Obat yang dapat dibeli tanpa resep dengan peringatan khusus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Keras',
                'deskripsi' => 'Obat yang hanya dapat dibeli dengan resep dokter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Antibiotik',
                'deskripsi' => 'Obat untuk membunuh atau menghambat pertumbuhan bakteri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Vitamin & Suplemen',
                'deskripsi' => 'Suplemen untuk memenuhi kebutuhan vitamin dan mineral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Demam & Nyeri',
                'deskripsi' => 'Obat untuk menurunkan demam dan meredakan nyeri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Batuk & Flu',
                'deskripsi' => 'Obat untuk meredakan gejala batuk, pilek, dan flu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Pencernaan',
                'deskripsi' => 'Obat untuk mengatasi masalah pencernaan seperti maag, diare, dll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Alergi',
                'deskripsi' => 'Obat untuk mengatasi reaksi alergi dan antihistamin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Jantung & Darah',
                'deskripsi' => 'Obat untuk penyakit jantung, hipertensi, dan gangguan pembuluh darah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Diabetes',
                'deskripsi' => 'Obat untuk mengontrol kadar gula darah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Kulit',
                'deskripsi' => 'Obat topikal untuk masalah kulit seperti gatal, eksim, jerawat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Mata',
                'deskripsi' => 'Obat tetes mata dan salep mata',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Telinga',
                'deskripsi' => 'Obat tetes telinga untuk infeksi dan peradangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Obat Herbal',
                'deskripsi' => 'Obat tradisional dan jamu yang terbuat dari bahan alami',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriObat::create($kategori);
        }

        $this->command->info('✅ Kategori Obat seeder completed! ' . count($kategoris) . ' categories created.');
    }
}