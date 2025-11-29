<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'category_id',
        'room_id',      // ← WAJIB ADA
        'name',
        'location',
        'condition',
        'description',
        'photo',
        'capacity',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function repairReports()
    {
        return $this->hasMany(RepairReport::class);
    }
}
