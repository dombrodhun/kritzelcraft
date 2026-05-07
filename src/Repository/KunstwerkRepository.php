<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

/**
 * Repository für Kunstwerke.
 */
class KunstwerkRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Hole alle verfügbaren Kunstwerke aus der Datenbank.
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM kunstwerke WHERE status = 'verfuegbar'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hole ein einzelnes Kunstwerk anhand der ID.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM kunstwerke WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}
