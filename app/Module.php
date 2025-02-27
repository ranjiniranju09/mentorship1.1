<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'modules';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function modulenameSessions()
    {
        return $this->hasMany(Session::class, 'modulename_id', 'id');
    }
    public function moduleChapters()
    {
        return $this->hasMany(Chapter::class, 'module_id', 'id');
    }

    public function moduleidChapterTests()
    {
        return $this->hasMany(ChapterTest::class, 'moduleid_id', 'id');
    }
    public function moduleModuleresourcebanks()
    {
        return $this->hasMany(Moduleresourcebank::class, 'module_id', 'id');
    }
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
    

}
