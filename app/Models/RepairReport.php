<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'user_id',
        'description',
        'photo',
        'status',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
