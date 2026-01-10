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
        //
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Kunci Asing ke tabel carts
            $table->foreignId('cart_id')
                  ->constrained()
                  ->onDelete('cascade'); // Jika keranjang dihapus, item ikut terhapus

            // Kunci Asing ke tabel products
            // Mengambil detail produk yang dimasukkan
            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('cascade'); // Jika produk dihapus, item keranjang yang merujuk juga dihapus

            // Atribut Item Keranjang
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('price_at_purchase', 10, 2); // Harga produk saat item ditambahkan (Penting!)

            $table->timestamps();

            // Tambahkan index unik agar satu produk hanya muncul sekali di satu keranjang
            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('cart_items');
    }
};
