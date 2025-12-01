<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tourism extends Model
{
    use HasFactory;

    protected $table = 'tourism';

    protected $fillable = [
        'name',
        'description',
        'location',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'rating',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
    ];

    /**
     * Get the tourism prices for this tourism.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(TourismPrice::class);
    }

    /**
     * Get the tourism files for this tourism.
     */
    public function files(): HasMany
    {
        return $this->hasMany(TourismFile::class);
    }

    /**
     * Get the tourism hours for this tourism.
     */
    public function hours(): HasMany
    {
        return $this->hasMany(TourismHour::class);
    }

    /**
     * Get the categories for this tourism through tourism_category pivot table.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'tourism_category');
    }

    /**
     * Get the facilities for this tourism through tourism_facility pivot table.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'tourism_facility');
    }

    /**
     * Get the trip cart items for this tourism.
     */
    public function tripCart(): HasMany
    {
        return $this->hasMany(TripCart::class);
    }
}
