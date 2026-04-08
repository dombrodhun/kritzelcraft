<?php

declare(strict_types=1);

/**
 * Front-Controller
 */

require_once __DIR__ . '/../src/Core/Container.php';
require_once __DIR__ . '/../src/Core/Router.php';
require_once __DIR__ . '/../src/Controller/BaseController.php';
require_once __DIR__ . '/../src/Controller/HomeController.php';

use App\Core\Container;
use App\Core\Router;
use App\Controller\HomeController;

// Initialisiere DI-Container
$container = new Container();

// Registriere Controller im Container
$container->set(HomeController::class, function (Container $c) {
    return new HomeController();
});

// Initialisiere Router und definiere Routen
$router = new Router();

$router->get('/', HomeController::class . '@index');

// Verarbeite Anfrage
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->dispatch($uri, $method, $container);
} catch (\Exception $e) {
    http_response_code(500);
    echo "Ein interner Fehler ist aufgetreten: " . $e->getMessage();
}
