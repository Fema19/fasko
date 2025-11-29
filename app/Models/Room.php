<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    // Setiap ruangan punya banyak fasilitas
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
{
    return $this->hasMany(User::class, 'room_id');
}

}
