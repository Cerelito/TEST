// =====================================================
// CATÁLOGO PÚBLICO - JAVASCRIPT
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda de productos
    const searchInput = document.getElementById('buscar-productos');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                buscarProductos(e.target.value);
            }, 300);
        });
    }

    // Lazy loading de imágenes
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

/**
 * Buscar productos (filtrado visual)
 */
function buscarProductos(query) {
    const productos = document.querySelectorAll('.producto-card');
    const searchTerm = query.toLowerCase().trim();

    if (!searchTerm) {
        productos.forEach(card => {
            card.style.display = '';
        });
        return;
    }

    productos.forEach(card => {
        const nombre = card.querySelector('.producto-nombre a')?.textContent.toLowerCase() || '';
        const descripcion = card.querySelector('.producto-descripcion')?.textContent.toLowerCase() || '';
        const categoria = card.querySelector('.producto-categoria')?.textContent.toLowerCase() || '';

        const match = nombre.includes(searchTerm) ||
                     descripcion.includes(searchTerm) ||
                     categoria.includes(searchTerm);

        card.style.display = match ? '' : 'none';
    });

    // Mostrar mensaje si no hay resultados
    const productosVisibles = Array.from(productos).filter(card => card.style.display !== 'none');
    const grid = document.querySelector('.productos-grid');

    if (grid && productosVisibles.length === 0) {
        if (!document.querySelector('.no-results-message')) {
            const message = document.createElement('div');
            message.className = 'no-results-message empty-state';
            message.innerHTML = `
                <i class="bi bi-search"></i>
                <p>No se encontraron productos para "${query}"</p>
            `;
            grid.appendChild(message);
        }
    } else {
        const existingMessage = document.querySelector('.no-results-message');
        if (existingMessage) {
            existingMessage.remove();
        }
    }
}

/**
 * Toggle menú móvil
 */
function toggleMobileMenu() {
    const navLinks = document.querySelector('.navbar-links');
    if (navLinks) {
        navLinks.classList.toggle('active');
    }
}

/**
 * Smooth scroll
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});
