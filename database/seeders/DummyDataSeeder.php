<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Image;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === 1. Create a User for Transactions ===
        $user = User::firstOrCreate([
            'email' => 'buyer@example.com'
        ], [
            'name' => 'Buyer Account',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // === 2. Create 5 Products ===
        $productsData = [
            [
                'name' => 'Acer Predator Helios 300',
                'brand' => 'Acer',
                'type' => 'Gaming',
                'description' => 'Powerful gaming performance with advanced cooling.',
                'price' => 18000000,
                'processor' => 'Intel Core i7-11800H',
                'ram' => '16GB DDR4',
                'storage' => '512GB NVMe SSD',
                'graphic' => 'NVIDIA GeForce RTX 3060',
                'display' => '15.6" FHD 144Hz',
                'battery' => '59Wh',
                'weight' => '2.5kg',
                'minus' => 'Slight scratches on the lid',
            ],
            [
                'name' => 'Asus ROG Zephyrus G14',
                'brand' => 'Asus',
                'type' => 'Gaming',
                'description' => 'Compact and portable gaming laptop.',
                'price' => 22000000,
                'processor' => 'AMD Ryzen 9 5900HS',
                'ram' => '16GB DDR4',
                'storage' => '1TB NVMe SSD',
                'graphic' => 'NVIDIA GeForce RTX 3060',
                'display' => '14" QHD 120Hz',
                'battery' => '76Wh',
                'weight' => '1.6kg',
                'minus' => null,
            ],
            [
                'name' => 'Dell XPS 13',
                'brand' => 'Dell',
                'type' => 'Ultrabook',
                'description' => 'Premium ultrabook with infinity edge display.',
                'price' => 24000000,
                'processor' => 'Intel Core i7-1165G7',
                'ram' => '16GB LPDDR4x',
                'storage' => '512GB NVMe SSD',
                'graphic' => 'Intel Iris Xe Graphics',
                'display' => '13.4" FHD+',
                'battery' => '52Wh',
                'weight' => '1.2kg',
                'minus' => 'No original box',
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'brand' => 'Lenovo',
                'type' => 'Business',
                'description' => 'The ultimate business laptop with excellent keyboard.',
                'price' => 26000000,
                'processor' => 'Intel Core i7-1185G7',
                'ram' => '16GB LPDDR4x',
                'storage' => '1TB NVMe SSD',
                'graphic' => 'Intel Iris Xe Graphics',
                'display' => '14" WUXGA',
                'battery' => '57Wh',
                'weight' => '1.13kg',
                'minus' => null,
            ],
            [
                'name' => 'MacBook Air M1',
                'brand' => 'Apple',
                'type' => 'Student',
                'description' => 'Efficient laptop with incredible battery life.',
                'price' => 15000000,
                'processor' => 'Apple M1',
                'ram' => '8GB Unified Memory',
                'storage' => '256GB SSD',
                'graphic' => '7-core GPU',
                'display' => '13.3" Retina Display',
                'battery' => '49.9Wh',
                'weight' => '1.29kg',
                'minus' => 'Battery cycle count > 200',
            ],
        ];

        $products = collect();
        foreach ($productsData as $data) {
            $product = Product::create($data);
            $products->push($product);

            // Create image for product
            Image::create([
                'model_id' => $product->id,
                'model_type' => Product::class,
                'image' => 'https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/dbfb8172-2736-4074-b5ab-7aef1f6cedd1_1600w.jpg',
            ]);
        }

        // === 3. Create 5 Articles ===
        $titles = [
            'Tips Memilih Laptop Gaming',
            'Review MacBook Air M1',
            'Cara Merawat Baterai Laptop',
            'Top 5 Ultrabook 2024',
            'Panduan Upgrade RAM Laptop'
        ];

        foreach ($titles as $index => $title) {
            Article::create([
                'name' => $title,
                'description' => '<p>Ini adalah konten untuk artikel ' . $title . '. Di sini Anda bisa membaca berbagai informasi menarik yang bermanfaat.</p>',
                'author' => 'Admin ZLM.ID',
                'date' => now()->subDays(rand(1, 30)),
            ]);
        }

        // === 4. Create 5 Transactions and Transaction Items ===
        for ($i = 0; $i < 5; $i++) {
            // Pick a random product
            $randomProduct = $products->random();
            $qty = rand(1, 2);
            $totalPrice = $randomProduct->price * $qty;

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'payment_method' => ['bank_transfer', 'credit_card', 'ewallet'][rand(0, 2)],
                'payment_status' => ['pending', 'paid', 'failed'][rand(0, 1)],
                'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $randomProduct->id,
                'quantity' => $qty,
                'price' => $randomProduct->price,
                'total_price' => $totalPrice,
            ]);
        }
    }
}
