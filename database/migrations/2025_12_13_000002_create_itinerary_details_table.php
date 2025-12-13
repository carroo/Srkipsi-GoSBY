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
        Schema::create('itinerary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')
                  ->constrained('itineraries')
                  ->cascadeOnDelete();
            
            $table->foreignId('tourism_id')
                  ->constrained('tourism')
                  ->cascadeOnDelete();
            
            // Urutan kunjungan (0, 1, 2, ...)
            $table->integer('order');
            
            // Waktu kedatangan di lokasi
            $table->time('arrival_time')->nullable();
            
            // Durasi tinggal di lokasi (dalam menit)
            $table->integer('stay_duration')->default(0);
            
            // Jarak dari destinasi sebelumnya (dalam meter)
            $table->integer('distance_from_previous')->default(0);
            
            // Durasi perjalanan dari destinasi sebelumnya (dalam detik)
            $table->integer('duration_from_previous')->default(0);
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('itinerary_id');
            $table->index('tourism_id');
            $table->unique(['itinerary_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_details');
    }
};
