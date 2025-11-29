<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'user_id', // Penting! Untuk relasi ke guru penanggung jawab
    ];

    /**
     * Relasi: Ruangan dimiliki oleh satu user (guru penanggung jawab)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Ruangan memiliki banyak fasilitas
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}
