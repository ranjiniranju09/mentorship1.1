<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mentee;
use App\Mentor;

class Mapping extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'mappings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'mentorname_id',
        'menteename_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    
    public function mentorname()
    {
        return $this->belongsTo(Mentor::class, 'mentorname_id');
    }

    public function menteename()
    {
        return $this->belongsTo(Mentee::class, 'menteename_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentorname_id');
    }

    public function mentee()
    {
        return $this->belongsTo(Mentee::class, 'menteename_id');
    }
}
