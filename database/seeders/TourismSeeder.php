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
                'name' => 'Tugu Pahlawan',
                'description' => 'Monumen bersejarah setinggi 41 meter yang dibangun untuk mengenang pertempuran 10 November 1945. Dilengkapi dengan museum dan taman yang asri.',
                'location' => 'Jl. Pahlawan, Surabaya',
                'latitude' => '-7.2458',
                'longitude' => '112.7378',
                'phone' => '031-3571100',
                'email' => 'tugupahlawan@surabaya.go.id',
                'website' => 'www.tugupahlawan-sby.com',
                'rating' => 4.7,
            ],
            [
                'name' => 'Kebun Binatang Surabaya',
                'description' => 'Kebun binatang tertua di Indonesia dengan koleksi lebih dari 2.000 satwa dari berbagai spesies. Tempat rekreasi keluarga yang edukatif.',
                'location' => 'Jl. Setail No.1, Surabaya',
                'latitude' => '-7.2817',
                'longitude' => '112.7317',
                'phone' => '031-5678401',
                'email' => 'info@kebunbinatangsurabaya.com',
                'website' => 'www.kebunbinatangsurabaya.com',
                'rating' => 4.3,
            ],
            [
                'name' => 'House of Sampoerna',
                'description' => 'Museum bersejarah yang menampilkan warisan industri rokok kretek Indonesia. Bangunan bergaya kolonial Belanda dengan arsitektur yang memukau.',
                'location' => 'Jl. Taman Sampoerna No.6, Surabaya',
                'latitude' => '-7.2524',
                'longitude' => '112.7315',
                'phone' => '031-3539000',
                'email' => 'info@houseofsampoerna.com',
                'website' => 'www.houseofsampoerna.museum',
                'rating' => 4.6,
            ],
            [
                'name' => 'Monumen Kapal Selam',
                'description' => 'Museum kapal selam KRI Pasopati 410 yang dapat dijelajahi pengunjung. Memberikan pengalaman unik tentang kehidupan awak kapal selam.',
                'location' => 'Jl. Pemuda No.39, Surabaya',
                'latitude' => '-7.2647',
                'longitude' => '112.7517',
                'phone' => '031-3571995',
                'email' => 'monkasel@surabaya.go.id',
                'website' => 'www.monkasel-surabaya.com',
                'rating' => 4.5,
            ],
            [
                'name' => 'Suramadu National Bridge',
                'description' => 'Jembatan terpanjang di Indonesia yang menghubungkan Surabaya dan Madura. Ikon modern kota Surabaya dengan pemandangan spektakuler.',
                'location' => 'Surabaya - Madura',
                'latitude' => '-7.2155',
                'longitude' => '112.7890',
                'phone' => '031-8280000',
                'email' => 'info@suramadu.com',
                'website' => 'www.jembatansuramadu.com',
                'rating' => 4.6,
            ],
            [
                'name' => 'Ciputra Waterpark',
                'description' => 'Taman rekreasi air dengan berbagai wahana seru dan kolam renang. Destinasi favorit keluarga untuk menghabiskan akhir pekan.',
                'location' => 'CitraLand, Surabaya',
                'latitude' => '-7.2918',
                'longitude' => '112.6150',
                'phone' => '031-7410777',
                'email' => 'info@ciputrawaterpark.com',
                'website' => 'www.ciputrawaterpark.com',
                'rating' => 4.4,
            ],
            [
                'name' => 'Taman Bungkul',
                'description' => 'Taman kota yang hijau dan nyaman untuk bersantai. Dilengkapi dengan area bermain anak, jogging track, dan free WiFi.',
                'location' => 'Jl. Raya Darmo, Surabaya',
                'latitude' => '-7.2892',
                'longitude' => '112.7392',
                'phone' => '031-5030000',
                'email' => 'tamanbungkul@surabaya.go.id',
                'website' => 'www.tamanbungkul.com',
                'rating' => 4.5,
            ],
            [
                'name' => 'Masjid Al Akbar',
                'description' => 'Masjid terbesar kedua di Indonesia dengan menara setinggi 99 meter. Arsitektur modern yang megah dengan pemandangan kota dari atas menara.',
                'location' => 'Jl. Masjid Al Akbar Timur No.1, Surabaya',
                'latitude' => '-7.3314',
                'longitude' => '112.7277',
                'phone' => '031-8284618',
                'email' => 'info@masjidakbar.com',
                'website' => 'www.masjidakbar-surabaya.com',
                'rating' => 4.8,
            ],
            [
                'name' => 'Pantai Kenjeran',
                'description' => 'Pantai dengan pemandangan laut yang indah dan ikon Pagoda Tian Ti. Tempat favorit untuk menikmati sunset dan kuliner seafood.',
                'location' => 'Kenjeran, Surabaya',
                'latitude' => '-7.2403',
                'longitude' => '112.7917',
                'phone' => '031-3811234',
                'email' => 'pantaikenjeran@surabaya.go.id',
                'website' => 'www.pantaikenjeran.com',
                'rating' => 4.3,
            ],
            [
                'name' => 'Museum WR Soepratman',
                'description' => 'Museum yang didedikasikan untuk pencipta lagu kebangsaan Indonesia Raya. Menampilkan koleksi benda bersejarah dan kisah perjuangan.',
                'location' => 'Jl. Mangga No.21, Surabaya',
                'latitude' => '-7.2464',
                'longitude' => '112.7421',
                'phone' => '031-3532168',
                'email' => 'museum.wrsoepratman@surabaya.go.id',
                'website' => 'www.museumwrsoepratman.com',
                'rating' => 4.4,
            ],
            [
                'name' => 'Grand City Mall Surabaya',
                'description' => 'Mall terbesar di Surabaya dengan berbagai tenant fashion, kuliner, dan hiburan. Destinasi shopping modern dengan bioskop dan area bermain anak.',
                'location' => 'Jl. Gubeng Pojok No.1, Surabaya',
                'latitude' => '-7.2706',
                'longitude' => '112.7517',
                'phone' => '031-99200000',
                'email' => 'info@grandcitysurabaya.com',
                'website' => 'www.grandcitysurabaya.com',
                'rating' => 4.6,
            ],
            [
                'name' => 'Tunjungan Plaza',
                'description' => 'Mall legendaris di pusat kota Surabaya dengan konsep lifestyle dan fashion. Menawarkan berbagai brand internasional dan lokal.',
                'location' => 'Jl. Basuki Rahmat No.8-12, Surabaya',
                'latitude' => '-7.2625',
                'longitude' => '112.7383',
                'phone' => '031-5319111',
                'email' => 'info@tunjunganplaza.com',
                'website' => 'www.tunjunganplaza.com',
                'rating' => 4.5,
            ],
            [
                'name' => 'Pakuwon Mall',
                'description' => 'Mall premium dengan konsep lifestyle yang lengkap. Menyediakan tenant eksklusif, restoran, dan area entertainment untuk keluarga.',
                'location' => 'Jl. Puncak Indah Lontar No.2, Surabaya',
                'latitude' => '-7.2750',
                'longitude' => '112.7808',
                'phone' => '031-5936888',
                'email' => 'info@pakuwonmall.com',
                'website' => 'www.pakuwonmall.com',
                'rating' => 4.7,
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
