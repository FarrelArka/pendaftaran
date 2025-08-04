<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';
// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER'],
    'host'      => $_ENV['DB_HOST'],
    'port'      => $_ENV['DB_PORT'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'prefix'    => '',
    'schema'    => $_ENV['DB_SCHEMA'],
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $pdo = $capsule->getConnection()->getPdo();
    // echo "✅ Database connected successfully.<br>"; // Jangan echo di production
    $conn = $pdo; // Inisialisasi variabel global $conn
} catch (Exception $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
