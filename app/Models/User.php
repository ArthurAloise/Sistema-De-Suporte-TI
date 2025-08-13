<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // evita precisar passar 'web' em cada chamada de permissão/role
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'role_id', // ok manter se existir no BD; não é usado pelo Spatie
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // suas relações de tickets permanecem
    public function ticketsCriados()
    {
        return $this->hasMany(Ticket::class, 'usuario_id');
    }

    public function ticketsAtribuidos()
    {
        return $this->hasMany(Ticket::class, 'tecnico_id');
    }
}
