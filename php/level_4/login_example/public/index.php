<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Repositories\UserRepository;
use App\Services\AuthService;

// Enige plek waar de databasegegevens staan. PDO geeft ons een verbinding.
$dbConnection = new PDO("mysql:host=localhost;dbname=login_example", "peter", "peter");

// Lagen opbouwen van binnen naar buiten via dependency injection.
// Elke laag krijgt precies de afhankelijkheid die hij nodig heeft.
$userRepository = new UserRepository($dbConnection);
$authService = new AuthService($userRepository);
$authController = new AuthController($authService);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $authController->register($_POST['username'], $_POST['password']);
        }catch(RuntimeException $e){
            echo $e->getMessage();
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button>Register</button>
    </form>
</body>
</html>