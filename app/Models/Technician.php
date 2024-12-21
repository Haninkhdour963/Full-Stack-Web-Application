<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Technician extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'commercial_records', 'identity_number', 'skills', 'hourly_rate', 
        'certifications', 'bio', 'location', 'rating', 'available_from'
    ];

    // A technician belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A technician can have many job bids
    public function jobBids()
    {
        return $this->hasMany(JobBid::class, 'technician_id');
    }

    // A technician can have many job postings via job bids
    public function jobPostings()
    {
        return $this->hasManyThrough(JobPosting::class, JobBid::class, 'technician_id', 'id', 'id', 'job_id');
    }

    // A technician can have many escrow payments
    public function escrowPayments()
    {
        return $this->hasMany(EscrowPayment::class, 'technician_id');
    }

    // A technician can receive many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

   

   // Define scope to filter by hourly rate category
   public function scopeByHourlyRate($query, $category)
   {
       switch ($category) {
           case 'featured':
               return $query->where('hourly_rate', '>', 50);  // Example threshold for "featured"
           case 'full_time':
               return $query->whereBetween('hourly_rate', [30, 50]);  // Example range for "full_time"
           case 'part_time':
               return $query->where('hourly_rate', '<', 30);  // Example threshold for "part_time"
           default:
               return $query;
       }
   }

}
