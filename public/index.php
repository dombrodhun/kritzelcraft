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
require_once __DIR__ . '/../src/Controller/AdminController.php';
require_once __DIR__ . '/../src/Repository/KunstwerkRepository.php';
require_once __DIR__ . '/../src/Repository/WarenkorbRepository.php';
require_once __DIR__ . '/../src/Repository/BenutzerRepository.php';

use App\Core\Container;
use App\Core\Router;
use App\Controller\HomeController;
use App\Controller\WarenkorbController;
use App\Controller\AdminController;
use App\Repository\KunstwerkRepository;
use App\Repository\WarenkorbRepository;
use App\Repository\BenutzerRepository;

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

$container->set(BenutzerRepository::class, function (Container $c) {
    return new BenutzerRepository($c->get(PDO::class));
});

// Registriere Controller im Container
$container->set(HomeController::class, function (Container $c) {
    return new HomeController($c->get(KunstwerkRepository::class));
});

$container->set(WarenkorbController::class, function (Container $c) {
    return new WarenkorbController($c->get(WarenkorbRepository::class));
});

$container->set(AdminController::class, function (Container $c) {
    return new AdminController($c->get(BenutzerRepository::class), $c->get(KunstwerkRepository::class));
});

// Initialisiere Router und definiere Routen
$router = new Router();

$router->get('/', HomeController::class . '@index');
$router->get('/api/kunstwerke', HomeController::class . '@apiIndex');

// Warenkorb API
$router->get('/api/warenkorb', WarenkorbController::class . '@index');
$router->post('/api/warenkorb/add', WarenkorbController::class . '@add');
$router->post('/api/warenkorb/remove', WarenkorbController::class . '@remove');

// Admin Routen
$router->get('/admin/login', AdminController::class . '@login');
$router->post('/admin/login', AdminController::class . '@login');
$router->get('/admin/logout', AdminController::class . '@logout');
$router->get('/admin/dashboard', AdminController::class . '@dashboard');
$router->post('/admin/upload', AdminController::class . '@upload');
$router->get('/admin/edit', AdminController::class . '@edit');
$router->post('/admin/edit', AdminController::class . '@update');
$router->post('/admin/delete', AdminController::class . '@delete');

// Verarbeite Anfrage
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->dispatch($uri, $method, $container);
} catch (\Exception $e) {
    http_response_code(500);
    echo "Ein interner Fehler ist aufgetreten: " . $e->getMessage();
}
