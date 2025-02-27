<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'chapters';

    public const PUBLISHED_SELECT = [
        'Yes' => 'Yes',
        'No'  => 'No',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'chaptername',
        'module_id',
        'description',
        'published',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function chapterChapterTests()
    {
        return $this->hasMany(ChapterTest::class, 'chapter_id', 'id');
    }

    public function chapterSubchapters()
    {
        return $this->hasMany(Subchapter::class, 'chapter_id', 'id');
    }

    public function lessonCreateProgressTables()
    {
        return $this->hasMany(CreateProgressTable::class, 'lesson_id', 'id');
    }
    public function tests()
    {
        return $this->hasMany(Test::class, 'chapter_id');
    }
    

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
    public function chapteridModuleresourcebanks()
    {
        return $this->hasMany(Moduleresourcebank::class, 'chapterid_id', 'id');
    }
    public function subchapters()
{
    return $this->hasMany(Subchapter::class, 'chapter_id');
}

}
