<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\KunstwerkRepository;

/**
 * Controller für die Startseite.
 */
class HomeController extends BaseController
{
    private KunstwerkRepository $repository;

    public function __construct(KunstwerkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Zeige die Startseite an.
     */
    public function index(): void
    {
        $this->render('home');
    }

    /**
     * API-Endpunkt für die Kunstwerke (JSON).
     */
    public function apiIndex(): void
    {
        $kunstwerke = $this->repository->getAll();
        $this->json($kunstwerke);
    }
}
