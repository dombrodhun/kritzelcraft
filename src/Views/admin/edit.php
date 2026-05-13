<?php require __DIR__ . '/layouts/header.php'; ?>
<?php
if (!isset($kunstwerk)) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Fehler: Keine Daten zum Bearbeiten gefunden. <a href='/admin/dashboard'>Zurück</a></div></div>";
    require __DIR__ . '/layouts/footer.php';
    exit;
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4">Kunstwerk bearbeiten</h2>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>

                    <form action="/admin/edit" method="POST">
                        <input type="hidden" name="id" value="<?php echo $kunstwerk['id']; ?>">

                        <div class="mb-3 text-center">
                            <img src="/uploads/<?php echo htmlspecialchars($kunstwerk['bild_name']); ?>" alt="Vorschau" class="img-thumbnail" style="max-height: 200px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Titel</label>
                            <input type="text" name="titel" class="form-control" value="<?php echo htmlspecialchars($kunstwerk['titel']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preis (€)</label>
                            <input type="number" name="preis" step="0.01" class="form-control" value="<?php echo $kunstwerk['preis']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Beschreibung</label>
                            <textarea name="beschreibung" class="form-control" rows="5"><?php echo htmlspecialchars($kunstwerk['beschreibung']); ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                            <a href="/admin/dashboard" class="btn btn-outline-secondary">Abbrechen</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
