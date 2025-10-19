<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\CategoryController;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbHost = getenv('DB_HOST') ?: 'db';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'test_shop';
$dbUser = getenv('DB_USER') ?: 'user';
$dbPass = getenv('DB_PASS') ?: 'password';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');

if ($path === '' || $path === '/categories') {
    $ctrl = new CategoryController();
    $ctrl->index();
    exit;
}

if (preg_match('#^/category/([^/]+)$#', $path, $m)) {
    $slug = urldecode($m[1]);
    $ctrl = new CategoryController();
    $ctrl->show($slug);
    exit;
}

if ($path === '/seed') {
    $pdo = new PDO("mysql:host=db;port=3306;dbname=test_shop;charset=utf8", "user", "password");
    $seeder = new \App\Seeders\DatabaseSeeder($pdo);
    $seeder->run();
    exit;
}

if ($path === '/api/products') {
    $ctrl = new App\Controllers\CategoryController();
    $ctrl->apiProducts();
    exit;
}
