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
        Schema::create('product_promotions', function (Blueprint $table) {
            $table->id();
            // Relasi ke Produk
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // Relasi ke User (Mitra) - Opsional biar query cepat
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Detail Paket
            $table->enum('plan', ['bronze', 'silver', 'gold']);
            $table->integer('duration_days')->default(7);
            $table->integer('price_paid'); // Simpan harga saat transaksi terjadi
            
            // Bukti Bayar (Penting untuk Hybrid/Manual Check)
            $table->string('payment_proof')->nullable(); 
            
            // Status Request
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Catatan Admin (misal: "Foto buram, tolong ganti")
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_promotions');
    }
};
