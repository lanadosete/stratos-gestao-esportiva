<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('grade_horarios')) {
            Schema::create('grade_horarios', function (Blueprint $table) {
                $table->id();

                // 1. Relacionamento: De qual quadra (arena) é este horário?
                $table->foreignId('arena_id')->constrained('arenas')->onDelete('cascade');

                // 2. Dia da semana: Vamos usar números (0 = Domingo, 1 = Segunda, 2 = Terça...)
                $table->integer('dia_semana');

                // 3. O Horário em si (Ex: 18:00)
                $table->time('horario');

                // 4. O Esporte dinâmico (Futevôlei, Vôlei ou Beach Tennis)
                $table->string('esporte');

                // 5. O Preço dinâmico daquele horário específico
                $table->decimal('preco', 8, 2);

                // 6. Botão de ligar/desligar: O dono pode pausar um horário sem precisar deletar
                $table->boolean('ativo')->default(true);

                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('grade_horarios');
    }
};
