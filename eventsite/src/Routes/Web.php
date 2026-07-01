<?php

use App\Controllers\EventController;
use App\Services\EventService;
use App\Repositories\EventRepository;
use App\Database\Database;
use App\Services\AuthService;

$repository = new EventRepository(Database::connect());
$eventService =     new EventService($repository);
$authService = new AuthService(Database::connect());
$controller = new EventController($eventService, $authService);

// should show a homepage, currently no implementation yet
$router->get('/', function () {
    echo 'This is a homepage';
});


// get overview of all event
$router->get(
    '/events',
    fn() =>
    $controller->index()
);

// show specific event
$router->get('/events/(\d+)', function ($eventId) use ($controller) {
    $controller->show($eventId);
});

// shows creating screen of a new event
$router->get(
    '/events/create',
    fn() =>
    $controller->showEventCreationForm()
);

// create new event - fn so we can make use of the $controller variable. otherwise we have to use 'use ($controller) in every function
$router->post(
    '/events/create',
    fn() =>
    $controller->create()
);

// shows edit event screen for a specific event
$router->get('/events/(\d+)/edit', function ($eventId) use ($controller) {
    $controller->showEdit($eventId);
});

// update specific event - in an API we would use put, but we can't
$router->post('/events/(\d+)/edit', function ($eventId) use ($controller) {
    $controller->edit($eventId);
});

// delete a specific event
$router->post('/events/(\d+)/delete', function ($eventId) use ($controller) {
    $controller->deleteEvent($eventId);
});