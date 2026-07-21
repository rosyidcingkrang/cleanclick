<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('nama_layanan', 50);
            $table->string('kategori', 20); // kiloan_reguler, kiloan_ekspres, satuan
            $table->integer('harga_satuan');
            $table->string('satuan', 10); // kg, pcs
            $table->integer('estimasi_hari')->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};