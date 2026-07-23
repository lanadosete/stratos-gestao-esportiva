<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('precos');
        Schema::dropIfExists('grade_horarios');
        Schema::dropIfExists('arena_funcionamento');

        if (Schema::hasColumn('arenas', 'preco_hora')) {
            Schema::table('arenas', function (Blueprint $table) {
                $table->dropColumn('preco_hora');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arenas', function (Blueprint $table) {
            $table->decimal('preco_hora', 8, 2)->default(0);
        });

        Schema::create('arena_funcionamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arena_id')->constrained()->onDelete('cascade');
            $table->integer('dia_semana');
            $table->boolean('ativo')->default(true);
            $table->time('hora_abertura')->nullable();
            $table->time('hora_fechamento')->nullable();
            $table->timestamps();
        });

        Schema::create('grade_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arena_id')->constrained()->onDelete('cascade');
            $table->integer('dia_semana');
            $table->time('horario');
            $table->string('esporte')->nullable();
            $table->string('turno')->nullable();
            $table->decimal('preco', 8, 2)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('precos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arena_id')->constrained()->onDelete('cascade');
            $table->string('esporte');
            $table->string('turno');
            $table->decimal('valor', 8, 2);
            $table->timestamps();
        });
    }
};
