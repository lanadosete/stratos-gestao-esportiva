<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('arena_esportes')) {
            Schema::create('arena_esportes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('arena_id')->constrained('arenas')->onDelete('cascade');
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('arena_esportes');
    }
};
