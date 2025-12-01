<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facility';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the tourism facilities for this facility.
     */
    public function tourisms(): HasMany
    {
        return $this->hasMany(TourismFacility::class);
    }
}
