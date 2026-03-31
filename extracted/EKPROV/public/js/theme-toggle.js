/**
 * theme-toggle.js - Gestión de temas claro/oscuro
 */
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.querySelector('.theme-toggle');
    const html = document.documentElement;

    // Cargar tema guardado (redundancia para scripts cargados tarde)
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            if (newTheme === 'dark') {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }

            // Animación suave del icono si fuera necesario
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.style.transform = 'rotate(360deg)';
                setTimeout(() => icon.style.transform = '', 300);
            }
        });
    }
});
