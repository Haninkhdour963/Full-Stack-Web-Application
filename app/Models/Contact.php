<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['job_id', 'technician_id', 'name', 'email', 'subject', 'message'];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }
}