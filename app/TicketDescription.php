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

class TicketDescription extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'ticket_descriptions';

    protected $appends = [
        'attachment_url',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ticket_category_id',
        'ticket_description',
        'Response',
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

    public function ticketDescriptionTicketResponses()
    {
        return $this->hasMany(TicketResponse::class, 'ticket_description_id', 'id');
    }

    public function ticket_category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function getAttachmentUrlAttribute()
    {
        $media = $this->getMedia('attachment_url')->first();
        return $media ? $media->getUrl() : null;
    }


    // public function getSupportingPhotoAttribute()
    // {
    //     return $this->getMedia('supporting_photo')->map(function ($media) {
    //         return [
    //             'url'       => $media->getUrl(),
    //             'thumbnail' => $media->getUrl('thumb'),
    //             'preview'   => $media->getUrl('preview'),
    //         ];
    //     });
    // }
    
}
