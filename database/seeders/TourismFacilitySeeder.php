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
        // Tugu Pahlawan -> Parkir Gratis, Toilet Bersih, Musholla, Pemandu Wisata, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 1, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 1, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Kebun Binatang Surabaya -> Parkir Gratis, Toilet Bersih, Restoran, Warung Makan, Musholla, WiFi Gratis, Area Bermain Anak, Pos Kesehatan, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 2, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 2, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // House of Sampoerna -> Parkir Gratis, Toilet Bersih, Restoran, Musholla, WiFi Gratis, Pemandu Wisata, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 3, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 3, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Monumen Kapal Selam -> Parkir Gratis, Toilet Bersih, Musholla, Pemandu Wisata, Pos Kesehatan, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 4, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 4, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Suramadu National Bridge -> Parkir Gratis, Toilet Bersih, Musholla, WiFi Gratis, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 5, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 5, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Ciputra Waterpark -> Parkir Gratis, Toilet Bersih, Restoran, Warung Makan, Musholla, WiFi Gratis, Area Bermain Anak, Pos Kesehatan, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 6, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 6, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Taman Bungkul -> Parkir Gratis, Toilet Bersih, Warung Makan, Musholla, WiFi Gratis, Area Bermain Anak, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 7, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'facility_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 7, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Masjid Al Akbar -> Parkir Gratis, Toilet Bersih, Musholla, WiFi Gratis, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 8, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 8, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 8, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 8, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 8, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pantai Kenjeran -> Parkir Gratis, Toilet Bersih, Warung Makan, Musholla, WiFi Gratis, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 9, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 9, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 9, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 9, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 9, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 9, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Museum WR Soepratman -> Parkir Gratis, Toilet Bersih, Musholla, Pemandu Wisata, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 10, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 10, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 10, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 10, 'facility_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 10, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 10, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Grand City Mall Surabaya -> Parkir Gratis, Toilet Bersih, Restoran, Warung Makan, Musholla, WiFi Gratis, Loker, Area Bermain Anak, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 11, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 11, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Tunjungan Plaza -> Parkir Gratis, Toilet Bersih, Restoran, Warung Makan, Musholla, WiFi Gratis, Loker, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 12, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 12, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pakuwon Mall -> Parkir Gratis, Toilet Bersih, Restoran, Warung Makan, Musholla, WiFi Gratis, Loker, Area Bermain Anak, Toko Cinderamata, Area Fotografi
        DB::table('tourism_facility')->insert([
            ['tourism_id' => 13, 'facility_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['tourism_id' => 13, 'facility_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
