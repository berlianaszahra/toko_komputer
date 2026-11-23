<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Voucher;
use App\Models\Article;
use App\Models\Review;
use App\Models\Discussion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Toko Komputer',
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
            'name' => 'Intan User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 1. Products (Berlian)
        Product::create(['user_id' => $user1->id, 'name' => 'Laptop Asus ROG Strix', 'description' => 'Laptop gaming RTX 4060', 'price' => 18500000, 'stock' => 5]);
        Product::create(['user_id' => $user2->id, 'name' => 'Monitor LG UltraGear 24', 'description' => 'Monitor IPS 144Hz', 'price' => 2350000, 'stock' => 12]);

        // 2. Categories (Dira)
        Category::create(['user_id' => $user1->id, 'name' => 'Laptop Gaming', 'description' => 'Koleksi laptop performa tinggi']);
        Category::create(['user_id' => $user2->id, 'name' => 'Aksesoris PC', 'description' => 'Keyboard, mouse, dan headset']);

        // 3. Vouchers (Lecha)
        Voucher::create(['user_id' => $user1->id, 'code' => 'RAKITPC10', 'discount_percent' => 10, 'description' => 'Diskon 10% rakit PC']);
        Voucher::create(['user_id' => $user2->id, 'code' => 'HEMAT20', 'discount_percent' => 20, 'description' => 'Diskon 20% aksesoris']);

        // 4. Articles (Intan)
        Article::create(['user_id' => $user1->id, 'title' => 'Tips Memilih PSU untuk PC Gaming', 'content' => 'Pastikan memilih sertifikasi minimal 80+ Bronze.']);
        Article::create(['user_id' => $user2->id, 'title' => 'Perbedaan SSD SATA dan NVMe', 'content' => 'SSD NVMe memiliki kecepatan baca tulis jauh lebih tinggi.']);

        // 5. Reviews (Nasywa)
        Review::create(['user_id' => $user1->id, 'product_name' => 'Laptop Asus ROG Strix', 'rating' => 5, 'comment' => 'Pengiriman cepat, barang ori dan mulus.']);
        Review::create(['user_id' => $user2->id, 'product_name' => 'Monitor LG UltraGear 24', 'rating' => 4, 'comment' => 'Warna tajam, sangat nyaman dipakai main game.']);

        // 6. Discussions (Hana)
        Discussion::create(['user_id' => $user1->id, 'topic' => 'Rekomendasi VGA Budget 4 Juta', 'question' => 'Mending ambil RTX 3060 atau RX 6600 XT?']);
        Discussion::create(['user_id' => $user2->id, 'topic' => 'Suhu Processor Tinggi', 'question' => 'Apakah perlu ganti thermal paste setiap 1 tahun?']);
    }
}