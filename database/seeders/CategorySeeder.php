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
            ['name' => 'Pantai', 'description' => 'Destinasi wisata pantai dengan pemandangan laut yang indah'],
            ['name' => 'Gunung', 'description' => 'Destinasi wisata gunung untuk pendaki dan pencinta alam'],
            ['name' => 'Budaya', 'description' => 'Destinasi wisata budaya dengan kekayaan tradisi lokal'],
            ['name' => 'Kuliner', 'description' => 'Tempat wisata kuliner untuk menikmati makanan khas daerah'],
            ['name' => 'Hiburan', 'description' => 'Taman hiburan dan wahana permainan seru untuk keluarga'],
            ['name' => 'Sejarah', 'description' => 'Situs bersejarah dan monumen bersejarah yang bernilai'],
            ['name' => 'Alam', 'description' => 'Destinasi wisata alam dengan flora dan fauna yang unik'],
            ['name' => 'Adventure', 'description' => 'Aktivitas petualangan menegangkan untuk pencari adrenalin'],
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
