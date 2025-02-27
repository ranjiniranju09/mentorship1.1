<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestLecture extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'guest_lectures';

    protected $dates = [
        'guestsession_date_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const GUEST_SESSION_DURATION_RADIO = [
        '30'  => '30 mins',
        '60'  => '60 mins',
        '90'  => '90 mins',
        '120' => '120 mins',
    ];

    public const PLATFORM_SELECT = [
        'Zoom'            => 'Zoom',
        'Google Meet'     => 'Google Meet',
        'Microsoft Teams' => 'Microsoft Team',
        'Other'           => 'Other',
    ];

    protected $fillable = [
        'guessionsession_title',
        'speakername_id',
        'guestsession_date_time',
        'guest_session_duration',
        'platform',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function speaker()
    {
        return $this->belongsTo(Guestspeaker::class, 'speakername_id', 'id');
    }

    // In App\GuestLecture model
    public function speaker_name()
    {
        return $this->belongsTo(Guestspeaker::class, 'speaker_name_id');
    }

    public function invitedMentees()
    {
        return $this->belongsToMany(Mentee::class, 'guest_lecture_mentee', 'guest_lecture_id', 'mentee_id');
    }



    public function getGuestsessionDateTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setGuestsessionDateTimeAttribute($value)
    {
        $this->attributes['guestsession_date_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

}
