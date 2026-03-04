/**
 * public/js/app.js
 * Utilidades comunes del sistema, alertas y visor de documentos.
 */

// 1. Auto-cerrar alertas flash (mensajes de sesión)
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert[data-auto-close]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000); // 5 segundos
    });
});

// 2. Helper para obtener el tema actual (Claro/Oscuro) para SweetAlert
function getSwalTheme() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

    return {
        background: isDark ? '#1f2937' : '#ffffff',
        color: isDark ? '#f3f4f6' : '#1f2937',
        confirmButtonColor: 'var(--primary)',
        cancelButtonColor: isDark ? '#4b5563' : '#6c757d',
        // Clases base para estilizado
        customClass: {
            popup: 'glass-panel',
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-glass',
            denyButton: 'btn btn-danger'
        }
    };
}

// 3. Ayudantes de SweetAlert2 Globales (Adaptables al Tema)

window.alertSuccess = (title, text) => {
    const theme = getSwalTheme();
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        background: theme.background,
        color: theme.color,
        confirmButtonColor: theme.confirmButtonColor,
        customClass: theme.customClass
    });
};

window.alertError = (title, text) => {
    const theme = getSwalTheme();
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        background: theme.background,
        color: theme.color,
        confirmButtonColor: 'var(--danger)',
        customClass: theme.customClass
    });
};

window.alertWarning = (title, text) => {
    const theme = getSwalTheme();
    Swal.fire({
        icon: 'warning',
        title: title,
        text: text,
        background: theme.background,
        color: theme.color,
        confirmButtonColor: 'var(--warning)',
        customClass: theme.customClass
    });
};

window.confirmDialog = async (title, text, confirmText = 'Sí, continuar', cancelText = 'Cancelar') => {
    const theme = getSwalTheme();
    const result = await Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        background: theme.background,
        color: theme.color,
        confirmButtonColor: theme.confirmButtonColor,
        cancelButtonColor: theme.cancelButtonColor,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        customClass: theme.customClass
    });
    return result.isConfirmed;
};

// 4. Visor de Documentos PDF (Lightbox Mejorado)
// Soluciona el problema del marco negro usando las clases CSS nuevas
window.verDocumento = (url, titulo) => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

    // Detección mejorada: si el URL contiene extensiones de imagen o el parámetro format=image/isImage=1
    const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(url) || url.includes('format=image') || url.includes('isImage=1');

    const htmlContent = isImage
        ? `<div class="pdf-lightbox-container" style="background: #111; overflow: hidden;">
               <div class="image-zoom-wrapper" id="zoomWrapper">
                   <img src="${url}" class="img-zoomable" id="zoomImage">
               </div>
           </div>`
        : `<div class="pdf-lightbox-container">
               <iframe src="${url}" class="pdf-lightbox-iframe"></iframe>
           </div>`;

    let mouseMoveHandler, mouseUpHandler;

    Swal.fire({
        title: titulo,
        html: htmlContent,
        width: '95%',
        padding: '1rem',
        showCloseButton: true,
        showConfirmButton: false,
        background: isDark ? '#1f2937' : '#ffffff',
        color: isDark ? '#f3f4f6' : '#1f2937',
        backdrop: isDark ? `rgba(0,0,0,0.85)` : `rgba(0,0,0,0.6)`,
        didOpen: () => {
            if (isImage) {
                const wrapper = document.getElementById('zoomWrapper');
                const img = document.getElementById('zoomImage');
                let scale = 1;
                let isDragging = false;
                let startX, startY;
                let translateX = 0, translateY = 0;

                const updateTransform = () => {
                    img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
                };

                wrapper.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    const delta = e.deltaY > 0 ? -0.15 : 0.15;
                    const prevScale = scale;
                    scale = Math.min(Math.max(0.5, scale + delta), 10);

                    // Si el zoom es muy pequeño, resetear posición
                    if (scale <= 1) {
                        translateX = 0;
                        translateY = 0;
                    }
                    updateTransform();
                });

                wrapper.addEventListener('mousedown', (e) => {
                    if (e.button !== 0) return;
                    isDragging = true;
                    startX = e.clientX - translateX;
                    startY = e.clientY - translateY;
                    wrapper.style.cursor = 'grabbing';
                });

                mouseMoveHandler = (e) => {
                    if (!isDragging) return;
                    translateX = e.clientX - startX;
                    translateY = e.clientY - startY;
                    updateTransform();
                };

                mouseUpHandler = () => {
                    isDragging = false;
                    if (wrapper) wrapper.style.cursor = 'grab';
                };

                window.addEventListener('mousemove', mouseMoveHandler);
                window.addEventListener('mouseup', mouseUpHandler);
            }
        },
        willClose: () => {
            if (mouseMoveHandler) window.removeEventListener('mousemove', mouseMoveHandler);
            if (mouseUpHandler) window.removeEventListener('mouseup', mouseUpHandler);
        },
        customClass: {
            popup: 'glass-panel pdf-lightbox-popup',
            title: 'pdf-lightbox-title',
            closeButton: 'pdf-lightbox-close'
        }
    });
};
