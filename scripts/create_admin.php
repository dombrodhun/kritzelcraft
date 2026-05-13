<?php

declare(strict_types=1);

/**
 * CLI-Skript zum Erstellen eines Admin-Benutzers.
 */

require_once __DIR__ . '/../src/Repository/BenutzerRepository.php';

use App\Repository\BenutzerRepository;

$host = getenv('DB_HOST');
$db   = getenv('MARIADB_DATABASE');
$user = getenv('MARIADB_USER');
$pass = getenv('MARIADB_PASSWORD');

if (!$host || !$db || !$user || !$pass) {
    die("Fehler: Umgebungsvariablen DB_HOST, MARIADB_DATABASE, MARIADB_USER oder MARIADB_PASSWORD fehlen.\n");
}

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$repo = new BenutzerRepository($pdo);

echo "--- Kritzelcraft Admin Setup ---\n";
echo "Benutzername: ";
$username = trim(fgets(STDIN));
echo "Passwort: ";
$password = trim(fgets(STDIN));

if (!$username || !$password) {
    die("Fehler: Benutzername und Passwort dürfen nicht leer sein.\n");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $repo->create($username, $hash);
    echo "Erfolg: Admin-Benutzer '$username' wurde erstellt!\n";
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage() . "\n";
}
