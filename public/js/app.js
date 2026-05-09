/**
 * Frontend - Hauptlogik
 * Verarbeitet die Galerie-Anzeige und die Warenkorb-Interaktionen via AJAX.
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- DOM-Elemente ---
    const artGrid = document.getElementById('artwork-grid');
    const cartItemsList = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    /** @type {Array} Aktueller lokaler Stand des Warenkorbs */
    let cart = [];

    /**
     * Initialisiert die Anwendung.
     * Lädt parallel alle Kunstwerke und den aktuellen Session-Warenkorb vom Server.
     */
    function init() {
        Promise.all([
            fetch('/api/kunstwerke').then(res => res.json()),
            fetch('/api/warenkorb').then(res => res.json())
        ])
        .then(([artworks, cartItems]) => {
            cart = cartItems;
            renderArtworks(artworks);
            updateCartUI();
        })
        .catch(err => console.error("Fehler beim Initialisieren:", err));
    }

    // Startet die App
    init();

    /**
     * Erzeugt das HTML für die Kunstgalerie.
     * @param {Array} artworks - Liste der Kunstwerk-Objekte vom Server.
     */
    function renderArtworks(artworks) {
        if (!artGrid) return;

        artGrid.innerHTML = artworks.map(art => `
            <div class="col-md-4 mb-4">
                <div class="card h-100 ${art.status !== 'verfuegbar' ? 'opacity-50' : ''}">
                    <img src="uploads/${art.bild_name}" class="card-img-top" alt="${art.titel}">
                    <div class="card-body">
                        <h5 class="card-title">${art.titel}</h5>
                        <p class="card-text text-muted small">${art.beschreibung}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 mb-0">${parseFloat(art.preis).toFixed(2)} €</span>
                            ${art.status === 'verfuegbar'
                                ? `<button class="btn btn-primary buy-btn" data-id="${art.id}">
                                     <i class="bi bi-cart-plus"></i> Kaufen
                                   </button>`
                                : `<span class="badge bg-secondary">Reserviert</span>`
                            }
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        // Event-Listener für neu erstellte Buttons binden
        document.querySelectorAll('.buy-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                addToCart(parseInt(id));
            });
        });
    }

    /**
     * Sendet eine Anfrage zum Hinzufügen eines Kunstwerks an den Server.
     * Nutzt die Unikat-Logik (Reservierung) im Backend.
     * @param {number} id - ID des zu kaufenden Kunstwerks.
     */
    function addToCart(id) {
        fetch('/api/warenkorb/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // UI neu synchronisieren (lädt Status 'reserviert' nach)
                init();
                const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
                cartOffcanvas.show();
            } else {
                alert(data.message || "Fehler beim Hinzufügen");
            }
        })
        .catch(err => console.error("Fehler beim Hinzufügen:", err));
    }

    /**
     * Aktualisiert die Anzeige des Warenkorb-Offcanvas basierend auf der 'cart' Variable.
     */
    function updateCartUI() {
        cartCount.textContent = cart.length;

        // Leeren Warenkorb anzeigen
        if (cart.length === 0) {
            cartItemsList.innerHTML = '<li class="list-group-item text-center text-muted py-5">Dein Warenkorb ist noch leer.</li>';
            cartTotal.textContent = "0.00";
            return;
        }

        // Liste der Items generieren
        cartItemsList.innerHTML = cart.map((item) => `
            <li class="list-group-item cart-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="uploads/${item.bild_name}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="${item.titel}">
                    <div>
                        <h6 class="mb-0">${item.titel}</h6>
                        <small class="text-muted">${parseFloat(item.preis).toFixed(2)} €</small>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger remove-btn" data-id="${item.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </li>
        `).join('');

        // Event-Listener für Löschen-Buttons
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                removeFromCart(parseInt(id));
            });
        });

        // Gesamtsumme berechnen
        const total = cart.reduce((sum, item) => sum + parseFloat(item.preis), 0);
        cartTotal.textContent = total.toFixed(2);
    }

    /**
     * Entfernt ein Kunstwerk aus dem Warenkorb und gibt es auf dem Server wieder frei.
     * @param {number} id - ID des zu entfernenden Kunstwerks.
     */
    function removeFromCart(id) {
        fetch('/api/warenkorb/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                init(); // UI neu laden
            }
        })
        .catch(err => console.error("Fehler beim Entfernen:", err));
    }
});
