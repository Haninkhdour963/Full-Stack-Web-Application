<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminAction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['admin_id', 'action_type', 'description', 'target_user_id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}