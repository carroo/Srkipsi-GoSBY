<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Tugu Pahlawan - Buka setiap hari 08:00 - 16:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 1,
                'day' => $day,
                'open_time' => '08:00',
                'close_time' => '16:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kebun Binatang Surabaya - Buka setiap hari 08:00 - 16:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 2,
                'day' => $day,
                'open_time' => '08:00',
                'close_time' => '16:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // House of Sampoerna - Buka setiap hari 09:00 - 16:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 3,
                'day' => $day,
                'open_time' => '09:00',
                'close_time' => '16:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Monumen Kapal Selam - Buka setiap hari 08:00 - 16:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 4,
                'day' => $day,
                'open_time' => '08:00',
                'close_time' => '16:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Suramadu National Bridge - Buka 24 jam
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 5,
                'day' => $day,
                'open_time' => '00:00',
                'close_time' => '23:59',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ciputra Waterpark - Buka setiap hari 09:00 - 17:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 6,
                'day' => $day,
                'open_time' => '09:00',
                'close_time' => '17:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Taman Bungkul - Buka setiap hari 06:00 - 18:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 7,
                'day' => $day,
                'open_time' => '06:00',
                'close_time' => '18:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Masjid Al Akbar - Buka setiap hari 24 jam untuk sholat
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 8,
                'day' => $day,
                'open_time' => '00:00',
                'close_time' => '23:59',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pantai Kenjeran - Buka setiap hari 06:00 - 18:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 9,
                'day' => $day,
                'open_time' => '06:00',
                'close_time' => '18:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Museum WR Soepratman - Buka setiap hari 08:00 - 15:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 10,
                'day' => $day,
                'open_time' => '08:00',
                'close_time' => '15:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Grand City Mall Surabaya - Buka setiap hari 10:00 - 22:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 11,
                'day' => $day,
                'open_time' => '10:00',
                'close_time' => '22:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tunjungan Plaza - Buka setiap hari 10:00 - 21:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 12,
                'day' => $day,
                'open_time' => '10:00',
                'close_time' => '21:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pakuwon Mall - Buka setiap hari 10:00 - 22:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 13,
                'day' => $day,
                'open_time' => '10:00',
                'close_time' => '22:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
