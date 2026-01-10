<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_user_agent_and_ip_address_to_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            if (!Schema::hasColumn('sessions', 'ip_address')) {
                $table->string('ip_address', 45)->nullable();
            }
            
            // Cek dulu apakah kolom 'user_agent' belum ada
            if (!Schema::hasColumn('sessions', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Hapus kolom saat rollback
            $table->dropColumn('user_agent');
            $table->dropColumn('ip_address');
        });
    }
};
