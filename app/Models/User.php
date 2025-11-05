<?php

namespace App\Models;

// 1. IMPORTAR A INTERFACE DE VERIFICAÇÃO DE E-MAIL
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// 2. IMPLEMENTAR A INTERFACE NA CLASSE PARA "LIGAR" A FUNCIONALIDADE
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    // ... SEU CÓDIGO 'fillable', 'hidden', 'casts' CONTINUA IGUAL ...
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'role_id',
        'setor_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists() ||
            $this->role->permissions()->where('name', $permissionName)->exists();
    }
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    // Adição de relação dos tickets para com os usuários e técnicos
    public function ticketsCriados()
    {
        return $this->hasMany(Ticket::class, 'usuario_id');
    }

    public function ticketsAtribuidos()
    {
        return $this->hasMany(Ticket::class, 'tecnico_id');
    }

    public function setor()
    {
        return $this->belongsTo(\App\Models\Setor::class);
    }
}
