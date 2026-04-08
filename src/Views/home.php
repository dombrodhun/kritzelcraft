<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kritzelcraft | Echte Kinderkunst für dein Zuhause</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="bi bi-palette-fill me-2"></i>Kritzelcraft
            </a>
            <button class="btn btn-outline-primary position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                <i class="bi bi-bag-heart-fill"></i>
                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">Verwandle Kritzeleien in <span class="text-primary">Echtes Karma</span>.</h1>
            <p class="lead mb-4">Entdecke einzigartige Kunstwerke, gemalt von einem kleinen Künstler für große Herzen. Jedes Stück ist ein Unikat.</p>
            <a href="#galerie" class="btn btn-primary btn-lg px-5">Jetzt stöbern</a>
        </div>
    </header>

    <!-- Galerie Section -->
    <main id="galerie" class="container mb-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold">Aktuelle Unikate</h2>
                <hr class="w-25 mx-auto text-primary border-3">
            </div>
        </div>

        <!-- Artwork Grid (per JS) -->
        <div id="artwork-grid" class="row">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Laden...</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Warenkorb Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">
                <i class="bi bi-bag-heart-fill me-2"></i>Dein Warenkorb
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul id="cart-items" class="list-group list-group-flush mb-4">
                <!-- Warenkorb-Items -->
            </ul>

            <div class="d-grid gap-2 border-top pt-4">
                <div class="d-flex justify-content-between mb-3 px-2">
                    <span class="h5 fw-bold">Gesamtsumme:</span>
                    <span class="h5 fw-bold"><span id="cart-total">0.00</span> €</span>
                </div>
                <button class="btn btn-primary btn-lg">Zur Kasse</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center">
            <p class="text-muted small mb-0">&copy; 2026 Kritzelcraft - Ein Uni-Projekt für die Demonstration von Unikat-Shops.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="js/app.js"></script>
</body>
</html>
