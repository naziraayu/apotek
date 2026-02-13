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
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_obat')->onDelete('restrict');
            $table->string('nama_obat', 150);
            $table->text('deskripsi')->nullable();
            $table->string('satuan', 50); // tablet, botol, box, dll
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('harga_beli', 15, 2);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(10);
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('no_batch', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
