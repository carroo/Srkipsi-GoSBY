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
        Schema::create('tourism_hour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourism_id')->constrained('tourism')->onDelete('cascade');
            $table->string('day'); // Monday, Tuesday, etc or 'daily'
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_hour');
    }
};
