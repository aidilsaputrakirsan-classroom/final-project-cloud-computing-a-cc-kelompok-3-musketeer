<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pelapor
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // postingan yang dilaporkan
            $table->string('reason'); // alasan laporan
            $table->text('details')->nullable(); // detail tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
