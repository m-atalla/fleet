<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
