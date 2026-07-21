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
        // 1. MEMBUAT TABEL USERS (Gunakan Schema::create, BUKAN Schema::table)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
        $table->string('name');
        $table->string('username')->unique(); // ✓ Mencegah username kembar
        $table->string('email')->unique();    // ✓ Mencegah email kembar
        $table->string('password');           // ✓ Akan disimpan dalam bentuk Hash
        $table->string('whatsapp')->nullable();
        $table->timestamps();
            
            // Kolom kustom untuk CleanClick dimasukkan langsung ke sini:
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('whatsapp', 15)->nullable();
            $table->text('alamat')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. MEMBUAT TABEL PASSWORD RESET TOKENS (Bawaan Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. MEMBUAT TABEL SESSIONS (Bawaan Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};