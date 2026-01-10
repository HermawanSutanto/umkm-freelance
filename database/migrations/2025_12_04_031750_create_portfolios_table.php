<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kunci asing ke User
            
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL yang ramah SEO
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            
            $table->string('category')->nullable();

            $table->string('project_url')->nullable();
            

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};