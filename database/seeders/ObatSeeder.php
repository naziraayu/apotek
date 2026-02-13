<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;
use App\Models\KategoriObat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('obat')->delete();

        // Get kategori IDs
        $kategoris = KategoriObat::pluck('id', 'nama_kategori')->toArray();

        $obats = [
            // Obat Demam & Nyeri
            [
                'kategori_id' => $kategoris['Obat Demam & Nyeri'] ?? 1,
                'nama_obat' => 'Paracetamol 500mg',
                'deskripsi' => 'Obat penurun panas dan pereda nyeri ringan hingga sedang',
                'satuan' => 'Tablet',
                'harga_beli' => 500,
                'harga_jual' => 1000,
                'stok' => 500,
                'stok_minimum' => 50,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'PCT-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Demam & Nyeri'] ?? 1,
                'nama_obat' => 'Paracetamol Sirup 120mg/5ml',
                'deskripsi' => 'Obat penurun panas untuk anak dalam bentuk sirup',
                'satuan' => 'Botol',
                'harga_beli' => 8000,
                'harga_jual' => 15000,
                'stok' => 80,
                'stok_minimum' => 20,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(12),
                'no_batch' => 'PCTS-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Demam & Nyeri'] ?? 1,
                'nama_obat' => 'Ibuprofen 400mg',
                'deskripsi' => 'Anti inflamasi dan pereda nyeri',
                'satuan' => 'Tablet',
                'harga_beli' => 800,
                'harga_jual' => 1500,
                'stok' => 300,
                'stok_minimum' => 50,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24),
                'no_batch' => 'IBU-2024-001',
            ],

            // Antibiotik
            [
                'kategori_id' => $kategoris['Antibiotik'] ?? 2,
                'nama_obat' => 'Amoxicillin 500mg',
                'deskripsi' => 'Antibiotik untuk infeksi bakteri',
                'satuan' => 'Kapsul',
                'harga_beli' => 1200,
                'harga_jual' => 2500,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'AMX-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Antibiotik'] ?? 2,
                'nama_obat' => 'Ciprofloxacin 500mg',
                'deskripsi' => 'Antibiotik golongan fluoroquinolone',
                'satuan' => 'Tablet',
                'harga_beli' => 2000,
                'harga_jual' => 4000,
                'stok' => 150,
                'stok_minimum' => 25,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'CIP-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Antibiotik'] ?? 2,
                'nama_obat' => 'Cefadroxil 500mg',
                'deskripsi' => 'Antibiotik sefalosporin generasi pertama',
                'satuan' => 'Kapsul',
                'harga_beli' => 1500,
                'harga_jual' => 3000,
                'stok' => 180,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(22),
                'no_batch' => 'CEF-2024-001',
            ],

            // Vitamin & Suplemen
            [
                'kategori_id' => $kategoris['Vitamin & Suplemen'] ?? 3,
                'nama_obat' => 'Vitamin C 500mg',
                'deskripsi' => 'Suplemen vitamin C untuk daya tahan tubuh',
                'satuan' => 'Tablet',
                'harga_beli' => 600,
                'harga_jual' => 1200,
                'stok' => 400,
                'stok_minimum' => 50,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24),
                'no_batch' => 'VITC-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Vitamin & Suplemen'] ?? 3,
                'nama_obat' => 'Multivitamin Tablet',
                'deskripsi' => 'Kombinasi vitamin dan mineral lengkap',
                'satuan' => 'Tablet',
                'harga_beli' => 1000,
                'harga_jual' => 2000,
                'stok' => 250,
                'stok_minimum' => 40,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'MULTI-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Vitamin & Suplemen'] ?? 3,
                'nama_obat' => 'Vitamin B Complex',
                'deskripsi' => 'Kombinasi vitamin B1, B6, B12',
                'satuan' => 'Kaplet',
                'harga_beli' => 800,
                'harga_jual' => 1600,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'VITB-2024-001',
            ],

            // Obat Batuk & Flu
            [
                'kategori_id' => $kategoris['Obat Batuk & Flu'] ?? 4,
                'nama_obat' => 'OBH Combi Sirup',
                'deskripsi' => 'Obat batuk berdahak dengan ekspektoran',
                'satuan' => 'Botol',
                'harga_beli' => 10000,
                'harga_jual' => 18000,
                'stok' => 100,
                'stok_minimum' => 20,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(15),
                'no_batch' => 'OBH-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Batuk & Flu'] ?? 4,
                'nama_obat' => 'Actifed Tablet',
                'deskripsi' => 'Obat flu dengan pseudoefedrin dan triprolidin',
                'satuan' => 'Tablet',
                'harga_beli' => 1500,
                'harga_jual' => 3000,
                'stok' => 150,
                'stok_minimum' => 25,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'ACT-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Batuk & Flu'] ?? 4,
                'nama_obat' => 'Decolgen Tablet',
                'deskripsi' => 'Obat flu dengan paracetamol, phenylpropanolamine, CTM',
                'satuan' => 'Tablet',
                'harga_beli' => 1000,
                'harga_jual' => 2000,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(16),
                'no_batch' => 'DEC-2024-001',
            ],

            // Obat Pencernaan
            [
                'kategori_id' => $kategoris['Obat Pencernaan'] ?? 5,
                'nama_obat' => 'Antasida DOEN Tablet',
                'deskripsi' => 'Obat maag untuk menetralkan asam lambung',
                'satuan' => 'Tablet',
                'harga_beli' => 500,
                'harga_jual' => 1000,
                'stok' => 300,
                'stok_minimum' => 40,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24),
                'no_batch' => 'ANT-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Pencernaan'] ?? 5,
                'nama_obat' => 'Omeprazole 20mg',
                'deskripsi' => 'Obat untuk mengurangi produksi asam lambung',
                'satuan' => 'Kapsul',
                'harga_beli' => 2000,
                'harga_jual' => 4000,
                'stok' => 150,
                'stok_minimum' => 25,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'OME-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Pencernaan'] ?? 5,
                'nama_obat' => 'New Diatabs',
                'deskripsi' => 'Obat diare dengan attapulgite',
                'satuan' => 'Tablet',
                'harga_beli' => 800,
                'harga_jual' => 1600,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'DIA-2024-001',
            ],

            // Obat Alergi
            [
                'kategori_id' => $kategoris['Obat Alergi'] ?? 6,
                'nama_obat' => 'Cetirizine 10mg',
                'deskripsi' => 'Antihistamin untuk alergi',
                'satuan' => 'Tablet',
                'harga_beli' => 1000,
                'harga_jual' => 2000,
                'stok' => 250,
                'stok_minimum' => 35,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(22),
                'no_batch' => 'CET-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Alergi'] ?? 6,
                'nama_obat' => 'Loratadine 10mg',
                'deskripsi' => 'Antihistamin generasi kedua untuk alergi',
                'satuan' => 'Tablet',
                'harga_beli' => 1200,
                'harga_jual' => 2400,
                'stok' => 180,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'LOR-2024-001',
            ],

            // Obat Jantung & Darah
            [
                'kategori_id' => $kategoris['Obat Jantung & Darah'] ?? 7,
                'nama_obat' => 'Amlodipine 5mg',
                'deskripsi' => 'Obat hipertensi golongan calcium channel blocker',
                'satuan' => 'Tablet',
                'harga_beli' => 1500,
                'harga_jual' => 3000,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24),
                'no_batch' => 'AML-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Jantung & Darah'] ?? 7,
                'nama_obat' => 'Captopril 25mg',
                'deskripsi' => 'Obat hipertensi golongan ACE inhibitor',
                'satuan' => 'Tablet',
                'harga_beli' => 800,
                'harga_jual' => 1600,
                'stok' => 250,
                'stok_minimum' => 40,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'CAP-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Jantung & Darah'] ?? 7,
                'nama_obat' => 'Simvastatin 10mg',
                'deskripsi' => 'Obat kolesterol tinggi',
                'satuan' => 'Tablet',
                'harga_beli' => 2000,
                'harga_jual' => 4000,
                'stok' => 150,
                'stok_minimum' => 25,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(22),
                'no_batch' => 'SIM-2024-001',
            ],

            // Obat Diabetes
            [
                'kategori_id' => $kategoris['Obat Diabetes'] ?? 8,
                'nama_obat' => 'Metformin 500mg',
                'deskripsi' => 'Obat diabetes tipe 2',
                'satuan' => 'Tablet',
                'harga_beli' => 1000,
                'harga_jual' => 2000,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24),
                'no_batch' => 'MET-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Diabetes'] ?? 8,
                'nama_obat' => 'Glimepiride 2mg',
                'deskripsi' => 'Obat diabetes untuk merangsang produksi insulin',
                'satuan' => 'Tablet',
                'harga_beli' => 1500,
                'harga_jual' => 3000,
                'stok' => 150,
                'stok_minimum' => 25,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'GLI-2024-001',
            ],

            // Obat Kulit
            [
                'kategori_id' => $kategoris['Obat Kulit'] ?? 9,
                'nama_obat' => 'Hydrocortisone Cream 1%',
                'deskripsi' => 'Krim kortikosteroid untuk peradangan kulit',
                'satuan' => 'Tube',
                'harga_beli' => 8000,
                'harga_jual' => 15000,
                'stok' => 80,
                'stok_minimum' => 15,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'HYD-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Kulit'] ?? 9,
                'nama_obat' => 'Acyclovir Cream 5%',
                'deskripsi' => 'Krim antiviral untuk herpes',
                'satuan' => 'Tube',
                'harga_beli' => 12000,
                'harga_jual' => 22000,
                'stok' => 60,
                'stok_minimum' => 15,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(16),
                'no_batch' => 'ACY-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Kulit'] ?? 9,
                'nama_obat' => 'Betadine Ointment',
                'deskripsi' => 'Salep antiseptik povidone iodine',
                'satuan' => 'Tube',
                'harga_beli' => 10000,
                'harga_jual' => 18000,
                'stok' => 70,
                'stok_minimum' => 15,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'BET-2024-001',
            ],

            // Obat Mata
            [
                'kategori_id' => $kategoris['Obat Mata'] ?? 10,
                'nama_obat' => 'Insto Dry Eyes',
                'deskripsi' => 'Obat tetes mata untuk mata kering',
                'satuan' => 'Botol',
                'harga_beli' => 12000,
                'harga_jual' => 22000,
                'stok' => 50,
                'stok_minimum' => 10,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(12),
                'no_batch' => 'INS-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Mata'] ?? 10,
                'nama_obat' => 'Cendo Xitrol',
                'deskripsi' => 'Obat tetes mata antibiotik dan kortikosteroid',
                'satuan' => 'Botol',
                'harga_beli' => 25000,
                'harga_jual' => 45000,
                'stok' => 40,
                'stok_minimum' => 10,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(15),
                'no_batch' => 'CEN-2024-001',
            ],

            // Obat dengan stok minimum (untuk testing alert)
            [
                'kategori_id' => $kategoris['Obat Demam & Nyeri'] ?? 1,
                'nama_obat' => 'Aspirin 100mg',
                'deskripsi' => 'Antiplatelet untuk pencegahan stroke',
                'satuan' => 'Tablet',
                'harga_beli' => 600,
                'harga_jual' => 1200,
                'stok' => 15, // Stok minimum!
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20),
                'no_batch' => 'ASP-2024-001',
            ],

            // Obat yang akan kadaluarsa (untuk testing alert)
            [
                'kategori_id' => $kategoris['Antibiotik'] ?? 2,
                'nama_obat' => 'Azithromycin 500mg',
                'deskripsi' => 'Antibiotik golongan makrolida',
                'satuan' => 'Tablet',
                'harga_beli' => 3000,
                'harga_jual' => 6000,
                'stok' => 100,
                'stok_minimum' => 20,
                'tanggal_kadaluarsa' => Carbon::now()->addDays(60), // Akan kadaluarsa!
                'no_batch' => 'AZI-2024-001',
            ],

            // Obat habis (untuk testing alert)
            [
                'kategori_id' => $kategoris['Obat Batuk & Flu'] ?? 4,
                'nama_obat' => 'Ambroxol 30mg',
                'deskripsi' => 'Obat pengencer dahak',
                'satuan' => 'Tablet',
                'harga_beli' => 800,
                'harga_jual' => 1600,
                'stok' => 0, // Habis!
                'stok_minimum' => 50,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'AMB-2024-001',
            ],

            // Obat Herbal
            [
                'kategori_id' => $kategoris['Obat Herbal'] ?? 11,
                'nama_obat' => 'Tolak Angin',
                'deskripsi' => 'Jamu tradisional untuk masuk angin',
                'satuan' => 'Sachet',
                'harga_beli' => 2000,
                'harga_jual' => 4000,
                'stok' => 200,
                'stok_minimum' => 30,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18),
                'no_batch' => 'TOL-2024-001',
            ],
            [
                'kategori_id' => $kategoris['Obat Herbal'] ?? 11,
                'nama_obat' => 'Antangin Syrup',
                'deskripsi' => 'Obat herbal untuk masuk angin dalam bentuk sirup',
                'satuan' => 'Sachet',
                'harga_beli' => 1500,
                'harga_jual' => 3000,
                'stok' => 150,
                'stok_minimum' => 25,
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(15),
                'no_batch' => 'ANTS-2024-001',
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }

        $this->command->info('✅ Obat seeder completed! ' . count($obats) . ' medicines created.');
    }
}