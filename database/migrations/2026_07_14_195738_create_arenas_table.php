<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('arenas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complexo_id')->constrained('complexos')->onDelete('cascade');
            $table->string('nome');
            $table->string('tipo_esporte');
            $table->decimal('preco_hora', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('arenas');
    }
};