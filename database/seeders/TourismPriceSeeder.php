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
            // Pantai Bali
            [
                'tourism_id' => 1,
                'type' => 'Dewasa',
                'price' => 50000,
            ],
            [
                'tourism_id' => 1,
                'type' => 'Anak-anak',
                'price' => 25000,
            ],
            [
                'tourism_id' => 1,
                'type' => 'Parkir Motor',
                'price' => 5000,
            ],
            [
                'tourism_id' => 1,
                'type' => 'Parkir Mobil',
                'price' => 10000,
            ],
            // Gunung Bromo
            [
                'tourism_id' => 2,
                'type' => 'Masuk Taman',
                'price' => 75000,
            ],
            [
                'tourism_id' => 2,
                'type' => 'Jeep 4WD (6 jam)',
                'price' => 1200000,
            ],
            [
                'tourism_id' => 2,
                'type' => 'Pemandu Wisata',
                'price' => 300000,
            ],
            // Candi Borobudur
            [
                'tourism_id' => 3,
                'type' => 'Wisatawan Lokal',
                'price' => 30000,
            ],
            [
                'tourism_id' => 3,
                'type' => 'Wisatawan Mancanegara',
                'price' => 200000,
            ],
            [
                'tourism_id' => 3,
                'type' => 'Sunrise Tour',
                'price' => 75000,
            ],
            // Kawah Ijen
            [
                'tourism_id' => 4,
                'type' => 'Tiket Masuk',
                'price' => 100000,
            ],
            [
                'tourism_id' => 4,
                'type' => 'Pemandu Wisata',
                'price' => 500000,
            ],
            // Taman Mini Indonesia Indah
            [
                'tourism_id' => 5,
                'type' => 'Tiket Masuk Umum',
                'price' => 25000,
            ],
            [
                'tourism_id' => 5,
                'type' => 'Tiket Masuk Anak',
                'price' => 15000,
            ],
            [
                'tourism_id' => 5,
                'type' => 'Tiket Keluarga',
                'price' => 80000,
            ],
            // Raja Ampat
            [
                'tourism_id' => 6,
                'type' => 'Tiket Masuk',
                'price' => 150000,
            ],
            [
                'tourism_id' => 6,
                'type' => 'Paket Snorkeling (sehari)',
                'price' => 500000,
            ],
            [
                'tourism_id' => 6,
                'type' => 'Paket Diving (sehari)',
                'price' => 750000,
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
