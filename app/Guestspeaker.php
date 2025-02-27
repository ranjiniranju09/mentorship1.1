<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guestspeaker extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'guestspeakers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'speakername',  // Ensure this column exists in the database
        'speakermobile',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // Define the relationship to GuestLecture model
    public function speakerNameGuestLectures()
    {
        return $this->hasMany(GuestLecture::class, 'speakername_id', 'id');
    }
    public function guestLectures()
    {
        return $this->hasMany(GuestLecture::class);
    }
}
