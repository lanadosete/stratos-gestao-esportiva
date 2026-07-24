<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quadra_preco_turnos')) {
            Schema::create('quadra_preco_turnos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quadra_id')->constrained('quadras')->onDelete('cascade');
                $table->string('esporte');
                $table->string('turno');
                $table->decimal('valor_hora', 8, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quadra_preco_turnos');
    }
};
