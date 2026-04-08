<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Ein abstrakter Basis-Controller für alle Web-Controller.
 */
abstract class BaseController
{
    /**
     * Rendere eine View-Datei (mit PHP-Inklusion).
     */
    protected function render(string $view, array $data = []): void
    {
        // Mache Daten für die View verfügbar
        extract($data);
        
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View '{$viewPath}' wurde nicht gefunden.");
        }
        
        require $viewPath;
    }

    /**
     * Sende eine JSON-Antwort.
     */
    protected function json(mixed $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}
