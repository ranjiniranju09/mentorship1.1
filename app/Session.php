<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'sessions';

    protected $dates = [
        'sessiondatetime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SESSION_DURATION_MINUTES_RADIO = [
        '30'  => '30 mins',
        '45'  => '45 mins',
        '60'  => '60 mins',
        '90'  => '90 mins',
        '120' => '120 mins',
    ];

    protected $fillable = [
        'modulename_id',
        'mentorname_id',
        'menteename_id',
        'sessiondatetime',
        'sessionlink',
        'session_title',
        'session_duration_minutes',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sessionTitleSessionRecordings()
    {
        return $this->hasMany(SessionRecording::class, 'session_title_id', 'id');
    }

    public function sessionAttendanceOneOneAttendances()
    {
        return $this->hasMany(OneOneAttendance::class, 'session_attendance_id', 'id');
    }

    public function sessionTitleGuestSessionAttendances()
    {
        return $this->hasMany(GuestSessionAttendance::class, 'session_title_id', 'id');
    }

    public function modulename()
    {
        return $this->belongsTo(Module::class, 'modulename_id');
    }

    public function mentorname()
    {
        return $this->belongsTo(Mentor::class, 'mentorname_id');
    }

    public function menteename()
    {
        return $this->belongsTo(Mentee::class, 'menteename_id');
    }

    public function getSessiondatetimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSessiondatetimeAttribute($value)
    {
        $this->attributes['sessiondatetime'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
