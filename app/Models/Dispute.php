<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispute extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['job_id', 'initiator_id', 'technician_id', 'client_id', 'dispute_reason', 'status'];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}