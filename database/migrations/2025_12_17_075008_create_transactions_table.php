<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembeli
            
            // Info Dasar
            $table->string('invoice_code')->unique(); // Contoh: INV/20231217/001
            
            // Info Keuangan
            $table->decimal('total_price', 12, 0); // Total harga barang
            $table->decimal('shipping_price', 12, 0)->default(0); // Ongkir
            $table->decimal('grand_total', 12, 0); // Total + Ongkir
            
            // Info Pembayaran Manual
            $table->string('payment_method')->default('manual_transfer'); // manual_transfer / cod
            $table->string('payment_proof')->nullable(); // Path gambar bukti transfer (disimpan di storage)
            
            // Info Pengiriman
            $table->text('shipping_address'); // Alamat lengkap
            $table->string('courier')->nullable(); // JNE, J&T, dll
            $table->string('service')->nullable(); // REG, YES, dll
            $table->string('resi_number')->nullable(); // Diisi penjual saat dikirim
            
            // Status Pesanan (Manual Flow)
            // pending_payment: Baru checkout, belum upload bukti
            // waiting_confirmation: Sudah upload bukti, menunggu dicek Admin/Penjual
            // processed: Bukti valid, sedang dikemas
            // shipped: Sedang dikirim (ada resi)
            // completed: Selesai
            // cancelled: Dibatalkan/Bukti tidak valid
            $table->string('status')->default('pending_payment'); 

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};