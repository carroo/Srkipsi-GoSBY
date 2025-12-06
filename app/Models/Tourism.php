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
        'popularity',
        'is_ready',
        'external_id',
        'external_source',
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
     * Get the trip cart items for this tourism.
     */
    public function tripCart(): HasMany
    {
        return $this->hasMany(TripCart::class);
    }

    /**
     * Get the reviews for this tourism.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(TourismReview::class);
    }

    /**
     * Check if this tourism is in the trip cart for a specific user.
     *
     * @param int|null $userId
     * @return bool
     */
    public function isInTripCart($userId = null): bool
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return false;
        }

        return $this->tripCart()->where('user_id', $userId)->exists();
    }

    /**
     * Get the is_in_trip_cart attribute (for current authenticated user).
     *
     * @return bool
     */
    public function getIsInTripCartAttribute(): bool
    {
        return $this->isInTripCart();
    }
}
