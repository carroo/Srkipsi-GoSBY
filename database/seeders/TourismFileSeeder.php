<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = [];

        // Untuk setiap tourism (1-6), buat 3-5 file gambar
        for ($tourismId = 1; $tourismId <= 6; $tourismId++) {
            // Jumlah file acak antara 3-5 untuk setiap tourism
            $fileCount = rand(3, 5);

            for ($i = 1; $i <= $fileCount; $i++) {
                $files[] = [
                    'tourism_id' => $tourismId,
                    'file_path' => 'https://picsum.photos/800/600?random=' . ($tourismId * 10 + $i),
                    'file_type' => 'image',
                    'original_name' => 'tourism_' . $tourismId . '_image_' . $i . '.jpg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('tourism_file')->insert($files);
    }
}
