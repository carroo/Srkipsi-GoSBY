<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistanceCache extends Model
{
    use HasFactory;

    protected $table = 'distance_cache';

    protected $fillable = [
        'from_id',
        'to_id',
        'distance',
        'duration',
    ];

    protected $casts = [
        'distance' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * Get the origin tourism location.
     */
    public function fromTourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class, 'from_id');
    }

    /**
     * Get the destination tourism location.
     */
    public function toTourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class, 'to_id');
    }

    /**
     * Get distance in kilometers formatted.
     */
    public function getFormattedDistanceAttribute(): string
    {
        $km = $this->distance / 1000;
        return number_format($km, 2) . ' km';
    }

    /**
     * Get duration formatted (HH:MM).
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        
        if ($hours > 0) {
            return sprintf('%d jam %d menit', $hours, $minutes);
        }
        
        return sprintf('%d menit', $minutes);
    }
}

