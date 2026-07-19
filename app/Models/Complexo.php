<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complexo extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'endereco', 'telefone'];

    // Um Complexo tem muitas Arenas (quadras)
    public function arenas()
    {
        return $this->hasMany(Arena::class);
    }

    public function funcionamento()
    {
        return $this->hasMany(ComplexoFuncionamento::class);
    }
}