<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Kalau sebelumnya ada foreign key ke posts, hapus dulu
            // Sesuaikan nama constraint kalau berbeda
            try {
                $table->dropForeign(['post_id']);
            } catch (\Throwable $e) {
                // abaikan kalau FK tidak ada / sqlite
            }

            // Jadikan nullable
            $table->unsignedBigInteger('post_id')->nullable()->change();

            // Optional: kalau mau tetap ada FK tapi tidak menghapus report,
            // bisa pakai SET NULL saat post dihapus:
            // $table->foreign('post_id')
            //       ->references('id')->on('posts')
            //       ->nullOnDelete(); // SET NULL, bukan CASCADE
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
