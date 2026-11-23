<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Kelompok',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'Berlian User 1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Dira User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Data Dummy Products (Bagian Berlian)
        Product::create([
            'user_id' => $user1->id,
            'name' => 'Laptop Asus ROG Strix',
            'description' => 'Laptop gaming milik User 1',
            'price' => 18500000,
            'stock' => 5,
        ]);

        Product::create([
            'user_id' => $user2->id,
            'name' => 'Monitor LG UltraGear 24 Inch',
            'description' => 'Monitor IPS 144Hz milik User 2',
            'price' => 2350000,
            'stock' => 12,
        ]);

        // Tambahkan data dummy milik Dira, Lecha, Intan, Nasywa, dan Hana di bawah ini
    }
}