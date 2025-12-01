<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToPostsTable extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Tambah kolom deleted_at (nullable timestamp)
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Hapus kolom deleted_at kalau di-rollback
            $table->dropSoftDeletes();
        });
    }
}
