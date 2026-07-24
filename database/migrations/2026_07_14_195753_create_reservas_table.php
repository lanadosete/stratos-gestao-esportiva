<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quadra_id')->constrained('quadras')->onDelete('cascade');
            $table->date('data_reserva');
            $table->string('horario');
            $table->decimal('valor_total', 8, 2);
            $table->string('status')->default('pendente');
            $table->string('metodo_pagamento');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservas');
    }
};