<?php

use App\Controllers\{EventController, LoginController};
use App\Services\{EventService, AuthService};
use App\Repositories\{EventRepository, UserRepository};
use App\Database\Database;

$repository = new EventRepository(Database::connect());
$eventService = new EventService($repository);

// De AuthService werkt via de UserRepository, niet rechtstreeks met PDO.
$userRepository = new UserRepository(Database::connect());
$authService = new AuthService($userRepository);

$eventController = new EventController($eventService, $authService);
$authController = new LoginController($authService);


// toont de homepage met uitleg over de site en het CSR-patroon
$router->get('/', function () use ($authService) {
    // wie is er ingelogd? (null als er niemand is ingelogd) - voor de nav bovenaan
    $currentUser = $authService->currentUser();
    require __DIR__ . '/../Views/homepage.php';
});


// get overview of all event
$router->get(
    '/events',
    fn() =>
    $eventController->index()
);

// show specific event
$router->get('/events/(\d+)', function ($eventId) use ($eventController) {
    $eventController->show($eventId);
});

// shows creating screen of a new event
$router->get(
    '/events/create',
    fn() =>
    $eventController->showEventCreationForm()
);

// create new event - fn so we can make use of the $eventController variable. otherwise we have to use 'use ($eventController) in every function
$router->post(
    '/events/create',
    fn() =>
    $eventController->create()
);

// shows edit event screen for a specific event
$router->get('/events/(\d+)/edit', function ($eventId) use ($eventController) {
    $eventController->showEdit($eventId);
});

// update specific event - in an API we would use put, but we can't
$router->post('/events/(\d+)/edit', function ($eventId) use ($eventController) {
    if($_POST['action'] == "Opslaan"){
        $eventController->edit($eventId);
        } else if ($_POST['action'] == "Verwijderen"){
            $eventController->delete($eventId);
        }
});

// delete a specific event
$router->post('/events/(\d+)/delete', function ($eventId) use ($eventController) {
    $eventController->delete($eventId);
});

// toont het inlogscherm
$router->get('/login', fn() => $authController->showLogin());

// verwerkt het inlogformulier
$router->post('/login', fn() => $authController->login());

// toont het registratiescherm
$router->get('/register', fn() => $authController->showRegister());

// verwerkt het registratieformulier
$router->post('/register', fn() => $authController->register());

// uitloggen en terug naar het inlogscherm
$router->get('/logout', fn() => $authController->logout());

// uitleg-detailpagina's: nette URLs zoals /uitleg/domain
$router->get('/uitleg/router', function () {
    require __DIR__ . '/../Views/uitleg/router.php';
});
$router->get('/uitleg/controller', function () {
    require __DIR__ . '/../Views/uitleg/controller.php';
});
$router->get('/uitleg/service', function () {
    require __DIR__ . '/../Views/uitleg/service.php';
});
$router->get('/uitleg/repository', function () {
    require __DIR__ . '/../Views/uitleg/repository.php';
});
$router->get('/uitleg/domain', function () {
    require __DIR__ . '/../Views/uitleg/domain.php';
});
$router->get('/uitleg/view', function () {
    require __DIR__ . '/../Views/uitleg/view.php';
});

$router->set404(function() {
    // stuur ook echt een 404-status mee, niet stiekem een 200
    http_response_code(404);
    require __DIR__ . '/../Views/page_not_found.php';
});