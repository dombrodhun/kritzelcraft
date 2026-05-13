<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <!-- Neues Kunstwerk Formular -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4">Neues Kunstwerk</h4>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">Aktion erfolgreich!</div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>

                    <form action="/admin/upload" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Titel</label>
                            <input type="text" name="titel" class="form-control" required placeholder="z.B. Sonniger Tag">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preis (€)</label>
                            <input type="number" name="preis" step="0.01" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Beschreibung</label>
                            <textarea name="beschreibung" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bild (JPG/PNG)</label>
                            <input type="file" name="bild" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Kunstwerk hinzufügen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kunstwerke Liste -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4">Vorhandene Kunstwerke</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bild</th>
                                    <th>Titel</th>
                                    <th>Preis</th>
                                    <th>Status</th>
                                    <th>Aktionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($kunstwerke) && is_array($kunstwerke)): ?>
                                    <?php foreach ($kunstwerke as $kunstwerk): ?>
                                    <tr>
                                        <td>
                                            <img src="/uploads/<?php echo htmlspecialchars($kunstwerk['bild_name']); ?>"
                                                 alt="Vorschau"
                                                 class="img-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($kunstwerk['titel']); ?></strong></td>
                                        <td><?php echo number_format((float)$kunstwerk['preis'], 2, ',', '.'); ?> €</td>
                                        <td>
                                            <?php
                                                $statusClass = 'bg-secondary';
                                                if ($kunstwerk['status'] === 'verfuegbar') $statusClass = 'bg-success';
                                                if ($kunstwerk['status'] === 'reserviert') $statusClass = 'bg-warning text-dark';
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($kunstwerk['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/admin/edit?id=<?php echo $kunstwerk['id']; ?>"
                                                   class="btn btn-outline-primary"
                                                   title="Bearbeiten">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="/admin/delete" method="POST" onsubmit="return confirm('Wirklich löschen?');" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?php echo $kunstwerk['id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger" title="Löschen">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">Keine Kunstwerke gefunden.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
