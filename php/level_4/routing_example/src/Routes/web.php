<?php
declare(strict_types=1);

/** @var Bramus\Router\Router $router */

// publiek
$router->get('/', fn() => $eventController->index());
$router->get('/events/(\d+)', fn($id) => $eventController->show($id));

// inloggen
$router->get('/login', fn() => $authController->showLogin());
$router->post('/login', fn() => $authController->login());
$router->post('/logout', fn() => $authController->logout());

// beheer (de admin-acties roepen zelf requireLogin() aan)
$router->get('/admin/events', fn() => $adminController->index());
$router->get('/admin/events/create', fn() => $adminController->create());
$router->post('/admin/events', fn() => $adminController->store());
$router->get('/admin/events/(\d+)/edit', fn($id) => $adminController->edit($id));
$router->post('/admin/events/(\d+)', fn($id) => $adminController->update($id));
$router->post('/admin/events/(\d+)/delete', fn($id) => $adminController->destroy($id));

$router->set404(function () {
    http_response_code(404);
    echo '404 - Pagina niet gevonden';
});
