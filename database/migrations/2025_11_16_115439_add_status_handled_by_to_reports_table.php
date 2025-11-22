<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'status')) {
                $table->string('status')->default('pending')->after('details'); // pending|accepted|rejected
            }
            if (! Schema::hasColumn('reports', 'handled_by')) {
                $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'handled_by')) {
                $table->dropConstrainedForeignId('handled_by');
            }
            if (Schema::hasColumn('reports', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
