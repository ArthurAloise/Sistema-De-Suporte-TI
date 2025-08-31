<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nome','default_priority','sla_hours'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
