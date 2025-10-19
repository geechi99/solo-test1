<?php

require __DIR__ . '/vendor/autoload.php';

foreach (glob(__DIR__ . '/migrations/*.php') as $file) {
    echo "Running migration: " . basename($file) . PHP_EOL;
    require $file;
}
echo "✅ All migrations executed successfully." . PHP_EOL;

$pdo = new PDO("mysql:host=db;port=3306;dbname=test_shop;charset=utf8", "user", "password");
$seeder = new \App\Seeders\DatabaseSeeder($pdo);
$seeder->run();
echo "✅ Seeder executed successfully." . PHP_EOL;
