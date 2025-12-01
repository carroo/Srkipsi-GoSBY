<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the tourism categories for this category.
     */
    public function tourisms(): HasMany
    {
        return $this->hasMany(TourismCategory::class);
    }
}
