<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama (opsional)
        // DB::table('pelanggan')->truncate();

        $pelanggan = [
            // ========== VIP CUSTOMERS (Total Belanja > 5 Juta) ==========
            [
                'nama_pelanggan' => 'Dr. Budi Santoso',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'no_telp' => '081234567890',
                'email' => 'budi.santoso@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(12),
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Siti Nurhaliza',
                'alamat' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan',
                'no_telp' => '081298765432',
                'email' => 'siti.nurhaliza@yahoo.com',
                'tanggal_daftar' => Carbon::now()->subMonths(10),
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Ahmad Wijaya, S.E.',
                'alamat' => 'Jl. Thamrin No. 88, Jakarta Pusat',
                'no_telp' => '081345678901',
                'email' => 'ahmad.wijaya@outlook.com',
                'tanggal_daftar' => Carbon::now()->subMonths(8),
                'created_at' => Carbon::now()->subMonths(8),
                'updated_at' => Carbon::now(),
            ],
            
            // ========== LOYAL CUSTOMERS (5+ Transaksi) ==========
            [
                'nama_pelanggan' => 'Rina Kusuma',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
                'no_telp' => '081456789012',
                'email' => 'rina.kusuma@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(6),
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Hendra Gunawan',
                'alamat' => 'Jl. Mangga Dua No. 56, Jakarta Utara',
                'no_telp' => '081567890123',
                'email' => null, // Pelanggan tanpa email
                'tanggal_daftar' => Carbon::now()->subMonths(5),
                'created_at' => Carbon::now()->subMonths(5),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Dewi Lestari',
                'alamat' => 'Jl. Cikini Raya No. 34, Jakarta Pusat',
                'no_telp' => '081678901234',
                'email' => 'dewi.lestari@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(4),
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Agus Prasetyo',
                'alamat' => 'Jl. Tebet Timur No. 78, Jakarta Selatan',
                'no_telp' => '081789012345',
                'email' => 'agus.prasetyo@yahoo.com',
                'tanggal_daftar' => Carbon::now()->subMonths(4),
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now(),
            ],
            
            // ========== REGULAR CUSTOMERS (Aktif) ==========
            [
                'nama_pelanggan' => 'Linda Wijayanti',
                'alamat' => 'Jl. Cideng Barat No. 22, Jakarta Pusat',
                'no_telp' => '081890123456',
                'email' => 'linda.wijayanti@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(3),
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Rudi Hartono',
                'alamat' => 'Jl. Kemang Raya No. 90, Jakarta Selatan',
                'no_telp' => '081901234567',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Sri Mulyani',
                'alamat' => 'Jl. Raden Saleh No. 15, Jakarta Pusat',
                'no_telp' => '082012345678',
                'email' => 'sri.mulyani@outlook.com',
                'tanggal_daftar' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Bambang Sugiarto',
                'alamat' => 'Jl. Pulo Gadung No. 44, Jakarta Timur',
                'no_telp' => '082123456789',
                'email' => 'bambang.sugiarto@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Putri Anggraini',
                'alamat' => 'Jl. Senopati No. 67, Jakarta Selatan',
                'no_telp' => '082234567890',
                'email' => 'putri.anggraini@yahoo.com',
                'tanggal_daftar' => Carbon::now()->subMonth(),
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now(),
            ],
            
            // ========== PELANGGAN BARU (1 Bulan Terakhir) ==========
            [
                'nama_pelanggan' => 'Eko Prasetyo',
                'alamat' => 'Jl. Kuningan Barat No. 23, Jakarta Selatan',
                'no_telp' => '082345678901',
                'email' => 'eko.prasetyo@gmail.com',
                'tanggal_daftar' => Carbon::now()->subDays(25),
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Maya Sari',
                'alamat' => 'Jl. Panjang No. 101, Jakarta Barat',
                'no_telp' => '082456789012',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Doni Setiawan',
                'alamat' => 'Jl. Rawamangun No. 55, Jakarta Timur',
                'no_telp' => '082567890123',
                'email' => 'doni.setiawan@outlook.com',
                'tanggal_daftar' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Fitri Handayani',
                'alamat' => 'Jl. Fatmawati No. 77, Jakarta Selatan',
                'no_telp' => '082678901234',
                'email' => 'fitri.handayani@gmail.com',
                'tanggal_daftar' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Yoga Aditya',
                'alamat' => 'Jl. Mampang Prapatan No. 33, Jakarta Selatan',
                'no_telp' => '082789012345',
                'email' => 'yoga.aditya@yahoo.com',
                'tanggal_daftar' => Carbon::now()->subDays(8),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Indah Permatasari',
                'alamat' => 'Jl. Cempaka Putih No. 19, Jakarta Pusat',
                'no_telp' => '082890123456',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Fajar Ramadhan',
                'alamat' => 'Jl. Kelapa Gading No. 88, Jakarta Utara',
                'no_telp' => '082901234567',
                'email' => 'fajar.ramadhan@gmail.com',
                'tanggal_daftar' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Nurul Hidayah',
                'alamat' => 'Jl. Salemba Raya No. 66, Jakarta Pusat',
                'no_telp' => '083012345678',
                'email' => 'nurul.hidayah@outlook.com',
                'tanggal_daftar' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now(),
            ],
            
            // ========== PELANGGAN TIDAK AKTIF (Sudah lama tidak transaksi) ==========
            [
                'nama_pelanggan' => 'Kartini Susilowati',
                'alamat' => 'Jl. Menteng No. 45, Jakarta Pusat',
                'no_telp' => '083123456789',
                'email' => 'kartini.susilowati@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(15),
                'created_at' => Carbon::now()->subMonths(15),
                'updated_at' => Carbon::now()->subMonths(3),
            ],
            [
                'nama_pelanggan' => 'Joko Widodo',
                'alamat' => 'Jl. Kemayoran No. 11, Jakarta Pusat',
                'no_telp' => '083234567890',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subMonths(14),
                'created_at' => Carbon::now()->subMonths(14),
                'updated_at' => Carbon::now()->subMonths(4),
            ],
            
            // ========== PELANGGAN BELUM PERNAH TRANSAKSI ==========
            [
                'nama_pelanggan' => 'Anita Kusuma',
                'alamat' => 'Jl. Tanah Abang No. 99, Jakarta Pusat',
                'no_telp' => '083345678901',
                'email' => 'anita.kusuma@gmail.com',
                'tanggal_daftar' => Carbon::now()->subDays(30),
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'nama_pelanggan' => 'Irfan Hakim',
                'alamat' => 'Jl. Senen Raya No. 22, Jakarta Pusat',
                'no_telp' => '083456789012',
                'email' => 'irfan.hakim@yahoo.com',
                'tanggal_daftar' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'nama_pelanggan' => 'Ratna Dewi',
                'alamat' => 'Jl. Pancoran No. 77, Jakarta Selatan',
                'no_telp' => '083567890123',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'nama_pelanggan' => 'Andi Wijaya',
                'alamat' => 'Jl. Cawang No. 44, Jakarta Timur',
                'no_telp' => '083678901234',
                'email' => 'andi.wijaya@gmail.com',
                'tanggal_daftar' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'nama_pelanggan' => 'Yuni Shara',
                'alamat' => 'Jl. Casablanca No. 55, Jakarta Selatan',
                'no_telp' => '083789012345',
                'email' => 'yuni.shara@outlook.com',
                'tanggal_daftar' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            
            // ========== TAMBAHAN PELANGGAN VARIATIF ==========
            [
                'nama_pelanggan' => 'Pak Haji Abdul Rahman',
                'alamat' => 'Jl. Matraman Raya No. 123, Jakarta Timur',
                'no_telp' => '083890123456',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subMonths(6),
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Ibu Mega Sari',
                'alamat' => 'Jl. Cipete Raya No. 88, Jakarta Selatan',
                'no_telp' => '083901234567',
                'email' => 'mega.sari@gmail.com',
                'tanggal_daftar' => Carbon::now()->subMonths(3),
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Rizki Febian',
                'alamat' => 'Jl. Pluit Raya No. 66, Jakarta Utara',
                'no_telp' => '084012345678',
                'email' => 'rizki.febian@yahoo.com',
                'tanggal_daftar' => Carbon::now()->subMonth(),
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Tika Dewi Nurhaliza',
                'alamat' => 'Jl. Lebak Bulus No. 99, Jakarta Selatan',
                'no_telp' => '084123456789',
                'email' => null,
                'tanggal_daftar' => Carbon::now()->subDays(25),
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pelanggan' => 'Arief Budiman',
                'alamat' => 'Jl. Jatinegara No. 33, Jakarta Timur',
                'no_telp' => '084234567890',
                'email' => 'arief.budiman@gmail.com',
                'tanggal_daftar' => Carbon::now()->subDays(18),
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data
        DB::table('pelanggan')->insert($pelanggan);

        $this->command->info('✅ Berhasil menambahkan ' . count($pelanggan) . ' data pelanggan');
        $this->command->info('📊 Breakdown:');
        $this->command->info('   - VIP Customers: 3 pelanggan');
        $this->command->info('   - Loyal Customers: 4 pelanggan');
        $this->command->info('   - Regular Customers (Aktif): 5 pelanggan');
        $this->command->info('   - Pelanggan Baru: 10 pelanggan');
        $this->command->info('   - Pelanggan Tidak Aktif: 2 pelanggan');
        $this->command->info('   - Belum Pernah Transaksi: 5 pelanggan');
        $this->command->info('   - Pelanggan Variatif: 5 pelanggan');
    }
}