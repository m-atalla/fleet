<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSegment extends Model
{
    use HasFactory;

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function startStation()
    {
        return $this->belongsTo(Station::class);
    }

    public function endStation()
    {
        return $this->belongsTo(Station::class);
    }
}
