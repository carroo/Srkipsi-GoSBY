<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismReview extends Model
{
    use HasFactory;

    protected $table = 'tourism_review';

    protected $fillable = [
        'tourism_id',
        'snippet',
    ];

    /**
     * Get the tourism that owns the review.
     */
    public function tourism()
    {
        return $this->belongsTo(Tourism::class, 'tourism_id');
    }
}
