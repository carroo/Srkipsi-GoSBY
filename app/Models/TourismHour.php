<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourismHour extends Model
{
    use HasFactory;

    protected $table = 'tourism_hour';

    protected $fillable = [
        'tourism_id',
        'day',
        'open_time',
        'close_time',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
    ];

    /**
     * Get the tourism for this tourism hour.
     */
    public function tourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class);
    }
}
