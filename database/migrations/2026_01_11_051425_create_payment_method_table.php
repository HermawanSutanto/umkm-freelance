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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->id();
            $table->string('name');              // Contoh: "Bank BCA", "GoPay", "QRIS"
            $table->string('account_number')->nullable(); // No. Rekening / No. HP
            $table->string('account_holder')->nullable(); // Atas Nama (misal: PT Lokalitas Market)
            $table->string('type')->nullable(); 

            // Visual & Instruksi
            $table->string('logo')->nullable();  // URL/Path gambar logo bank
            $table->text('instructions')->nullable(); // Langkah-langkah pembayaran (bisa HTML atau text)
            
            // Biaya & Status
            $table->decimal('admin_fee', 10, 2)->default(0); // Biaya admin tambahan (opsional)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
