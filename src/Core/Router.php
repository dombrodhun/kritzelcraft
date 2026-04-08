<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Router für Front-Controller-Pattern.
 */
class Router
{
    private array $routes = [];

    /**
     * Registriere GET-Route.
     */
    public function get(string $path, string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Registriere POST-Route.
     */
    public function post(string $path, string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Löse Anfrage auf und führe entsprechenden Controller aus.
     */
    public function dispatch(string $uri, string $method, Container $container): void
    {
        // Entferne Query-String
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $methodMatch = ($route['method'] === $method) || ($route['method'] === 'GET' && $method === 'HEAD');
            if ($methodMatch && $route['path'] === $uri) {
                [$controllerName, $action] = explode('@', $route['handler']);

                // Hole Controller aus dem Container
                $controller = $container->get($controllerName);

                // Führe Aktion aus
                $controller->$action();
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Seite nicht gefunden.";
    }
}
