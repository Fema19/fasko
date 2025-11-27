<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'facility_id',
        'start_time',
        'end_time',
        'reason',
        'capacity_used',
        'status',
        'approved_by',
        'check_in_time',
        'checked_in',
    ];

    protected $dates = ['start_time', 'end_time', 'check_in_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function repairReports()
    {
        return $this->hasMany(RepairReport::class);
    }
}
