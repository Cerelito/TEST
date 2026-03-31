/**
 * public/js/alerts.js
 * Sistema de Alertas Bonitas usando SweetAlert2
 * Versión personalizada para EK Proveedores MVC
 * ACTUALIZADO: Soporte nativo para Tema Oscuro/Claro
 */

// Función para obtener colores según el tema actual (data-theme en html)
function getSwalTheme() {
    // Detectamos si el atributo data-theme es 'dark'
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

    return {
        background: isDark ? '#1f2937' : '#ffffff', // Gris oscuro o Blanco
        color: isDark ? '#f3f4f6' : '#1f2937',      // Texto claro u oscuro
        // Colores de botones (se mantienen constantes o se pueden ajustar)
        confirmButtonColor: '#0d6efd', // Azul primario
        cancelButtonColor: isDark ? '#4b5563' : '#6c757d', // Gris más oscuro para botón cancelar en dark mode
        denyButtonColor: '#dc3545',
        customClass: {
            popup: 'glass-panel', // Usamos tu clase de estilo glass si lo deseas, o vacía
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-glass',
            denyButton: 'btn btn-danger'
        }
    };
}

/**
 * Generador de configuración base dinámica
 * Se llama en cada alerta para asegurar que lea el tema actual
 */
function getSwalConfig() {
    const theme = getSwalTheme();
    return {
        background: theme.background,
        color: theme.color,
        buttonsStyling: false, // Desactivamos estilos por defecto para usar nuestras clases
        confirmButtonColor: theme.confirmButtonColor,
        cancelButtonColor: theme.cancelButtonColor,
        denyButtonColor: theme.denyButtonColor,
        customClass: theme.customClass
    };
}

/**
 * Alerta de éxito
 * @param {string} title - Título de la alerta
 * @param {string} text - Texto de la alerta
 */
function alertSuccess(title, text = '') {
    return Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        confirmButtonText: 'Aceptar',
        ...getSwalConfig()
    });
}

/**
 * Alerta de error
 * @param {string} title - Título de la alerta
 * @param {string} text - Texto de la alerta
 */
function alertError(title, text = '') {
    return Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonText: 'Entendido',
        ...getSwalConfig()
    });
}

/**
 * Alerta de advertencia
 * @param {string} title - Título de la alerta
 * @param {string} text - Texto de la alerta
 */
function alertWarning(title, text = '') {
    return Swal.fire({
        icon: 'warning',
        title: title,
        text: text,
        confirmButtonText: 'Entendido',
        ...getSwalConfig()
    });
}

/**
 * Alerta informativa
 * @param {string} title - Título de la alerta
 * @param {string} text - Texto de la alerta
 */
function alertInfo(title, text = '') {
    return Swal.fire({
        icon: 'info',
        title: title,
        text: text,
        confirmButtonText: 'Aceptar',
        ...getSwalConfig()
    });
}

/**
 * Confirmación con botones Sí/No
 * @param {string} title - Título de la confirmación
 * @param {string} text - Texto de la confirmación
 * @param {string} confirmText - Texto del botón de confirmar (default: 'Sí')
 * @param {string} cancelText - Texto del botón de cancelar (default: 'No')
 * @returns {Promise} Promise que se resuelve con true si confirma, false si cancela
 */
function confirmDialog(title, text = '', confirmText = 'Sí', cancelText = 'No') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true, // Botón de cancelar a la izquierda, confirmar a la derecha
        ...getSwalConfig()
    }).then((result) => {
        return result.isConfirmed;
    });
}

/**
 * Confirmación de eliminación (Rojo)
 * @param {string} itemName - Nombre del elemento a eliminar
 * @param {string} warningText - Texto de advertencia adicional
 * @returns {Promise} Promise que se resuelve con true si confirma
 */
function confirmDelete(itemName = 'este elemento', warningText = 'Esta acción no se puede deshacer') {
    const config = getSwalConfig();
    return Swal.fire({
        title: '¿Está seguro?',
        html: `Está a punto de eliminar <strong>${itemName}</strong>.<br>${warningText}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545', // Forzamos rojo para eliminar
        reverseButtons: true,
        background: config.background,
        color: config.color,
        buttonsStyling: false,
        customClass: {
            popup: 'glass-panel',
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-glass'
        }
    }).then((result) => {
        return result.isConfirmed;
    });
}

/**
 * Alert con input de texto
 * @param {string} title - Título del diálogo
 * @param {string} placeholder - Placeholder del input
 * @param {string} inputValue - Valor inicial del input
 * @returns {Promise} Promise que se resuelve con el valor ingresado o null si cancela
 */
function promptDialog(title, placeholder = '', inputValue = '') {
    return Swal.fire({
        title: title,
        input: 'text',
        inputPlaceholder: placeholder,
        inputValue: inputValue,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        ...getSwalConfig(),
        inputValidator: (value) => {
            if (!value) {
                return 'Este campo es obligatorio';
            }
        }
    }).then((result) => {
        return result.isConfirmed ? result.value : null;
    });
}

/**
 * Alert con textarea
 * @param {string} title - Título del diálogo
 * @param {string} placeholder - Placeholder del textarea
 * @param {string} inputValue - Valor inicial del textarea
 * @returns {Promise} Promise que se resuelve con el valor ingresado o null si cancela
 */
function promptTextarea(title, placeholder = '', inputValue = '') {
    return Swal.fire({
        title: title,
        input: 'textarea',
        inputPlaceholder: placeholder,
        inputValue: inputValue,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        ...getSwalConfig(),
        inputValidator: (value) => {
            if (!value) {
                return 'Este campo es obligatorio';
            }
        }
    }).then((result) => {
        return result.isConfirmed ? result.value : null;
    });
}

/**
 * Loading/Cargando
 * @param {string} title - Título del loading
 * @param {string} text - Texto del loading
 */
function showLoading(title = 'Procesando...', text = 'Por favor espere') {
    const config = getSwalConfig();
    Swal.fire({
        title: title,
        text: text,
        background: config.background,
        color: config.color,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

/**
 * Cerrar loading
 */
function hideLoading() {
    Swal.close();
}

/**
 * Toast notification (mensaje pequeño en esquina)
 * @param {string} icon - Icono (success, error, warning, info)
 * @param {string} title - Título del toast
 */
function showToast(icon, title) {
    const theme = getSwalTheme();
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: theme.background,
        color: theme.color,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: icon,
        title: title
    });
}

// Alias para compatibilidad con alert() nativo
function showAlert(message, title = 'Atención') {
    return alertInfo(title, message);
}

// Alias para compatibilidad con confirm() nativo
function showConfirm(message, title = '¿Está seguro?') {
    return confirmDialog(title, message);
}