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
        
        // Pantai Bali - Buka setiap hari 06:00 - 18:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 1,
                'day' => $day,
                'open_time' => '06:00',
                'close_time' => '18:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Gunung Bromo - Buka setiap hari 06:00 - 16:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 2,
                'day' => $day,
                'open_time' => '06:00',
                'close_time' => '16:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Candi Borobudur - Buka setiap hari 06:00 - 17:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 3,
                'day' => $day,
                'open_time' => '06:00',
                'close_time' => '17:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kawah Ijen - Buka setiap hari 07:00 - 15:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 4,
                'day' => $day,
                'open_time' => '07:00',
                'close_time' => '15:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Taman Mini Indonesia Indah - Buka setiap hari 07:00 - 17:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 5,
                'day' => $day,
                'open_time' => '07:00',
                'close_time' => '17:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Raja Ampat - Buka setiap hari 08:00 - 17:00
        foreach ($days as $day) {
            DB::table('tourism_hour')->insert([
                'tourism_id' => 6,
                'day' => $day,
                'open_time' => '08:00',
                'close_time' => '17:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
