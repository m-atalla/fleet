<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function tripSegment()
    {
        return $this->belongsTo(TripSegment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
