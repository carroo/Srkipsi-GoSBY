<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@tourism.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Jalan Admin No. 1, Kota Besar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Wisata',
                'email' => 'admin@tourism.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'address' => 'Jalan Admin No. 2, Kota Besar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Konten',
                'email' => 'admin.konten@tourism.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'address' => 'Jalan Admin No. 3, Kota Besar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
