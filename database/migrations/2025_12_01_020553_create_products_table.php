<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relasi: Produk milik User (Mitra)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Informasi Produk
            $table->string('name');
            $table->string('slug')->unique(); // Untuk URL SEO friendly (contoh: /produk/keripik-pisang-coklat)
            $table->text('description')->nullable();
            // Data Penjualan
            $table->integer('price'); // Gunakan integer untuk Rupiah (hindari desimal/float)
            $table->integer('stock')->default(0);
            // Media
            $table->string('cover_image')->nullable();
            $table->integer('highlight_level')->default(0); 
            $table->timestamp('highlight_expires_at')->nullable();            
            // Status
            $table->boolean('is_active')->default(true); // Agar mitra bisa menyembunyikan produk tanpa menghapus

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};