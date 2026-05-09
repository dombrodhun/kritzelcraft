<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\WarenkorbRepository;

/**
 * Controller für Warenkorb-Aktionen.
 */
class WarenkorbController extends BaseController
{
    private WarenkorbRepository $repository;

    public function __construct(WarenkorbRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Gibt den aktuellen Warenkorb als JSON zurück.
     */
    public function index(): void
    {
        $items = $this->repository->getItems(session_id());
        $this->json($items);
    }

    /**
     * Fügt ein Item zum Warenkorb hinzu.
     */
    public function add(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? (int)$input['id'] : 0;

        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'Ungültige ID'], 400);
            return;
        }

        $success = $this->repository->add(session_id(), $id);

        if ($success) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Kunstwerk nicht mehr verfügbar'], 409);
        }
    }

    /**
     * Entfernt ein Item aus dem Warenkorb.
     */
    public function remove(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? (int)$input['id'] : 0;

        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'Ungültige ID'], 400);
            return;
        }

        $this->repository->remove(session_id(), $id);
        $this->json(['success' => true]);
    }
}
