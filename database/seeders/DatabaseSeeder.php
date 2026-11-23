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
        // 1. Buat Akun Admin (Wajib Kelompok)
        $admin = User::create([
            'name' => 'Admin Kelompok',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun User 1 (Disimpan ke variabel $user1)
        $user1 = User::create([
            'name' => 'Berlian User 1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Buat Akun User 2 (Disimpan ke variabel $user2)
        $user2 = User::create([
            'name' => 'Dira User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 4. Data Dummy Tabel Products (Bagian Berlian)
        Product::create([
            'user_id' => $user1->id, // Otomatis terisi ID milik user1
            'name' => 'Laptop Asus ROG',
            'description' => 'Produk milik User 1',
            'price' => 15000000,
            'stock' => 5,
        ]);

        Product::create([
            'user_id' => $user2->id, // Otomatis terisi ID milik user2
            'name' => 'Mouse Logitech Wireless',
            'description' => 'Produk milik User 2',
            'price' => 350000,
            'stock' => 20,
        ]);

        // Nanti data dummy milik Dira, Lecha, Intan, Nasywa, dan Hana
        // tinggal ditambahkan di bawah baris ini dengan cara yang sama.
    }
}