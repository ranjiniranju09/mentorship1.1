<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SessionRecording extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'session_recordings';

    protected $appends = [
        'session_video_file',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SESSION_TYPE_SELECT = [
        'session'      => 'session',
        'guestlecture' => 'guestlecture',
    ];

    protected $fillable = [
        'session_type',
        'session_title_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    // public function session_title()
    // {
    //     return $this->belongsTo(Session::class, 'session_title_id');
    // }
    // In SessionRecording model
public function session_title()
{
    return $this->belongsTo(Session::class, 'session_id');  // Adjust the foreign key as per your database schema
}

    

    public function getSessionVideoFileAttribute()
    {
        return $this->getMedia('session_video_file');
    }
}
