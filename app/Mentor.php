<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mentor extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'mentors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'companyname',
        'skills',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function mentornameSessions()
    {
        return $this->hasMany(Session::class, 'mentorname_id', 'id');
    }

    public function mentornameMappings()
    {
        return $this->hasMany(Mapping::class, 'mentorname_id', 'id');
    }

    public function langspokens()
    {
        return $this->belongsToMany(Languagespoken::class);
    }
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
