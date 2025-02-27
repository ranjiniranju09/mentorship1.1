<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'opportunities';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'link',
        'opportunity_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const OPPORTUNITY_TYPE_SELECT = [
        'Jobs'        => 'Jobs',
        'Internship' => 'Internship',
        'Fellowship' => 'Fellowship',
        'Competition'=>'Competition',
        'Others'     =>'Others',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
