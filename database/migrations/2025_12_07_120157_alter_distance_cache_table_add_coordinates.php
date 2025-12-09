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
        Schema::table('distance_cache', function (Blueprint $table) {
            // Make from_id and to_id nullable
            $table->foreignId('from_id')->nullable()->change();
            $table->foreignId('to_id')->nullable()->change();
            
            // Add coordinate columns
            $table->decimal('from_lat', 10, 8)->nullable()->after('from_id');
            $table->decimal('from_long', 11, 8)->nullable()->after('from_lat');
            $table->decimal('to_lat', 10, 8)->nullable()->after('to_id');
            $table->decimal('to_long', 11, 8)->nullable()->after('to_lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distance_cache', function (Blueprint $table) {
            // Remove coordinate columns
            $table->dropColumn(['from_lat', 'from_long', 'to_lat', 'to_long']);
            
            // Revert from_id and to_id to not nullable (if needed for rollback)
            $table->foreignId('from_id')->nullable(false)->change();
            $table->foreignId('to_id')->nullable(false)->change();
        });
    }
};
