<?php

declare(strict_types=1);

session_start();

/**
 * Front-Controller
 */

require_once __DIR__ . '/../src/Core/Container.php';
require_once __DIR__ . '/../src/Core/Router.php';
require_once __DIR__ . '/../src/Controller/BaseController.php';
require_once __DIR__ . '/../src/Controller/HomeController.php';
require_once __DIR__ . '/../src/Controller/WarenkorbController.php';
require_once __DIR__ . '/../src/Repository/KunstwerkRepository.php';
require_once __DIR__ . '/../src/Repository/WarenkorbRepository.php';

use App\Core\Container;
use App\Core\Router;
use App\Controller\HomeController;
use App\Controller\WarenkorbController;
use App\Repository\KunstwerkRepository;
use App\Repository\WarenkorbRepository;

// Initialisiere DI-Container
$container = new Container();

// Registriere Datenbank-Verbindung (PDO)
$container->set(PDO::class, function (Container $c) {
    $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8mb4", getenv('DB_HOST'), getenv('MARIADB_DATABASE'));
    return new PDO($dsn, getenv('MARIADB_USER'), getenv('MARIADB_PASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
});

// Registriere Repositories
$container->set(KunstwerkRepository::class, function (Container $c) {
    return new KunstwerkRepository($c->get(PDO::class));
});

$container->set(WarenkorbRepository::class, function (Container $c) {
    return new WarenkorbRepository($c->get(PDO::class));
});

// Registriere Controller im Container
$container->set(HomeController::class, function (Container $c) {
    return new HomeController($c->get(KunstwerkRepository::class));
});

$container->set(WarenkorbController::class, function (Container $c) {
    return new WarenkorbController($c->get(WarenkorbRepository::class));
});

// Initialisiere Router und definiere Routen
$router = new Router();

$router->get('/', HomeController::class . '@index');
$router->get('/api/kunstwerke', HomeController::class . '@apiIndex');

// Warenkorb API
$router->get('/api/warenkorb', WarenkorbController::class . '@index');
$router->post('/api/warenkorb/add', WarenkorbController::class . '@add');
$router->post('/api/warenkorb/remove', WarenkorbController::class . '@remove');

// Verarbeite Anfrage
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->dispatch($uri, $method, $container);
} catch (\Exception $e) {
    http_response_code(500);
    echo "Ein interner Fehler ist aufgetreten: " . $e->getMessage();
}
