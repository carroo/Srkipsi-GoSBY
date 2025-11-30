<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pantai Bali -> Parkir Gratis, Toilet Bersih, Warung Makan, WiFi Gratis, Pos Kesehatan, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 1, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Gunung Bromo -> Parkir Gratis, Toilet Bersih, Pemandu Wisata, Pos Kesehatan, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 2, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Candi Borobudur -> Parkir Gratis, Toilet Bersih, Restoran, Musholla, Pemandu Wisata, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 3, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Kawah Ijen -> Parkir Gratis, Toilet Bersih, Warung Makan, Pemandu Wisata, Pos Kesehatan, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 4, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Taman Mini Indonesia Indah -> Parkir Gratis, Toilet Bersih, Restoran, Warung Makan, Musholla, WiFi Gratis, Area Bermain Anak, Pos Kesehatan, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 5, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Raja Ampat -> Parkir Gratis, Toilet Bersih, Restoran, Pemandu Wisata, WiFi Gratis, Loker, Pos Kesehatan, Toko Cinderamata
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 6, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
