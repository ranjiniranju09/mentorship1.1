<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['name', 'date'];

    // Define a scope for past sessions
    public function scopePast($query)
    {
        return $query->where('date', '<', now());
    }

    // Define a scope for upcoming sessions
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now());
    }
}
