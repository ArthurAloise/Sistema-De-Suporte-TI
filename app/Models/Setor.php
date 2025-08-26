<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';
    protected $fillable = ['nome', 'sigla'];

    // Garante sigla sempre MAIÚSCULA e sem espaços extras
    public function setSiglaAttribute($value)
    {
        $this->attributes['sigla'] = mb_strtoupper(trim($value), 'UTF-8');
    }
}
