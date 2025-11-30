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
        Schema::create('tourism_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourism_id')->constrained('tourism')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facility')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_facility');
    }
};
