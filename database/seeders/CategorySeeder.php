<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Sejarah', 'description' => 'Situs bersejarah dan monumen perjuangan di Surabaya'],
            ['name' => 'Hiburan', 'description' => 'Taman hiburan, wahana permainan, dan rekreasi keluarga'],
            ['name' => 'Religi', 'description' => 'Wisata religi seperti masjid dan tempat ibadah'],
            ['name' => 'Alam', 'description' => 'Wisata alam seperti taman kota dan pantai'],
            ['name' => 'Budaya', 'description' => 'Museum dan tempat bersejarah budaya Surabaya'],
            ['name' => 'Kuliner', 'description' => 'Tempat wisata kuliner dan makanan khas Surabaya'],
            ['name' => 'Edukasi', 'description' => 'Wisata edukasi seperti museum dan kebun binatang'],
            ['name' => 'Modern', 'description' => 'Ikon modern dan landmark kota Surabaya'],
        ];

        foreach ($categories as $category) {
            DB::table('category')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
