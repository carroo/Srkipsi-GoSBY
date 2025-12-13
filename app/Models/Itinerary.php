<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $fillable = [
        'name',
        'travel_date',
        'start_time',
        'start_point_id',
        'start_point_lat',
        'start_point_long',
        'total_distance',
        'total_duration',
        'polyline_encode',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'start_time' => 'datetime:H:i',
        'total_distance' => 'integer',
        'total_duration' => 'integer',
    ];

    /**
     * Get the start point tourism
     */
    public function startPoint()
    {
        return $this->belongsTo(Tourism::class, 'start_point_id');
    }

    /**
     * Get the itinerary details (destinations)
     */
    public function details()
    {
        return $this->hasMany(ItineraryDetail::class)->orderBy('order');
    }

    /**
     * Get all tourism destinations for this itinerary
     */
    public function destinations()
    {
        return $this->hasManyThrough(
            Tourism::class,
            ItineraryDetail::class,
            'itinerary_id',
            'id',
            'id',
            'tourism_id'
        )->orderBy('itinerary_details.order');
    }
}
