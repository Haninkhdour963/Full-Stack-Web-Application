<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['client_id', 'title', 'description', 'category_id', 'location', 'budget_min', 
                           'budget_max', 'duration', 'status'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function jobBids()
    {
        return $this->hasMany(JobBid::class, 'job_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'job_id');
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class, 'job_id');
    }

    // Define the technicians() method to access related technicians through job bids
    public function technicians()
    {
        return $this->hasManyThrough(Technician::class, JobBid::class, 'job_id', 'id', 'id', 'technician_id');
    }

    
}