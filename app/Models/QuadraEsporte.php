<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuadraEsporte extends Model
{
    protected $fillable = [
        'quadra_id',
        'nome',
        'ativo',
    ];

    public function quadra()
    {
        return $this->belongsTo(Quadra::class);
    }
}
