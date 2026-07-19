<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('arena_funcionamento')) {
            Schema::create('arena_funcionamento', function (Blueprint $table) {
                $table->id();
                $table->foreignId('arena_id')->constrained()->onDelete('cascade');
                $table->integer('dia_semana'); // 0=Dom, 1=Seg...
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('grade_horarios')) {
            Schema::create('grade_horarios', function (Blueprint $table) {
                $table->id();
                $table->foreignId('arena_id')->constrained()->onDelete('cascade');
                $table->integer('dia_semana');
                $table->time('horario');
                $table->string('turno'); // Ex: 'Manhã', 'Tarde', 'Nobre'
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('precos')) {
            Schema::create('precos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('arena_id')->constrained()->onDelete('cascade');
                $table->string('esporte'); // Futevôlei, Beach Tennis...
                $table->string('turno');   // Deve bater com o turno da grade
                $table->decimal('valor', 8, 2);
                $table->timestamps();
            });
        }
    }

    public function down() {
        Schema::dropIfExists('precos');
        Schema::dropIfExists('grade_horarios');
        Schema::dropIfExists('arena_funcionamento');
    }
};