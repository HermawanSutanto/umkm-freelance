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
        Schema::create('mitra_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('shop_name');
            $table->text('shop_description')->nullable();
            $table->text('shop_address')->nullable();
            $table->string('phone_number')->nullable(); // Contoh: 08123456789
            $table->string('operational_hours')->nullable(); // Contoh: "Senin - Jumat, 08:00 - 17:00"
            $table->string('gmaps_link')->nullable();
            $table->string('shop_image')->nullable();            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_profiles');
    }
};
