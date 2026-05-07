<?php

declare(strict_types=1);

// Lade DB-Daten aus der Umgebung
$host = getenv('DB_HOST');
$db   = getenv('MARIADB_DATABASE');
$user = getenv('MARIADB_USER');
$pass = getenv('MARIADB_PASSWORD');

if (!$host || !$db || !$user || !$pass) {
    echo "Fehler: Datenbank-Konfiguration unvollständig (Umgebungsvariablen fehlen).\n";
    exit(1);
}

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Verbindung zur Datenbank erfolgreich!\n";

    $sql = file_get_contents(__DIR__ . '/../src/Database/migrations.sql');

    $pdo->exec($sql);

    echo "Datenbank-Schema wurde erfolgreich initialisiert und Daten importiert.\n";

} catch (PDOException $e) {
    echo "Fehler: " . $e->getMessage() . "\n";
    exit(1);
}
