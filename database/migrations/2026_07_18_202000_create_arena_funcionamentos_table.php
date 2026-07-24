<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('complexo_funcionamentos')) {
            Schema::create('complexo_funcionamentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('complexo_id')->constrained('complexos')->onDelete('cascade');
                $table->integer('dia_semana');
                $table->time('hora_abertura')->nullable();
                $table->time('hora_fechamento')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('complexo_funcionamentos');
    }
};