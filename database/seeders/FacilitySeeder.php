<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            ['name' => 'Parkir Gratis', 'description' => 'Area parkir gratis untuk kendaraan pengunjung'],
            ['name' => 'Toilet Bersih', 'description' => 'Fasilitas toilet yang bersih dan terawat'],
            ['name' => 'Restoran', 'description' => 'Restoran dengan berbagai pilihan menu makanan'],
            ['name' => 'Warung Makan', 'description' => 'Warung makan dengan harga terjangkau'],
            ['name' => 'Musholla', 'description' => 'Tempat ibadah untuk kebutuhan spiritual pengunjung'],
            ['name' => 'WiFi Gratis', 'description' => 'Akses internet gratis untuk semua pengunjung'],
            ['name' => 'Loker', 'description' => 'Fasilitas loker untuk menyimpan barang berharga'],
            ['name' => 'Pemandu Wisata', 'description' => 'Layanan pemandu wisata profesional berbahasa Indonesia dan Inggris'],
            ['name' => 'Area Bermain Anak', 'description' => 'Taman bermain aman untuk anak-anak'],
            ['name' => 'Pos Kesehatan', 'description' => 'Pos kesehatan dengan petugas medis siaga'],
            ['name' => 'Toko Cinderamata', 'description' => 'Toko penjual cinderamata dan souvenir khas daerah'],
            ['name' => 'Area Fotografi', 'description' => 'Spot fotografi Instagramable untuk pengunjung'],
        ];

        foreach ($facilities as $facility) {
            DB::table('facility')->insert([
                'name' => $facility['name'],
                'description' => $facility['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
