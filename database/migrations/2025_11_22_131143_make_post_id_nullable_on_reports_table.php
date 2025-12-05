<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql' || $driver === 'mariadb') {
            // Untuk MySQL/MariaDB: Hapus foreign key constraint jika ada
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'reports' 
                AND COLUMN_NAME = 'post_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            foreach ($foreignKeys as $foreignKey) {
                Schema::table('reports', function (Blueprint $table) use ($foreignKey) {
                    $table->dropForeign($foreignKey->CONSTRAINT_NAME);
                });
            }
        } else {
            // Untuk SQLite: Coba drop foreign key dengan nama default
            // SQLite tidak support drop foreign key secara langsung, jadi kita skip
            // atau coba dengan nama constraint default
            try {
                Schema::table('reports', function (Blueprint $table) {
                    $table->dropForeign(['post_id']);
                });
            } catch (\Exception $e) {
                // Ignore jika foreign key tidak ada
            }
        }

        // Jadikan nullable
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Balik lagi ke NOT NULL (kalau perlu)
            // HATI-HATI: data yang sudah null bisa bikin error saat rollback
            $table->unsignedBigInteger('post_id')->nullable(false)->change();
        });
    }
};
