<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quadra_esportes')) {
            Schema::create('quadra_esportes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quadra_id')->constrained('quadras')->onDelete('cascade');
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quadra_esportes');
    }
};
