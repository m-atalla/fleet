<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ["name"];


    public function startSegments()
    {
        return $this->hasMany(TripSegment::class, "end_station_id");
    }

    public function endSegments()
    {
        return $this->hasMany(TripSegment::class, "start_station_id");
    }
}
