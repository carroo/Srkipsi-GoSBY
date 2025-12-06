<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('distance_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_id')->constrained('tourism')->onDelete('cascade');
            $table->foreignId('to_id')->constrained('tourism')->onDelete('cascade');
            $table->integer('distance')->comment('Distance in meters');
            $table->integer('duration')->comment('Duration in seconds');
            $table->timestamps();

            // Create unique index to prevent duplicate cache entries
            $table->unique(['from_id', 'to_id']);
            
            // Add index for reverse lookups
            $table->index(['to_id', 'from_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distance_cache');
    }
};
