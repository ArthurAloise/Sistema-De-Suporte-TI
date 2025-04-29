<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'usuario_id',
        'tecnico_id',
        'category_id',
        'type_id',
        'descricao_resolucao'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function hasTechnician()
    {
        return !is_null($this->tecnico);
    }

    public function histories()
    {
        return $this->hasMany(TicketHistory::class);
    }
}
