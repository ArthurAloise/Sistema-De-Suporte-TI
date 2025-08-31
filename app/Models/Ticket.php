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
        'pendencia',
        'descricao_resolucao',
        'status',
        'prioridade',
        'usuario_id',
        'tecnico_id',
        'category_id',
        'type_id',
        'due_at',
    ];

    // Opcional: atalho de leitura
    protected $casts = [
        'due_at' => 'datetime',
    ];

    public function getIsOverdueAttribute(): bool
    {
        // Só considera SLA em aberto/andamento (pausa se pendente, fechado ou resolvido)
        if (!in_array($this->status, ['aberto','andamento'])) return false;
        if (!$this->due_at) return false;

        return now()->greaterThan($this->due_at);
    }

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
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
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
