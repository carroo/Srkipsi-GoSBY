<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register helper function for image URL
        if (!function_exists('tourismImageUrl')) {
            /**
             * Get tourism image URL - returns full URL for external links or asset URL for local files
             */
            function tourismImageUrl($filePath) {
                if (filter_var($filePath, FILTER_VALIDATE_URL)) {
                    // If it's already a full URL (from API), return as is
                    return $filePath;
                }
                // If it's a local path, use asset
                return asset('storage/' . $filePath);
            }
        }
    }
}
