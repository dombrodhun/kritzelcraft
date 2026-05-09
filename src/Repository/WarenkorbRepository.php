<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;
use Exception;

/**
 * Repository für den Warenkorb und die Unikat-Logik.
 */
class WarenkorbRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Fügt ein Kunstwerk zum Warenkorb hinzu und reserviert es.
     * Nutze Transaktionen und FOR UPDATE für die Unikat-Sicherheit.
     */
    public function add(string $sessionId, int $kunstwerkId): bool
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Prüfe Status mit Sperre (FOR UPDATE)
            $stmt = $this->pdo->prepare("SELECT status FROM kunstwerke WHERE id = ? FOR UPDATE");
            $stmt->execute([$kunstwerkId]);
            $art = $stmt->fetch();

            if (!$art || $art['status'] !== 'verfuegbar') {
                $this->pdo->rollBack();
                return false;
            }

            // 2. Setze den Status auf reserviert
            $updateStmt = $this->pdo->prepare("UPDATE kunstwerke SET status = 'reserviert' WHERE id = ?");
            $updateStmt->execute([$kunstwerkId]);

            // 3. Trage es in die Warenkorb-Tabelle ein
            $insertStmt = $this->pdo->prepare("INSERT INTO warenkorb (session_id, kunstwerk_id) VALUES (?, ?)");
            $insertStmt->execute([$sessionId, $kunstwerkId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Entfernt ein Kunstwerk aus dem Warenkorb und gibt es wieder frei.
     */
    public function remove(string $sessionId, int $kunstwerkId): void
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Aus Warenkorb löschen
            $stmt = $this->pdo->prepare("DELETE FROM warenkorb WHERE session_id = ? AND kunstwerk_id = ?");
            $stmt->execute([$sessionId, $kunstwerkId]);

            // 2. Status wieder auf verfuegbar setzen
            $updateStmt = $this->pdo->prepare("UPDATE kunstwerke SET status = 'verfuegbar' WHERE id = ?");
            $updateStmt->execute([$kunstwerkId]);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Holt alle Kunstwerke im Warenkorb einer Session.
     */
    public function getItems(string $sessionId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT k.*
            FROM kunstwerke k
            JOIN warenkorb w ON k.id = w.kunstwerk_id
            WHERE w.session_id = ?
        ");
        $stmt->execute([$sessionId]);
        return $stmt->fetchAll();
    }
}
