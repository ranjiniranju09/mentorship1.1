<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Languagespoken extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'languagespokens';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'langname',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function langspokenMentors()
    {
        return $this->belongsToMany(Mentor::class);
    }

    public function languagesspokenMentees()
    {
        return $this->belongsToMany(Mentee::class);
    }
}
