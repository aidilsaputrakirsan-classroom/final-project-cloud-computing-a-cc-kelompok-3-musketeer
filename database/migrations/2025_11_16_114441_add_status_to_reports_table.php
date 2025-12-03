<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'status')) {
                $table->string('status')->default('pending')->after('details');
            }
            if (! Schema::hasColumn('reports', 'handled_by')) {
                $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            }
        });
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if (Schema::hasColumn('reports', 'handled_by')) {
            // Drop foreign key constraint first
            if ($driver === 'mysql' || $driver === 'mariadb') {
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'reports' 
                    AND COLUMN_NAME = 'handled_by' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                foreach ($foreignKeys as $foreignKey) {
                    Schema::table('reports', function (Blueprint $table) use ($foreignKey) {
                        $table->dropForeign($foreignKey->CONSTRAINT_NAME);
                    });
                }
            } else {
                // Untuk SQLite, coba drop dengan nama default
                try {
                    Schema::table('reports', function (Blueprint $table) {
                        $table->dropForeign(['handled_by']);
                    });
                } catch (\Exception $e) {
                    // Ignore jika foreign key tidak ada
                }
            }
            
            Schema::table('reports', function (Blueprint $table) {
                $table->dropColumn('handled_by');
            });
        }
        
        if (Schema::hasColumn('reports', 'status')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
