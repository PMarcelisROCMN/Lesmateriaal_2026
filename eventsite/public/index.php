<?php

use Bramus\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// Sessie starten zodat we kunnen onthouden wie er is ingelogd.
session_start();

$router = new Router();

// Onder de ingebouwde PHP-server (php -S) draait de app op de root,
// onder Apache in de submap /lesmateriaal/eventsite/. We zetten de
// basePath daarop aan, anders matchen de routes niet.
if (php_sapi_name() === 'cli-server') {
    $router->setBasePath('/');
    // BASE_URL zetten we voor de links in de views (naar /events, /login enz.)
    define('BASE_URL', '/');
    // Bij `php -S` is de map public/ de webroot, dus css staat op /style.css
    define('ASSET_BASE', '/');
} else {
    $router->setBasePath('/lesmateriaal/eventsite/');
    define('BASE_URL', '/lesmateriaal/eventsite/');
    // Onder Apache serveert .htaccess bestanden uit public/ op dit pad.
    define('ASSET_BASE', '/lesmateriaal/eventsite/public/');
}

require_once __DIR__ . '/../src/Routes/Web.php';
try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
} catch (InvalidArgumentException $e) {
    echo 'invalid argument: ' . $e->getMessage();
}
