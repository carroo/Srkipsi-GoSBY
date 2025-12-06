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
        Schema::create('tourism_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourism_id')->constrained('tourism')->onDelete('cascade');
            $table->text('snippet');
            $table->timestamps();
            
            $table->index('tourism_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_review');
    }
};
