<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorMenteeMap extends Model
{
    protected $table = 'mentor_mentee_map';

    protected $fillable = ['mentor_id', 'mentee_id']; // Add other fields as needed

    // Define relationship with Mentee
    public function mentee()
    {
        return $this->belongsTo(Mentee::class, 'mentee_id');
    }

    // Define relationship with Mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }
}
