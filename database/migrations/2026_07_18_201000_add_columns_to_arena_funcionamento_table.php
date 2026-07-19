<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arena_funcionamento', function (Blueprint $table) {
            if (!Schema::hasColumn('arena_funcionamento', 'hora_abertura')) {
                $table->time('hora_abertura')->nullable()->after('dia_semana');
            }

            if (!Schema::hasColumn('arena_funcionamento', 'hora_fechamento')) {
                $table->time('hora_fechamento')->nullable()->after('hora_abertura');
            }
        });
    }

    public function down(): void
    {
        Schema::table('arena_funcionamento', function (Blueprint $table) {
            if (Schema::hasColumn('arena_funcionamento', 'hora_abertura')) {
                $table->dropColumn('hora_abertura');
            }

            if (Schema::hasColumn('arena_funcionamento', 'hora_fechamento')) {
                $table->dropColumn('hora_fechamento');
            }
        });
    }
};
