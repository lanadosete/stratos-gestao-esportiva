<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arena extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'endereco', 'telefone'];

    // Uma Arena tem muitas Quadras
    public function quadras()
    {
        return $this->hasMany(Quadra::class);
    }

    public function funcionamento()
    {
        return $this->hasMany(ArenaFuncionamento::class);
    }

    // Uma Arena possui muitos usuários vinculados (admins e funcionários)
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}