<?php
use App\Domain\User;
use App\Repositories\UserRepository;

require_once __DIR__. "/../vendor/autoload.php";

$pdo = new PDO("mysql:host=localhost;dbname=user_repo_opdracht", "peter", "peter");

$userRepo = new UserRepository($pdo);

$user = $userRepo->findByUsername("div");

echo $user->username;