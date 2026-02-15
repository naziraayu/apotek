<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota', 50)->unique();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // kasir
            $table->date('tanggal_penjualan');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->enum('status_pembayaran', ['lunas', 'belum_lunas'])->default('lunas');
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'kartu_kredit', 'e-wallet'])->default('tunai');
            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
