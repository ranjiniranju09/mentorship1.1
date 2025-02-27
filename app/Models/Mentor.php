<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = ['name', 'email', 'mobile']; // Add other fields as needed

    // Define relationship with MentorMenteeMap
    public function mentorMap()
    {
        return $this->hasOne(MentorMenteeMap::class, 'mentor_id');
    }
}
