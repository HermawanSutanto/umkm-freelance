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
        Schema::create('freelancer_profiles', function (Blueprint $table) {
            $table->id();   
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('skills');
            $table->string('phone_number')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('portfolio_link')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('cv_file')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_profiles');
    }
};
