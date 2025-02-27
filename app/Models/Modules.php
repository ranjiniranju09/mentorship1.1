<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chapters; // Import the Chapter model

class Modules extends Model
{
    protected $fillable = ['title'];

    public function chapters()
    {
        return $this->hasMany(Chapters::class); // Corrected the relationship to Chapter
    }
}
