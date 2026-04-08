document.addEventListener('DOMContentLoaded', () => {
    const artGrid = document.getElementById('artwork-grid');
    const cartItemsList = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    let cart = [];


    fetch('data/kunstwerke.json')
        .then(response => response.json())
        .then(artworks => {
            renderArtworks(artworks);
        })
        .catch(err => console.error("Fehler beim Laden der Kunstwerke:", err));

    function renderArtworks(artworks) {
        artGrid.innerHTML = artworks.map(art => `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="uploads/${art.bild_name}" class="card-img-top" alt="${art.titel}">
                    <div class="card-body">
                        <h5 class="card-title">${art.titel}</h5>
                        <p class="card-text text-muted small">${art.beschreibung}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 mb-0">${art.preis.toFixed(2)} €</span>
                            <button class="btn btn-primary buy-btn" data-id="${art.id}" data-titel="${art.titel}" data-preis="${art.preis}" data-bild="uploads/${art.bild_name}">
                                <i class="bi bi-cart-plus"></i> Kaufen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');


        document.querySelectorAll('.buy-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const item = e.currentTarget.dataset;
                addToCart(item);
            });
        });
    }

    function addToCart(item) {
        const exists = cart.find(i => i.id === item.id);
        if (exists) {
            alert("Dieses Unikat ist bereits in deinem Warenkorb!");
            return;
        }

        cart.push(item);
        updateCartUI();

        const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
        cartOffcanvas.show();
    }

    function updateCartUI() {
        cartCount.textContent = cart.length;

        if (cart.length === 0) {
            cartItemsList.innerHTML = '<li class="list-group-item text-center text-muted py-5">Dein Warenkorb ist noch leer.</li>';
            cartTotal.textContent = "0.00";
            return;
        }

        cartItemsList.innerHTML = cart.map((item, index) => `
            <li class="list-group-item cart-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="${item.bild}" class="me-3" alt="${item.titel}">
                    <div>
                        <h6 class="mb-0">${item.titel}</h6>
                        <small class="text-muted">${parseFloat(item.preis).toFixed(2)} €</small>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger remove-btn" data-index="${index}">
                    <i class="bi bi-trash"></i>
                </button>
            </li>
        `).join('');


        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = e.currentTarget.dataset.index;
                removeFromCart(parseInt(index));
            });
        });

        const total = cart.reduce((sum, item) => sum + parseFloat(item.preis), 0);
        cartTotal.textContent = total.toFixed(2);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }
});
