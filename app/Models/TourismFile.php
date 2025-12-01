<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourismFile extends Model
{
    use HasFactory;

    protected $table = 'tourism_file';

    protected $fillable = [
        'tourism_id',
        'file_path',
        'file_type',
        'original_name',
    ];

    /**
     * Get the tourism for this tourism file.
     */
    public function tourism(): BelongsTo
    {
        return $this->belongsTo(Tourism::class);
    }
}
