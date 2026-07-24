<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('arena_preco_turnos')) {
            Schema::create('arena_preco_turnos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('arena_id')->constrained('arenas')->onDelete('cascade');
                $table->string('esporte');
                $table->string('turno');
                $table->decimal('valor_hora', 8, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('arena_preco_turnos');
    }
};
