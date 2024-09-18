<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSegment extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_main' => 'boolean',
        ];
    }

    /**
     * Get all the bookings for the trip segment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the trip that owns the segment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the start station for the trip segment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function startStation()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get the end station for the trip segment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function endStation()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Scope a query to only include the main segment.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}
