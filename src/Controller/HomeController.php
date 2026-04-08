<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Controller für die Startseite.
 */
class HomeController extends BaseController
{
    /**
     * Zeige die Startseite an.
     */
    public function index(): void
    {
        $this->render('home');
    }
}
