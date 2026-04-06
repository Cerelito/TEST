<!-- Cookies Consent Popup -->
<div id="ek-cookies-popup" class="ek-cookies-popup">
    <div class="ek-cookies-content">
        <div class="ek-cookies-header">
            <div class="ek-cookies-icon">
                <i class="bi bi-cookie"></i>
            </div>
            <h4>Uso de Cookies</h4>
        </div>
        <div class="ek-cookies-text">
            <p>Utilizamos cookies propias y de terceros para mejorar tu experiencia de navegación y analizar el tráfico
                del sitio.</p>
        </div>
        <div class="ek-cookies-actions">
            <button class="ek-btn-secondary" onclick="rejectCookies()">Rechazar</button>
            <button class="ek-btn-primary" onclick="acceptCookies()">Aceptar</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!localStorage.getItem('cookies_accepted')) {
            setTimeout(() => {
                document.getElementById('ek-cookies-popup').classList.add('show');
            }, 2000);
        }
    });

    function acceptCookies() {
        localStorage.setItem('cookies_accepted', 'true');
        const popup = document.getElementById('ek-cookies-popup');
        popup.classList.add('hide');
        setTimeout(() => {
            popup.classList.remove('show', 'hide');
        }, 500);
    }

    function rejectCookies() {
        localStorage.setItem('cookies_accepted', 'false');
        const popup = document.getElementById('ek-cookies-popup');
        popup.classList.add('hide');
        setTimeout(() => {
            popup.classList.remove('show', 'hide');
        }, 500);
    }
</script>

<style>
    .ek-cookies-popup {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%) translateY(150%);
        width: calc(100% - 2rem);
        max-width: 420px;
        background: rgba(30, 30, 30, 0.85);
        background: #002B49;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4),
            0 2px 8px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        padding: 1.75rem;
        opacity: 0;
        pointer-events: none;
        transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        margin-bottom: 1.5rem;
    }

    .ek-cookies-popup.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
        pointer-events: all;
    }

    .ek-cookies-popup.hide {
        transform: translateX(-50%) translateY(150%);
        opacity: 0;
        pointer-events: none;
    }

    .ek-cookies-content {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .ek-cookies-header {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .ek-cookies-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, var(--ek-orange, #ff6b35) 0%, #ff8c42 100%);
        border-radius: 12px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    }

    .ek-cookies-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .ek-cookies-text h4 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: white;
        letter-spacing: -0.02em;
    }

    .ek-cookies-text p {
        margin: 0;
        font-size: 0.9375rem;
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.55;
        letter-spacing: -0.01em;
    }

    .ek-cookies-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.25rem;
    }

    .ek-cookies-actions button {
        flex: 1;
        padding: 0.75rem 1.25rem;
        border: none;
        border-radius: 12px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: -0.01em;
    }

    .ek-btn-primary {
        background: linear-gradient(135deg, var(--ek-orange, #ff6b35) 0%, #ff8c42 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
    }

    .ek-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
    }

    .ek-btn-primary:active {
        transform: translateY(0);
    }

    .ek-btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .ek-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.25);
    }

    .ek-btn-secondary:active {
        background: rgba(255, 255, 255, 0.08);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .ek-cookies-popup {
            max-width: 400px;
            padding: 1.5rem;
            border-radius: 18px;
            margin-bottom: 1.25rem;
        }

        .ek-cookies-icon {
            width: 40px;
            height: 40px;
        }

        .ek-cookies-icon i {
            font-size: 1.375rem;
        }

        .ek-cookies-text h4 {
            font-size: 1.0625rem;
        }

        .ek-cookies-text p {
            font-size: 0.875rem;
        }

        .ek-cookies-actions button {
            padding: 0.6875rem 1rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .ek-cookies-popup {
            width: calc(100% - 1.5rem);
            max-width: none;
            padding: 1.25rem;
            border-radius: 16px;
            margin-bottom: 1rem;
        }

        .ek-cookies-content {
            gap: 1rem;
        }

        .ek-cookies-header {
            gap: 0.75rem;
        }

        .ek-cookies-icon {
            width: 36px;
            height: 36px;
        }

        .ek-cookies-icon i {
            font-size: 1.25rem;
        }

        .ek-cookies-text h4 {
            font-size: 1rem;
        }

        .ek-cookies-text p {
            font-size: 0.8125rem;
            line-height: 1.5;
        }

        .ek-cookies-actions {
            gap: 0.625rem;
        }

        .ek-cookies-actions button {
            padding: 0.625rem 0.875rem;
            font-size: 0.8125rem;
            border-radius: 10px;
        }
    }

    @media (max-width: 380px) {
        .ek-cookies-popup {
            padding: 1rem;
        }

        .ek-cookies-actions {
            flex-direction: column;
        }

        .ek-cookies-actions button {
            width: 100%;
        }
    }
</style>