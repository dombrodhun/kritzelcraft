<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BenutzerRepository;
use App\Repository\KunstwerkRepository;

/**
 * Controller für den Admin-Bereich.
 */
class AdminController extends BaseController
{
    private BenutzerRepository $benutzerRepo;
    private KunstwerkRepository $kunstwerkRepo;

    public function __construct(BenutzerRepository $benutzerRepo, KunstwerkRepository $kunstwerkRepo)
    {
        $this->benutzerRepo = $benutzerRepo;
        $this->kunstwerkRepo = $kunstwerkRepo;
    }

    /**
     * Login-Formular anzeigen oder verarbeiten.
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';

            $admin = $this->benutzerRepo->getByBenutzername($user);

            if ($admin && password_verify($pass, $admin['passwort_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                header('Location: /admin/dashboard');
                exit;
            }

            $error = "Ungültiger Benutzername oder Passwort.";
            $this->render('admin/login', ['error' => $error]);
            return;
        }

        $this->render('admin/login');
    }

    /**
     * Logout.
     */
    public function logout(): void
    {
        unset($_SESSION['admin_logged_in']);
        header('Location: /admin/login');
        exit;
    }

    /**
     * Admin Dashboard.
     */
    public function dashboard(): void
    {
        $this->checkAuth();

        // Wir nennen die Variable hier lokal $daten
        $daten = $this->kunstwerkRepo->getAll();

        // Und übergeben sie unter dem Namen 'kunstwerke' an die View
        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'kunstwerke' => $daten
        ]);
    }

    /**
     * Verarbeitet den Upload eines neuen Kunstwerks.
     */
    public function upload(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titel = $_POST['titel'] ?? '';
            $preis = (float)($_POST['preis'] ?? 0);
            $beschreibung = $_POST['beschreibung'] ?? '';
            $file = $_FILES['bild'] ?? null;

            // Einfache Validierung
            if (!$titel || $preis <= 0 || !$file || $file['error'] !== UPLOAD_ERR_OK) {
                header('Location: /admin/dashboard?error=Eingabe unvollständig');
                exit;
            }

            // Bild-Validierung & Speicherung
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($file['type'], $allowedTypes)) {
                header('Location: /admin/dashboard?error=Ungültiger Dateityp');
                exit;
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = uniqid('art_', true) . '.' . $ext;
            $uploadPath = __DIR__ . '/../../public/uploads/' . $newName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // In DB speichern (Logik im KunstwerkRepository ergänzen wir noch)
                $this->kunstwerkRepo->create($titel, $beschreibung, $preis, $newName);
                header('Location: /admin/dashboard?success=1');
                exit;
            }
        }
    }

    /**
     * Zeigt das Bearbeitungs-Formular für ein Kunstwerk.
     */
    public function edit(): void
    {
        $this->checkAuth();

        $id = (int)($_GET['id'] ?? 0);
        $art = $this->kunstwerkRepo->getById($id);

        if (!$art) {
            header('Location: /admin/dashboard?error=Kunstwerk nicht gefunden');
            exit;
        }

        // Wir übergeben das Kunstwerk explizit unter dem Key 'kunstwerk'
        $this->render('admin/edit', [
            'kunstwerk' => $art
        ]);
    }

    /**
     * Verarbeitet die Aktualisierung eines Kunstwerks.
     */
    public function update(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $titel = $_POST['titel'] ?? '';
            $preis = (float)($_POST['preis'] ?? 0);
            $beschreibung = $_POST['beschreibung'] ?? '';

            if (!$id || !$titel || $preis <= 0) {
                header("Location: /admin/edit?id=$id&error=Eingabe ungültig");
                exit;
            }

            $this->kunstwerkRepo->update($id, $titel, $beschreibung, $preis);
            header('Location: /admin/dashboard?success=Aktualisiert');
            exit;
        }
    }

    /**
     * Löscht ein Kunstwerk.
     */
    public function delete(): void
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->kunstwerkRepo->delete($id);
                header('Location: /admin/dashboard?success=Gelöscht');
                exit;
            }
        }
        header('Location: /admin/dashboard');
        exit;
    }

    /**
     * Prüft, ob der Admin eingeloggt ist.
     */
    private function checkAuth(): void
    {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: /admin/login');
            exit;
        }
    }
}
