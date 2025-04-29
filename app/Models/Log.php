<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'route',
        'method',
        'controller',
        'action_name',
        'model',
        'record_id',
        'description',
        'request_data',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionBadgeAttribute()
    {
        return match($this->action) {
            'LOGIN' => 'bg-info',
            'LOGOUT' => 'bg-secondary',
            'CREATE' => 'bg-success',
            'UPDATE' => 'bg-warning',
            'DELETE' => 'bg-danger',
            default => 'bg-primary'
        };
    }

    public function hasDetailedInfo()
    {
        return $this->request_data || $this->old_values || $this->new_values || $this->user_agent;
    }
}
