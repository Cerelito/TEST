// theme-toggle.js - Sistema de cambio de tema

(function() {
    'use strict';

    // Obtener tema guardado o usar 'light' por defecto
    const getTheme = () => {
        return localStorage.getItem('theme') || 'light';
    };

    // Guardar tema
    const setTheme = (theme) => {
        localStorage.setItem('theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
        updateThemeIcon(theme);
        updateLogos(theme);
    };

    // Actualizar icono del toggle
    const updateThemeIcon = (theme) => {
        const sunIcons = document.querySelectorAll('.theme-toggle-icon.sun');
        const moonIcons = document.querySelectorAll('.theme-toggle-icon.moon');

        if (theme === 'dark') {
            sunIcons.forEach(icon => icon.style.display = 'inline-block');
            moonIcons.forEach(icon => icon.style.display = 'none');
        } else {
            sunIcons.forEach(icon => icon.style.display = 'none');
            moonIcons.forEach(icon => icon.style.display = 'inline-block');
        }
    };

    // Actualizar logos según tema
    const updateLogos = (theme) => {
        const logos = document.querySelectorAll('[data-light-src][data-dark-src]');
        logos.forEach(logo => {
            logo.src = theme === 'dark' ? logo.dataset.darkSrc : logo.dataset.lightSrc;
        });
    };

    // Toggle tema
    const toggleTheme = () => {
        const currentTheme = getTheme();
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        setTheme(newTheme);
    };

    // Inicializar
    const init = () => {
        // Aplicar tema inicial
        setTheme(getTheme());

        // Agregar event listeners a todos los botones de toggle
        const toggleButtons = document.querySelectorAll('.theme-toggle');
        toggleButtons.forEach(button => {
            button.addEventListener('click', toggleTheme);
        });
    };

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
