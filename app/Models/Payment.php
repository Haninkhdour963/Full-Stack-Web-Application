<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['job_id', 'client_id', 'technician_id', 'amount', 'payment_method', 'payment_status', 'transaction_id'];

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