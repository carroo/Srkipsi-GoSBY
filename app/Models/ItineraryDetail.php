<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryDetail extends Model
{
    protected $fillable = [
        'itinerary_id',
        'tourism_id',
        'lat',
        'long',
        'order',
        'arrival_time',
        'stay_duration',
        'distance_from_previous',
        'duration_from_previous',
    ];

    protected $casts = [
        'order' => 'integer',
        'stay_duration' => 'integer',
        'distance_from_previous' => 'integer',
        'duration_from_previous' => 'integer',
    ];

    /**
     * Get the itinerary this detail belongs to
     */
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    /**
     * Get the tourism destination
     */
    public function tourism()
    {
        return $this->belongsTo(Tourism::class);
    }
}
