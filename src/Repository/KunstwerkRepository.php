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
     * Holt alle verfügbaren Kunstwerke aus der Datenbank.
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM kunstwerke");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Holt ein einzelnes Kunstwerk anhand der ID.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM kunstwerke WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Erstellt ein neues Kunstwerk in der Datenbank.
     */
    public function create(string $titel, string $beschreibung, float $preis, string $bildName): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO kunstwerke (titel, beschreibung, preis, bild_name, status) 
            VALUES (?, ?, ?, ?, 'verfuegbar')
        ");
        $stmt->execute([$titel, $beschreibung, $preis, $bildName]);
    }

    /**
     * Aktualisiert ein bestehendes Kunstwerk.
     */
    public function update(int $id, string $titel, string $beschreibung, float $preis): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE kunstwerke 
            SET titel = ?, beschreibung = ?, preis = ? 
            WHERE id = ?
        ");
        $stmt->execute([$titel, $beschreibung, $preis, $id]);
    }

    /**
     * Löscht ein Kunstwerk aus der Datenbank.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM kunstwerke WHERE id = ?");
        $stmt->execute([$id]);
    }
}
