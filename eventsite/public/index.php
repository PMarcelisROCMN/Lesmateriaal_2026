<?php

use Bramus\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

// De app draait onder /lesmateriaal/eventsite/, niet onder /public/.
// Daarom zetten we de basePath zelf, anders matchen de routes niet.
$router->setBasePath('/lesmateriaal/eventsite/');

require_once __DIR__ . '/../src/Routes/Web.php';
try {
    $router->run();
} catch (Exception $e) {
    echo $e->getMessage();
} catch (InvalidArgumentException $e) {
    echo 'invalid argument: ' . $e->getMessage();
}
