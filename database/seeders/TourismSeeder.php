<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tourisms = [
            [
                'name' => 'Pantai Bali',
                'description' => 'Pantai pasir putih yang indah dengan ombak yang sempurna untuk surfing. Dilengkapi dengan berbagai fasilitas wisata modern.',
                'location' => 'Bali, Indonesia',
                'latitude' => '-8.6705',
                'longitude' => '115.2126',
                'phone' => '0361-123456',
                'email' => 'pantaibali@tourism.com',
                'website' => 'www.pantaibali.com',
                'rating' => 4.8,
            ],
            [
                'name' => 'Gunung Bromo',
                'description' => 'Gunung berapi aktif dengan pemandangan sunrise yang spektakuler. Destinasi favorit para pendaki dan fotografer alam.',
                'location' => 'Probolinggo, Jawa Timur',
                'latitude' => '-7.9422',
                'longitude' => '112.9508',
                'phone' => '0335-789012',
                'email' => 'gunungbromo@tourism.com',
                'website' => 'www.gunungbromo.com',
                'rating' => 4.7,
            ],
            [
                'name' => 'Candi Borobudur',
                'description' => 'Candi Buddha terbesar di dunia dengan arsitektur yang menakjubkan. Situs warisan dunia UNESCO yang wajib dikunjungi.',
                'location' => 'Magelang, Jawa Tengah',
                'latitude' => '-7.6079',
                'longitude' => '110.2008',
                'phone' => '0293-545678',
                'email' => 'borobudur@tourism.com',
                'website' => 'www.borobudur.com',
                'rating' => 4.9,
            ],
            [
                'name' => 'Kawah Ijen',
                'description' => 'Kawah vulkanik dengan fenomena api biru yang langka dan unik. Destinasi petualangan untuk pendaki ekstrem.',
                'location' => 'Banyuwangi, Jawa Timur',
                'latitude' => '-8.0583',
                'longitude' => '114.2417',
                'phone' => '0333-234567',
                'email' => 'kawaijen@tourism.com',
                'website' => 'www.kawaijen.com',
                'rating' => 4.6,
            ],
            [
                'name' => 'Taman Mini Indonesia Indah',
                'description' => 'Taman hiburan edukatif yang menampilkan miniatur keindahan Indonesia. Cocok untuk keluarga dan pelajar.',
                'location' => 'Jakarta, Indonesia',
                'latitude' => '-6.3041',
                'longitude' => '106.8000',
                'phone' => '021-8791711',
                'email' => 'tmii@tourism.com',
                'website' => 'www.tmii.com',
                'rating' => 4.5,
            ],
            [
                'name' => 'Raja Ampat',
                'description' => 'Kepulauan indah dengan terumbu karang terbaik di dunia. Surga bagi penyelam dan pecinta kehidupan laut.',
                'location' => 'Papua Barat',
                'latitude' => '-0.8333',
                'longitude' => '130.8333',
                'phone' => '0986-212345',
                'email' => 'rajaampat@tourism.com',
                'website' => 'www.rajaampat.com',
                'rating' => 4.9,
            ],
        ];

        foreach ($tourisms as $tourism) {
            DB::table('tourism')->insert([
                'name' => $tourism['name'],
                'description' => $tourism['description'],
                'location' => $tourism['location'],
                'latitude' => $tourism['latitude'],
                'longitude' => $tourism['longitude'],
                'phone' => $tourism['phone'],
                'email' => $tourism['email'],
                'website' => $tourism['website'],
                'rating' => $tourism['rating'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
