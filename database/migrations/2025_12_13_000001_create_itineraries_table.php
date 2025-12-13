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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('travel_date');
            $table->time('start_time');
            
            // Start point: bisa dari tourism atau manual
            $table->foreignId('start_point_id')
                  ->nullable()
                  ->constrained('tourism')
                  ->nullOnDelete();
            
            // Jika start point manual (tidak dari tourism)
            $table->decimal('start_point_lat', 10, 8)->nullable();
            $table->decimal('start_point_long', 11, 8)->nullable();
            
            // Route information
            $table->integer('total_distance')->default(0); // dalam meter
            $table->integer('total_duration')->default(0); // dalam detik
            $table->longText('polyline_encode')->nullable(); // Encoded polyline dari routing
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
