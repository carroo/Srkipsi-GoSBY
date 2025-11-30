<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pantai Bali -> Pantai, Alam
        DB::table('tourism_category')->insert([
            ['tourism_id' => 1, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'category_id' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Gunung Bromo -> Gunung, Adventure
        DB::table('tourism_category')->insert([
            ['tourism_id' => 2, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Candi Borobudur -> Budaya, Sejarah
        DB::table('tourism_category')->insert([
            ['tourism_id' => 3, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Kawah Ijen -> Gunung, Adventure, Alam
        DB::table('tourism_category')->insert([
            ['tourism_id' => 4, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'category_id' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Taman Mini Indonesia Indah -> Hiburan, Budaya
        DB::table('tourism_category')->insert([
            ['tourism_id' => 5, 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Raja Ampat -> Pantai, Alam, Adventure
        DB::table('tourism_category')->insert([
            ['tourism_id' => 6, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'category_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
