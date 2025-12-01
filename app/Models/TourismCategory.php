<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourismCategory extends Model
{
    use HasFactory;

    protected $table = 'tourism_category';

    protected $fillable = [
        'tourism_id',
        'category_id',
    ];

    /**
     * Get the tourism for this tourism category.
     */
    public function tourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class);
    }

    /**
     * Get the category for this tourism category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
