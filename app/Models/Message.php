<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mentor;
use App\Models\Mentee;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentorId', 'menteeId', 'message'
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'id');
    }

    public function mentee()
    {
        return $this->belongsTo(Mentee::class, 'id');
    }
}
