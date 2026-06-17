<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;
use App\Repositories\UserRepository;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = Database::getInstance();
} catch (PDOException $e) {
}

$repository = new UserRepository($pdo);

$user = $repository->findByUsername('peter');

var_dump($user);