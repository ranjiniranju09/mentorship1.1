<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'admin_id', 'is_approved', 'comments'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class,'id'); // assuming users table for admins
    }
}
