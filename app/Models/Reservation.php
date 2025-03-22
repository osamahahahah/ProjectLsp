<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id', 'check_in', 'check_out',
        'qty_person','phone_number', 'total_price', 'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function setPhoneNumberAttribute($value)
    {
        if (str_starts_with($value, '08')) {
            $value = '628' . substr($value, 1);
        }
        $this->attributes['phone_number'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function detailReservations()
    {
        return $this->hasMany(DetailReservation::class);
    }


}
