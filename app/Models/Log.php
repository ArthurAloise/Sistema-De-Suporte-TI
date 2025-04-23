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
        'model',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'
    ];
}
