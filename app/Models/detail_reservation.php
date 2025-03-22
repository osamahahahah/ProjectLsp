<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_reservation extends Model
{
    public function reservation()
    {
        return $this->belongsTo(reservation::class);
    }

    public function room()
    {
        return $this->belongsTo(room::class);
    }

}
