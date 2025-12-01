<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourismFacility extends Model
{
    use HasFactory;

    protected $table = 'tourism_facility';

    protected $fillable = [
        'tourism_id',
        'facility_id',
    ];

    /**
     * Get the tourism for this tourism facility.
     */
    public function tourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class);
    }

    /**
     * Get the facility for this tourism facility.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
