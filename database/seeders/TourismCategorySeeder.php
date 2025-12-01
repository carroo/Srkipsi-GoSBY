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
        // Tugu Pahlawan -> Sejarah, Budaya
        DB::table('tourism_category')->insert([
            ['tourism_id' => 1, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Kebun Binatang Surabaya -> Edukasi, Hiburan
        DB::table('tourism_category')->insert([
            ['tourism_id' => 2, 'category_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // House of Sampoerna -> Budaya, Sejarah
        DB::table('tourism_category')->insert([
            ['tourism_id' => 3, 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Monumen Kapal Selam -> Sejarah, Edukasi
        DB::table('tourism_category')->insert([
            ['tourism_id' => 4, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'category_id' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Suramadu National Bridge -> Modern, Alam
        DB::table('tourism_category')->insert([
            ['tourism_id' => 5, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Ciputra Waterpark -> Hiburan, Alam
        DB::table('tourism_category')->insert([
            ['tourism_id' => 6, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Taman Bungkul -> Alam, Hiburan
        DB::table('tourism_category')->insert([
            ['tourism_id' => 7, 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Masjid Al Akbar -> Religi, Modern
        DB::table('tourism_category')->insert([
            ['tourism_id' => 8, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 8, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pantai Kenjeran -> Alam, Kuliner
        DB::table('tourism_category')->insert([
            ['tourism_id' => 9, 'category_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 9, 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Museum WR Soepratman -> Budaya, Sejarah
        DB::table('tourism_category')->insert([
            ['tourism_id' => 10, 'category_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 10, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Grand City Mall Surabaya -> Hiburan, Kuliner, Modern
        DB::table('tourism_category')->insert([
            ['tourism_id' => 11, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Tunjungan Plaza -> Hiburan, Kuliner, Modern
        DB::table('tourism_category')->insert([
            ['tourism_id' => 12, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pakuwon Mall -> Hiburan, Kuliner, Modern
        DB::table('tourism_category')->insert([
            ['tourism_id' => 13, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'category_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'category_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
