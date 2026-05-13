<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

/**
 * Repository für Benutzer-Daten (Admin).
 */
class BenutzerRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Holt einen Benutzer anhand des Namens.
     */
    public function getByBenutzername(string $name): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM benutzer WHERE benutzername = ?");
        $stmt->execute([$name]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Legt einen neuen Benutzer an (für Initial-Setup).
     */
    public function create(string $name, string $hash): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO benutzer (benutzername, passwort_hash) VALUES (?, ?)");
        $stmt->execute([$name, $hash]);
    }
}
