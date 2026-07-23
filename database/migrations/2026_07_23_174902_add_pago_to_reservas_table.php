<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->boolean('pago')->default(false)->after('status');
        });

        // Antes, "status = finalizado" indicava tanto o jogo ter acabado quanto o
        // pagamento ter sido recebido. Agora pagamento é um campo independente do
        // status (que passa a ser calculado por tempo: a_iniciar/em_jogo/finalizado).
        DB::table('reservas')
            ->where('status', 'finalizado')
            ->orWhere('metodo_pagamento', 'pix')
            ->update(['pago' => true]);

        DB::table('reservas')
            ->where('status', 'finalizado')
            ->update(['status' => 'confirmado']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('pago');
        });
    }
};
