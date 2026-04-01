// app.js - Funciones generales del sistema

// Auto-cerrar alertas (Legacy)
document.querySelectorAll('[data-auto-close]').forEach(alert => {
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
});

// Confirmar eliminación
function confirmDelete(mensaje, formId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: mensaje || 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#002B49', // ek-navy
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            backdrop: `rgba(0,43,73,0.4)`
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    } else {
        if (confirm(mensaje || '¿Está seguro de eliminar este registro?')) {
            document.getElementById(formId).submit();
        }
    }
}

// Loading en formularios
document.querySelectorAll('form[data-loading]').forEach(form => {
    form.addEventListener('submit', function () {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';
        }
    });
});

// Búsqueda en tablas
document.querySelectorAll('[data-table-search]').forEach(input => {
    const tableId = input.dataset.tableSearch;
    const table = document.getElementById(tableId);

    input.addEventListener('keyup', function () {
        const filter = this.value.toUpperCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent || row.innerText;
            row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
        });
    });
});

// Tooltips simples
document.querySelectorAll('[data-tooltip]').forEach(el => {
    el.title = el.dataset.tooltip;
});

// =============================================================================
// NOTIFICATION SYSTEM (Toasts & Desktop)
// =============================================================================
const NotificationSystem = {
    container: null,
    permissionGranted: false,

    init() {
        // Create container if not exists
        if (!document.querySelector(".ug-toast-container")) {
            this.container = document.createElement("div");
            this.container.className = "ug-toast-container";
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector(".ug-toast-container");
        }

        // Check/Request Desktop Permission
        if ("Notification" in window) {
            if (Notification.permission === "granted") {
                this.permissionGranted = true;
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        this.permissionGranted = true;
                    }
                });
            }
        }

        // Check for PHP Flash Messages
        this.checkFlashMessages();
    },

    checkFlashMessages() {
        if (window.flashMessages) {
            if (window.flashMessages.success) {
                this.show("success", window.flashMessages.success);
            }
            if (window.flashMessages.error) {
                this.show("error", window.flashMessages.error);
            }
            if (window.flashMessages.warning) {
                this.show("warning", window.flashMessages.warning);
            }
            if (window.flashMessages.info) {
                this.show("info", window.flashMessages.info);
            }
        }
    },

    show(type, message, title = null) {
        // Desktop Notification
        if (this.permissionGranted && document.hidden) {
            new Notification(title || this.getDefaultTitle(type), {
                body: message,
                icon: "/favicon.ico" // Adjust path if needed
            });
        }

        // In-App Toast
        this.createToast(type, message, title);
    },

    createToast(type, message, title) {
        const toast = document.createElement("div");
        toast.className = `ug-toast ${type}`;

        const iconClass = this.getIcon(type);
        const defaultTitle = title || this.getDefaultTitle(type);

        toast.innerHTML = `
            <div class="ug-toast-icon">
                <i class="${iconClass}"></i>
            </div>
            <div class="ug-toast-content">
                <div class="ug-toast-title">${defaultTitle}</div>
                <div class="ug-toast-message">${message}</div>
            </div>
            <button class="ug-toast-close" onclick="this.parentElement.classList.add('hiding'); setTimeout(() => this.parentElement.remove(), 300);">
                <i class="bi bi-x"></i>
            </button>
        `;

        this.container.appendChild(toast);

        // Auto remove
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add("hiding");
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    },

    getIcon(type) {
        switch (type) {
            case "success": return "bi bi-check-lg";
            case "error": return "bi bi-exclamation-octagon";
            case "warning": return "bi bi-exclamation-triangle";
            case "info": return "bi bi-info-circle";
            default: return "bi bi-bell";
        }
    },

    getDefaultTitle(type) {
        switch (type) {
            case "success": return "¡Éxito!";
            case "error": return "Error";
            case "warning": return "Atención";
            case "info": return "Información";
            default: return "Notificación";
        }
    }
};

// Initialize on load
document.addEventListener("DOMContentLoaded", () => {
    NotificationSystem.init();
});
