<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            // Tugu Pahlawan
            [
                'tourism_id' => 1,
                'type' => 'Tiket Masuk',
                'price' => 5000,
            ],
            // Kebun Binatang Surabaya
            [
                'tourism_id' => 2,
                'type' => 'Tiket Dewasa',
                'price' => 15000,
            ],
            [
                'tourism_id' => 2,
                'type' => 'Tiket Anak',
                'price' => 10000,
            ],
            // House of Sampoerna
            [
                'tourism_id' => 3,
                'type' => 'Tiket Masuk',
                'price' => 25000,
            ],
            [
                'tourism_id' => 3,
                'type' => 'Tiket Wisatawan Asing',
                'price' => 50000,
            ],
            // Monumen Kapal Selam
            [
                'tourism_id' => 4,
                'type' => 'Tiket Masuk',
                'price' => 15000,
            ],
            [
                'tourism_id' => 4,
                'type' => 'Tiket Anak',
                'price' => 10000,
            ],
            // Suramadu National Bridge - Gratis untuk pejalan kaki
            [
                'tourism_id' => 5,
                'type' => 'Tiket Masuk Area Observasi',
                'price' => 0,
            ],
            // Ciputra Waterpark
            [
                'tourism_id' => 6,
                'type' => 'Tiket Masuk Weekday',
                'price' => 75000,
            ],
            [
                'tourism_id' => 6,
                'type' => 'Tiket Masuk Weekend',
                'price' => 100000,
            ],
            [
                'tourism_id' => 6,
                'type' => 'Tiket Anak',
                'price' => 50000,
            ],
            // Taman Bungkul - Gratis
            [
                'tourism_id' => 7,
                'type' => 'Tiket Masuk',
                'price' => 0,
            ],
            // Masjid Al Akbar - Gratis
            [
                'tourism_id' => 8,
                'type' => 'Tiket Masuk',
                'price' => 0,
            ],
            // Pantai Kenjeran
            [
                'tourism_id' => 9,
                'type' => 'Tiket Masuk',
                'price' => 5000,
            ],
            // Museum WR Soepratman
            [
                'tourism_id' => 10,
                'type' => 'Tiket Masuk',
                'price' => 5000,
            ],
            [
                'tourism_id' => 10,
                'type' => 'Tiket Anak',
                'price' => 2000,
            ],
            // Grand City Mall Surabaya - Gratis masuk
            [
                'tourism_id' => 11,
                'type' => 'Tiket Masuk',
                'price' => 0,
            ],
            // Tunjungan Plaza - Gratis masuk
            [
                'tourism_id' => 12,
                'type' => 'Tiket Masuk',
                'price' => 0,
            ],
            // Pakuwon Mall - Gratis masuk
            [
                'tourism_id' => 13,
                'type' => 'Tiket Masuk',
                'price' => 0,
            ],
        ];

        foreach ($prices as $price) {
            DB::table('tourism_price')->insert([
                'tourism_id' => $price['tourism_id'],
                'type' => $price['type'],
                'price' => $price['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
