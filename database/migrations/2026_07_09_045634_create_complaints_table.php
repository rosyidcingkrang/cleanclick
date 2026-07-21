<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
        $table->id('id_komplain');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->text('pesan');
        $table->string('bukti_foto')->nullable(); // Menyimpan nama file foto
        $table->enum('pengirim', ['user', 'admin']); // Membedakan siapa yang chat
        $table->enum('status', ['Diproses', 'Selesai'])->default('Diproses');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
