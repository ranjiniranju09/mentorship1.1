<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Chapters extends Model
{
    protected $fillable = ['title', 'description', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Modules::class);
    }
}
