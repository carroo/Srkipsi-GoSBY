<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin
        $this->call(AdminSeeder::class);

        // Seed users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed kategori
        // $this->call(CategorySeeder::class);

        // Seed wisata
        // $this->call(TourismSeeder::class);

        // Seed tourism category
        // $this->call(TourismCategorySeeder::class);

        // Seed tourism price
        // $this->call(TourismPriceSeeder::class);

        // Seed tourism hour
        // $this->call(TourismHourSeeder::class);

        // Seed tourism file
        // $this->call(TourismFileSeeder::class);
    }
}
