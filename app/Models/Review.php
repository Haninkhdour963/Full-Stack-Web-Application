<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Review extends Model
{
    use HasApiTokens,HasFactory,SoftDeletes;


    protected $fillable = ['job_id', 'reviewer_id', 'reviewee_id', 'rate', 'review_message'];
    

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }


    
}
