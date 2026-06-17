<?php
declare(strict_types=1);

use App\Config\Database;
use App\Controllers\AdminEventController;
use App\Controllers\AuthController;
use App\Controllers\EventController;
use App\Repositories\AdminRepository;
use App\Repositories\EventRepository;
use App\Services\AuthService;
use App\Services\EventService;
use Dotenv\Dotenv;

// instellingen uit .env laden
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// lagen opbouwen: pdo -> repository -> service -> controller
$pdo = Database::getInstance();

$eventRepository = new EventRepository($pdo);
$adminRepository = new AdminRepository($pdo);

$eventService = new EventService($eventRepository);
$authService  = new AuthService($adminRepository);

$eventController = new EventController($eventService);
$adminController = new AdminEventController($eventService, $authService);
$authController  = new AuthController($authService);
