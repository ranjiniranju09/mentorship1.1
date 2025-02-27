<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mentee extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'mentees';

    protected $dates = [
        'dob',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'dob',
        'skills',
        'interestedskills',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function menteenameSessions()
    {
        return $this->hasMany(Session::class, 'menteename_id', 'id');
    }

    public function menteenameMappings()
    {
        return $this->hasMany(Mapping::class, 'menteename_id', 'id');
    }

    
    public function invitedMenteesGuestLectures()
{
    return $this->belongsToMany(GuestLecture::class, 'guest_lecture_mentee');
}


    public function getDobAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function languagesspokens()
    {
        return $this->belongsToMany(Languagespoken::class);
    }
    public function guestLectures()
    {
        return $this->belongsToMany(GuestLecture::class, 'guest_lecture_mentee', 'mentee_id', 'guest_lecture_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'mentee_skill');
    }
    
}
