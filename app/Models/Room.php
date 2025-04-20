<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    protected $fillable = ['room_number', 'facilities', 'room_type', 'price', 'status'];

    protected $casts = [
        'room_type' => 'array',
        'image' => 'array',
        'status' => 'array',
    ];

    public function reservations()
    {
        return $this->hasMany(reservation::class);
    }

    public function detail_reservations()
    {
        return $this->hasMany(detail_reservation::class);
    }
}
