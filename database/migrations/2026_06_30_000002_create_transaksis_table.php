<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            // Relasi ke tabel users (pelanggan)
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            // Relasi ke tabel layanan
            $table->foreignId('id_layanan')->constrained('layanan', 'id_layanan')->onDelete('restrict');
            
            $table->date('tanggal');
            $table->string('no_nota', 20)->unique();
            $table->decimal('quantity', 10, 2);
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->enum('status_pembayaran', ['Lunas', 'Bayar di Akhir']);
            $table->enum('metode_pembayaran', ['Tunai', 'QRIS', 'Transfer'])->nullable();
            $table->enum('status_cucian', ['Antrean', 'Diproses/Dicuci', 'Disetrika', 'Selesai & Siap Diambil', 'Sudah Diambil'])->default('Antrean');
            $table->date('estimasi_selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};