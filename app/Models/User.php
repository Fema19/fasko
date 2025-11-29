<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function approvedBookings()
    {
        return $this->hasMany(Booking::class, 'approved_by');
    }

    public function repairReports()
    {
        return $this->hasMany(RepairReport::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    public function room()
{
    return $this->belongsTo(Room::class, 'room_id');
}

}
