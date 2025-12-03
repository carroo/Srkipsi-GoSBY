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
        Schema::table('tourism', function (Blueprint $table) {
            $table->string('external_id')->nullable()->unique()->after('id');
            $table->string('external_source')->nullable()->after('external_id')->comment('Source of external data, e.g., tourism.surabaya.go.id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourism', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'external_source']);
        });
    }
};
