<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'link',
        'visibility',
        'is_approved',
        'mentor_id',
    ];

    // Define relationship if needed
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
    
}

