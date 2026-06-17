<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/bootstrap.php';

use Bramus\Router\Router;

$router = new Router();

// submap-pad waaronder we draaien, voor het opbouwen van links (zie url())
define('BASE_PATH', $router->getBasePath());

require __DIR__ . '/src/Routes/web.php';

$router->run();
