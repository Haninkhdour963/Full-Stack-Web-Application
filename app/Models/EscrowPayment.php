<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscrowPayment extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['job_id', 'client_id', 'technician_id', 'amount_min', 'amount_max', 'status'];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }
}
