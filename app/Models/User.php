<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
        'profile_image', 
        'phone_number', 
        'mobile_phone', 
        'location',
        'email_verified_at'
    ];

    protected $dates = ['deleted_at']; // Specifies the 'deleted_at' column
 // A user can have many job postings (clients)
 public function jobPostings()
 {
     return $this->hasMany(JobPosting::class, 'client_id');
 }

 // A user can have many job bids (technician or client)
 public function jobBids()
 {
     return $this->hasMany(JobBid::class, 'technician_id');
 }

 // A user can have many reviews
 public function reviews()
 {
     return $this->hasMany(Review::class, 'reviewer_id');
 }

 // A user can have many disputes
 public function disputes()
 {
     return $this->hasMany(Dispute::class, 'initiator_id');
 }

 // A user can be related to many admin actions
 public function adminActions()
 {
     return $this->hasMany(AdminAction::class, 'target_user_id');
 }

 // Define relationship with payments (for clients)
 public function payments()
 {
     return $this->hasMany(Payment::class, 'client_id');
 }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
     // Method to check roles
     public function isAdmin()
     {
         return $this->user_role === 'admin';
     }
 
     public function isClient()
     {
         return $this->user_role === 'client';
     }
 
     public function isTechnician()
     {
         return $this->user_role === 'technician';
     }


}
