<?php

namespace App\Seeders;

use PDO;

class DatabaseSeeder
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run()
    {
        echo "<pre>";

        $categories = [
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Tablets', 'slug' => 'tablets'],
            ['name' => 'Smartphones', 'slug' => 'smartphones'],
            ['name' => 'TV', 'slug' => 'tv'],
            ['name' => 'Games', 'slug' => 'games'],
        ];

        foreach ($categories as $cat) {
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
            $stmt->execute($cat);
        }

        echo "✅ Categories added\n";

        $products = [
            ['name' => 'Lenovo Yoga 3 Pro', 'price' => 1539.47, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 1],
            ['name' => 'Apple MacBook Air 13.6 M2', 'price' => 1241.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 1],
            ['name' => 'Apple MacBook Air 13.6 M3', 'price' => 1593.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 1],
            ['name' => 'Apple MacBook Pro 16 M4', 'price' => 3311.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 1],
            ['name' => 'ASUS Vivobook 15', 'price' => 899.99, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 1],
            ['name' => 'Samsung Galaxy Tab S9', 'price' => 749.99, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 2],
            ['name' => 'Apple iPad Pro 12.9', 'price' => 1199.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 2],
            ['name' => 'iPhone 15 Pro', 'price' => 1399.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 3],
            ['name' => 'Samsung Galaxy S24', 'price' => 1190.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 3],
            ['name' => 'Sony Bravia 55"', 'price' => 999.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 4],
            ['name' => 'PlayStation 5', 'price' => 599.00, 'created_at' => date('Y-m-d H:i:s'), 'category_id' => 5],
        ];

        foreach ($products as $p) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO products (name, price, created_at, category_id) 
                 VALUES (:name, :price, :created_at, :category_id)"
            );
            $stmt->execute($p);
        }

        echo "Products added\n";
        echo "Done!\n</pre>";
    }
}
