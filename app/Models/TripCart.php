<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCart extends Model
{
    use HasFactory;

    protected $table = 'trip_cart';

    protected $fillable = [
        'user_id',
        'tourism_id',
    ];

    /**
     * Get the user that owns the trip cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tourism destination for this trip cart item.
     */
    public function tourism()
    {
        return $this->belongsTo(Tourism::class);
    }
}
