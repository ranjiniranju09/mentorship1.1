<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'resources';

    protected $fillable = [
        'title', 'description', 'file_path', 'type', 'module_id', 'added_by', 'is_approved'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function approvals()
    {
        return $this->hasMany(ResourceApproval::class);
    }
      // Scope to filter resources by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope to filter resources by status
    public function scopeOfStatus($query, $is_approved)
    {
        return $query->where('is_approved', $is_approved);
    }
}
