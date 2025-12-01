<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourismPrice extends Model
{
    use HasFactory;

    protected $table = 'tourism_price';

    protected $fillable = [
        'tourism_id',
        'type',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the tourism for this tourism price.
     */
    public function tourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class);
    }
}
